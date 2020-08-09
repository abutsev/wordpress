<?php
/**
 * Page builder support
 *
 * @package Bistro
 */

/* Extra option for the Page Builder */
function bistro_row_styles($fields) {

	$fields['column_padding'] = array(
		'name'        => __('Columns padding', 'bistro'),
		'type'        => 'checkbox',
		'description' => __('Remove padding between columns for this row?', 'bistro'),
		'priority'    => 10,
	);
	return $fields;
}
add_filter('siteorigin_panels_row_style_fields', 'bistro_row_styles');

/* Filter for the styles */
function bistro_row_styles_output($attr, $style) {
	$attr['style'] = '';

    if( !empty( $style['column_padding'] ) ) {
       $attr['class'][] = 'no-col-padding';
    }

	if(empty($attr['style'])) unset($attr['style']);
	return $attr;
}
add_filter('siteorigin_panels_row_style_attributes', 'bistro_row_styles_output', 10, 2);