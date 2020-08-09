<?php
/*
Plugin Name: html5-chat
Plugin URI: https://html5-chat.com/
Description: Plugin to integrate HTML5 chat to you WP blog, including avatar and username auto login.
Version: 1.02
Author: Proxymis
Author URI: contact@proxymis.com
*/

class HtmlChat
{
    private static $scriptUrl = 'https://html5-chat.com/scriptWP.php';
    private static $loginURL = 'https://html5-chat.com/chatadmin/';
    private static $registerAccountUrl = 'https://html5-chat.com/ajax.php';
    private static $noticeName = 'html5chat-notice';
    private static $domain;
    private $countShortcode = 0;
    private $adminPanel;
    private $code;
    private static $genderField;

    /*
     * init
     */
    function __construct(){
        $this->init();
        $this->setEvents();
    }

    /*
     * create an account when plugin activated
     */
    static function pluginActivated() {

        $user = wp_get_current_user();
        $roles = $user->roles;
        $isAdmin =  (in_array('administrator', $roles));
        $email = $user->user_email;
        $username = $user->user_login;;
        $domain = get_site_url(null, '', '');
        if (!$domain) {
            $domain = get_home_url(null, '', '');
        }
        if (!$domain) {
            $domain = $_SERVER['SERVER_NAME'];
        }
        $domain = parse_url($domain)['host'];
        $wp_register_url = wp_registration_url();
        $wp_login_url = wp_login_url();

        $params = array (
            'a'                 =>'createAccountWP',
            'username'          =>$username,
            'email'             =>$email,
            'isAdmin'           =>$isAdmin,
            'url'               =>$domain,
            'wp_register_url'   =>$wp_register_url,
            'wp_login_url'      =>$wp_login_url);

        $query = http_build_query ($params);
        $contextData = array (
            'method' => 'POST',
            'header' => "Connection: close\r\n"."Content-Length: ".strlen($query)."\r\n",
            'content'=> $query
        );

        function file_get_contents2($url, $data){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $res = curl_exec($ch);
            curl_close($ch);
            return $res;
        }
        if( !ini_get('allow_url_fopen') ) {
            $result = file_get_contents2(self::$registerAccountUrl, $params);
        } else {
            $context = stream_context_create (array ( 'http' => $contextData ));
            $result =  file_get_contents (self::$registerAccountUrl, false, $context);
        }
        set_transient( self::$noticeName, $result, 5 );
    }

    /*
     * display notice when account is activated
     */
    static function display_notice() {
        $jsonString = get_transient( self::$noticeName);
        $json = json_decode($jsonString);
        if ($json && $json->message) {
            echo "<div id='message' class='updated notice is-dismissible'>{$json->message}</div>";
        }
        delete_transient( self::$noticeName );
    }

    function init() {
        self::$domain = $this->getDomain();
    }

    function setEvents() {
        add_action('admin_init', array($this, 'adminInit'));
        add_action('admin_menu', array($this, 'setMenu'));
        add_shortcode('HTML5CHAT', array($this, 'doShortcode'));
        add_filter('the_content', 'do_shortcode');
        add_filter('mce_external_plugins', array($this, 'enqueuePluginScripts'));
        add_filter('mce_buttons', array($this, 'registerButtonEditor'));
    }

    function adminInit() {
        wp_register_style('html5-chat-style', plugin_dir_url( __FILE__ ) . 'css/style.css');
    }

    function styleAdmin() {
        wp_enqueue_style('html5-chat-style');
    }
    //-------------------------------------------------------------------------------------------------------------------------------
    /*
     * shortcode
     */
    function isSingleShortcode() {
        return $this->countShortcode == 0;
    }

    function isLoggedon() {
        $current_user = wp_get_current_user();
        return ($current_user instanceof WP_User);
    }

    function getDomain() {
        $str = get_site_url(null, '', '');
        $str = parse_url($str)['host'];
        return $str;
    }

    function getCurrentUser() {
        $current_user = wp_get_current_user();
        return $current_user->user_login;
    }

    function getSrcScript($width='100%', $height='fullscreen') {
        $roles = wp_get_current_user()->roles;
        $role = ($roles) ? $roles[0] : 'user';
        $isAdmin =  in_array('administrator', $roles);
        $currentUser = wp_get_current_user();
        $email = $currentUser->user_email;
        $src = self::$scriptUrl;
        $src .= '?url='. urlencode(self::$domain);
        $cache = time();
        if ($currentUser) {
            $src .= '&userid='.$currentUser->ID;
            $src .= '&username='.$currentUser->user_login;
            $src .= '&avatar='.urlencode(get_avatar_url($currentUser->ID));
            // test if buddyPress is installed
            if (function_exists('bp_has_profile')) {
                $src .= '&gender=' . $this->bbGetGenderUser();
            }
        }
        $src.="&width=$width&height=$height&isAdmin=$isAdmin&email=$email&cache=$cache&role=$role";
        $password = $currentUser->user_login;
        $hash = crypt($src, $password);
        return  $src."&h=$hash";
    }

    function doShortcode($attributes) {
        if (!$this->isSingleShortcode()) {
            return '';
        }
        $this->countShortcode++;
        if ( strtolower($attributes['height'])=='fullscreen') {
            return '<div style="position: fixed;left: 0px;width: 100vw;height: 100vh;top: 0px;z-index: 999999;"><script src="'. $this->getSrcScript($attributes['width'], '100vh') .'" ></script></div>';
        } else {
            return '<script src="'. $this->getSrcScript($attributes['width'], $attributes['height']) .'" ></script>';
        }

    }
    //-------------------------------------------------------------------------------------------------------------------------------
    /*
     * WP admin panel
     */
    function getIconMenu() {
        return plugin_dir_url(__FILE__) . 'images/icon-menu.png';
    }

    function getPageAdmin() {
        $email = wp_get_current_user()->user_email;
        $url = self::$loginURL."?email=$email";
        $src = "<iframe id='html5chat-iframe' src='$url' frameborder='0' style='height: calc(100vh - 100px);'></iframe>";
        echo $src;
    }

    function getPageShortcode() {
        $url = get_admin_url(null, 'admin.php?page='.$this->adminPanel['menu_slug']);
        ob_start();?>
        <div id="html5chat-help">
            <h1>Insert HTML5 chat</h1>
            <p>
                To add the chat to your post or page, please <b>paste:</b>
            </p>
            <div>
                <input type="text" value="[HTML5CHAT width=100% height=640px]" style="width: 50%;" >
                <button id="copyClipBoardHtml5chat1" onclick="copyToClipBoardHtml5(event)" >copy</button>
            </div>

            <p>(Specify yhe width and height you want)</p>
            <p>
                If you want the chat to be fullScreen, use height=fullscreen ex:
            <div>
                <input type="text" value="[HTML5CHAT width=100% height=fullscreen]"  style="width: 50%;">
                <button id="copyClipBoardHtml5chat1" onclick="copyToClipBoardHtml5(event)" >copy</button>
            </div>
            </p>
            <p>You can also <a href="<?= $url; ?>">configure the FULL chat</a> from here</p>
            <p>
                <i>(You account password has been emailed you to <b><?=wp_get_current_user()->user_email;?></b>)</i>
            </p>
        </div>
        <script>
            function copyToClipBoardHtml5(e) {
                jQuery(e.currentTarget).parent().find("input[type='text']").select()
                document.execCommand('copy');
            }
        </script>

        <?php $src = ob_get_clean();
        echo $src;
    }

    function setMenu() {
        $parent = array(
            'page_title'    => 'HTML5 chat setting',
            'menu_title'    => 'HTML5-CHAT',
            'capability'    => 'manage_options',
            'menu_slug'     => 'html5-chat',
            'function'      => array($this, 'getPageAdmin'),
            'icon_url'      => $this->getIconMenu()
        );

        $adminPanelTitle = 'Configure chat';
        $this->adminPanel = array(
            'parent_slug'   => $parent['menu_slug'],
            'page_title'    => $adminPanelTitle,
            'menu_title'    => $adminPanelTitle,
            'capability'    => $parent['capability'],
            'menu_slug'     => $parent['menu_slug'],
            'function'      => array($this, 'getPageAdmin')
        );

        $codeTitle = 'Insert chat';
        $this->code = array(
            'parent_slug'   => $parent['menu_slug'],
            'page_title'    => $codeTitle,
            'menu_title'    => $codeTitle,
            'capability'    => $parent['capability'],
            'menu_slug'     => $parent['menu_slug'].'code',
            'function'      => array($this, 'getPageShortcode')
        );

        add_menu_page($parent['page_title'], $parent['menu_title'], $parent['capability'], $parent['menu_slug'], $parent['function'], $parent['icon_url']);

        $pageMain = add_submenu_page( $this->adminPanel['parent_slug'], $this->adminPanel['page_title'],
            $this->adminPanel['menu_title'], $this->adminPanel['capability'], $this->adminPanel['menu_slug'], $this->adminPanel['function']);

        $pageCode = add_submenu_page( $this->code['parent_slug'], $this->code['page_title'], $this->code['menu_title'],
            $this->code['capability'], $this->code['menu_slug'], $this->code['function']);

        add_action('admin_print_styles-' . $pageMain, array($this,'styleAdmin'));
        add_action('admin_print_styles-' . $pageCode, array($this, 'styleAdmin'));
    }
    //-------------------------------------------------------------------------------------------------------------------------------
    /*
     * register button in editor
     */
    function enqueuePluginScripts($plugin_array)
    {
        if ($this->isSingleShortcode()) {
            $plugin_array['button_html5_chat'] = $this->getButtonScript();
        }

        return $plugin_array;
    }

    function registerButtonEditor($buttons)
    {
        if ($this->isSingleShortcode()) {
            array_push($buttons, 'btn_html5_chat');
        }

        return $buttons;
    }

    function getButtonScript() {
        $src = plugin_dir_url(__FILE__) . 'js/main.js';

        return  $src;
    }
    // buddyPress
    function bbGetGenderUser() {
        $gender = 'male';
        global $bp;
        $possibleSexes = ['gender', 'sex', 'sexe', 'sesso', 'genre', 'genero', 'género', 'sexo', 'seks', 'секс', 'geslacht', 'kind', 'geschlecht', 'płeć', 'sexuellt', 'kön'];
        foreach($possibleSexes as $possibleSex) {
            $args = array( 'field' => $possibleSex, 'user_id' => bp_loggedin_user_id() );
            $gender = bp_get_profile_field_data($args);
            if ($gender) {
                break;
            }
        }
        return $gender;
    }
    // buddyPress
    function bbGetTypeUser() {
        $role = bp_get_member_type(bp_loggedin_user_id(), true);
        return $role;
    }

}

register_activation_hook( __FILE__, array( 'HtmlChat', 'pluginActivated' ) );
add_action( 'admin_notices', array( 'HtmlChat', 'display_notice' ) );
$htmlChat = new HtmlChat();