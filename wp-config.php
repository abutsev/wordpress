<?php

/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define( 'DB_NAME', 'wordpress' );
/** Имя пользователя MySQL */
define( 'DB_USER', 'wordpress' );
/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', '1Qazzaq12wsx' );
/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );
/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );
/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Tn#D/Mz*(CT.WLXkK1Wl~Eq}f7Ii*TNY|Ko#Z]4Fft 5?t0@w[^ZwOT@Lf_5-%Lo' );
define( 'SECURE_AUTH_KEY',  '8~su/N/p+Q~?}rWlw)t8ZcxKgr^{}]`R=c+?![q93sgPhE28$23j4{$Cv`HOUJFL' );
define( 'LOGGED_IN_KEY',    '0wbe$;VN;[jN6kK02!/wZmt#-<#eH]cbTH.W{cvg5pRPtpd[I}p0t)e*6z85loQ~' );
define( 'NONCE_KEY',        '=h]ooAcxp Y9(0,-v;3BjlJ97;`O/aYa*>#52u2c8(J~MQ[? BS2-72gfBsd8sM3' );
define( 'AUTH_SALT',        ' fx7HKVdSRw@+/rqmeX0p0Gdw|,OU}lTz:>764[|~ppje}{6tOto0!HfWxgAi)6m' );
define( 'SECURE_AUTH_SALT', '%[R_?hS*-(fZn_a^rG{CNI{FH4Y(AI6/$XRbk)c|*F4E-!g(P/K)lTSq@mwKD;r{' );
define( 'LOGGED_IN_SALT',   ']JsVx#&H86=7x4,D7i0N6G[Uf^[< pP?-E)yY9c1~!~LX?*BqM Y|FsgC%}Ef+.N' );
define( 'NONCE_SALT',       'DmtZEI(5dm sa 8MNxT14t0TT/@TF1&VBLN[`#*Nsj4KOBry^f^NmVS=.Njc3KGt' );
define('ALLOW_UNFILTERED_UPLOADS',true);
/**#@-*/
/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';
/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );
/* Это всё, дальше не редактируем. Успехов! */
/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}
/** Инициализирует переменные WordPress и подключает файлы. */
require_once( ABSPATH . 'wp-settings.php' );
