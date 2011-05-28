<?php

/*---------------------------------------------------------------------------*/
//	Theme Options Panel Framework - Register Settings
/*---------------------------------------------------------------------------*/

global $pagenow;
global $our_parser;

//	set up WordPress with our options and our data validation callback function
register_setting( TOPF_OPTIONS, TOPF_OPTIONS, 'topf_options_validate' );

/*---------------------------------------------------------------------------*/
//	Theme Options Panel Framework - individual options for any 'Section' Tab
/*---------------------------------------------------------------------------*/
if ( 'themes.php' == $pagenow && isset( $_GET['page'] ) && 'options-panel' == $_GET['page'] )
{
	$tabslug = $our_parser->get_current_tab_slug();
	$tabname = $our_parser->get_current_tab_name();

	add_settings_section( 'topf_section_'.$tabslug, $tabname.' Options', 'dummy_section_output', 'topf_sections' );

	$items = $our_parser->sections[$tabname];
	foreach ( $items as $item )
	{
		if ( isset( $item['id'] ) && isset( $item['name'] ) )
			add_settings_field( $item['id'], $item['name'], 'topf_display_item', 'topf_sections', 'topf_section_'.$tabslug, $item );
	}
}

//	This will output markup for a Section Heading
function dummy_section_output()
{
/*?>
	<p><?php _e( "Screw WP, I/We don't want to output anything here.", 'topf_international' ); ?></p>
<?php*/
}

//	output markup for each option item
function topf_display_item( $item )
{

	global $our_parser;
	echo $our_parser->format_item_for_display( $item );

}


/*---------------------------------------------------------------------------*/
//	Validate/Whitelist User-Input Data Before Updating Theme Options
//	This is the callback function that WordPress uses to validate user input
//	Codex Reference: http://codex.wordpress.org/Data_Validation
/*---------------------------------------------------------------------------*/
function topf_options_validate( $input )
{
	global $our_parser;

	$current_options = get_option( TOPF_OPTIONS );
	$current_options['validation-error'] = true; // if no errors in validation, we will reset this to false
	$current_options['message'] = "There was an error validating the data. No update occured!";
	$valid_input = $current_options;	// we start with the current options, plus the error flag and message
	$operation = "";

	// Determine which form action was submitted
	// When WP calls this function, $_GET is not set to access the Tab name so ...
	// We only get here from a 'submit-' or a 'reset-' with the tab slug added on
	// The last element in the $input array is the operation submission
	end( $input );
	$operation = key( $input );
	list( $request, $tab ) = explode( "-", $operation );
	$tab = str_replace( "-", " ", ucwords( $tab ) );

	switch ( $operation )
	{
		case 'submit-general':
			$valid_input['custom_favicon'] = esc_url_raw( $input['custom_favicon'] );
			$valid_input['disabledoctitle'] = ( isset( $input['disabledoctitle'] ) ? '1' : '0' );
			$valid_input['google_analytics'] = trim( $input['google_analytics'] );
			$valid_input['footer_text'] = trim( $input['footer_text'] );
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "'".$tab."' section Theme options updated successfully.";
			break;

		case 'reset-general':
			$valid_input['custom_favicon'] = $our_parser->defaults['custom_favicon'];
			$valid_input['disabledoctitle'] = $our_parser->defaults['disabledoctitle'];
			$valid_input['google_analytics'] = $our_parser->defaults['google_analytics'];
			$valid_input['footer_text'] = $our_parser->defaults['footer_text'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "Theme options for section '". $tab ."' were successfully reset.";
			break;

		case 'submit-header':
			$valid_input['usedefaultheader'] = ( isset( $input['usedefaultheader'] ) ? '1' : '0' );
			$valid_input['headerbackground'] = $input['headerbackground'];
			$valid_input['displayblogtitle'] = ( isset( $input['displayblogtitle'] ) ? '1' : '0' );
			$valid_input['displayblogdesc'] = ( isset( $input['displayblogdesc'] ) ? '1' : '0' );
			$valid_input['usedefaultbranding'] = ( isset( $input['usedefaultbranding'] ) ? '1' : '0' );
			$valid_input['brandingbackground'] = esc_url_raw( $input['brandingbackground'] );
			$valid_input['logo'] = esc_url_raw( $input['logo'] );
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "'".$tab."' section Theme options updated successfully.";
			break;

		case 'reset-header':
			$valid_input['usedefaultheader'] = $our_parser->defaults['usedefaultheader'];
			$valid_input['headerbackground'] = $our_parser->defaults['headerbackground'];
			$valid_input['displayblogtitle'] = $our_parser->defaults['displayblogtitle'];
			$valid_input['displayblogdesc'] = $our_parser->defaults['displayblogdesc'];
			$valid_input['usedefaultbranding'] = $our_parser->defaults['usedefaultbranding'];
			$valid_input['brandingbackground'] = $our_parser->defaults['brandingbackground'];
			$valid_input['logo'] = $our_parser->defaults['logo'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "Theme options for section '". $tab ."' were successfully reset.";
			break;
		case 'submit-content':
			$valid_input['layout'] = $input['layout'];
			$valid_input['usedefaultbody'] = ( isset( $input['usedefaultbody'] ) ? '1' : '0' );
			$valid_input['bodybackground'] = $input['bodybackground'];
			$valid_input['exclude_cats_home'] = $input['exclude_cats_home'];
			$valid_input['displaycalendar'] = ( isset( $input['displaycalendar'] ) ? '1' : '0' );
			$valid_input['calendar_opacity'] = $input['calendar_opacity'];
			$valid_input['displaymeta'] = ( isset( $input['displaymeta'] ) ? '1' : '0' );
			$valid_input['displaydivider'] = ( isset( $input['displaydivider'] ) ? '1' : '0' );
			$valid_input['displayexcerpts'] = ( isset( $input['displayexcerpts'] ) ? '1' : '0' );
			$valid_input['hilitefirst'] = ( isset( $input['hilitefirst'] ) ? '1' : '0' );
			$valid_input['firstpost_border'] = $input['firstpost_border'];
			$valid_input['displayfullfirst'] = ( isset( $input['displayfullfirst'] ) ? '1' : '0' );
			$valid_input['home_aside_insert'] = $input['home_aside_insert'];
			$valid_input['usedefaultfooter'] = ( isset( $input['usedefaultfooter'] ) ? '1' : '0' );
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "'".$tab."' section Theme options updated successfully.";
			break;

		case 'reset-content':
			$valid_input['layout'] = $our_parser->defaults['layout'];
			$valid_input['usedefaultbody'] = $our_parser->defaults['usedefaultbody'];
			$valid_input['bodybackground'] = $our_parser->defaults['bodybackground'];
			$valid_input['exclude_cats_home'] = $our_parser->defaults['exclude_cats_home'];
			$valid_input['displaycalendar'] = $our_parser->defaults['displaycalendar'];
			$valid_input['calendar_opacity'] = $our_parser->defaults['calendar_opacity'];
			$valid_input['displaymeta'] = $our_parser->defaults['displaymeta'];
			$valid_input['displaydivider'] = $our_parser->defaults['displaydivider'];
			$valid_input['displayexcerpts'] = $our_parser->defaults['displayexcerpts'];
			$valid_input['hilitefirst'] = $our_parser->defaults['hilitefirst'];
			$valid_input['firstpost_border'] = $our_parser->defaults['firstpost_border'];
			$valid_input['displayfullfirst'] = $our_parser->defaults['displayfullfirst'];
			$valid_input['home_aside_insert'] = $our_parser->defaults['home_aside_insert'];
			$valid_input['usedefaultfooter'] = $our_parser->defaults['usedefaultfooter'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "Theme options for section '". $tab ."' were successfully reset.";
			break;

		case 'submit-fonts':
			$valid_input['ignorefonts'] = ( isset( $input['ignorefonts'] ) ? '1' : '0' );
			$valid_input['body_font'] = $input['body_font'];
 			$valid_input['blog_title_font'] = $input['blog_title_font'];
 			$valid_input['blog_description_font'] = $input['blog_description_font'];
 			$valid_input['post_title_font'] = $input['post_title_font'];
			$valid_input['post_font'] = $input['post_font'];
			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "'".$tab."' section Theme options updated successfully.";
			break;

		case 'reset-fonts':
			$valid_input['ignorefonts'] = $our_parser->defaults['ignorefonts'];
			$valid_input['body_font'] = $our_parser->defaults['body_font'];
			$valid_input['blog_title_font'] = $our_parser->defaults['blog_title_font'];
			$valid_input['blog_description_font'] = $our_parser->defaults['blog_description_font'];
			$valid_input['post_title_font'] = $our_parser->defaults['post_title_font'];
			$valid_input['post_font'] = $our_parser->defaults['post_font'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "Theme options for section '". $tab ."' were successfully reset.";
			break;

		case 'submit-styling':
			$valid_input['alt_stylesheet'] = $input['alt_stylesheet'];
			$valid_input['header_background_color'] = $input['header_background_color'];
			$valid_input['body_background'] = $input['body_background'];
			$valid_input['footer_background'] = $input['footer_background'];
			$valid_input['primarycolor'] = $input['primarycolor'];
			$valid_input['secondcolor'] = $input['secondcolor'];
			$valid_input['thirdcolor'] = $input['thirdcolor'];
			$valid_input['custom_css'] = $input['custom_css'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "'".$tab."' section Theme options updated successfully.";
			break;

		case 'reset-styling':
			$valid_input['alt_stylesheet'] = $our_parser->defaults['alt_stylesheet'];
			$valid_input['header_background_color'] = $our_parser->defaults['header_background_color'];
			$valid_input['body_background'] = $our_parser->defaults['body_background'];
			$valid_input['footer_background'] = $our_parser->defaults['footer_background'];
			$valid_input['primarycolor'] = $our_parser->defaults['primarycolor'];
			$valid_input['secondcolor'] = $our_parser->defaults['secondcolor'];
			$valid_input['thirdcolor'] = $our_parser->defaults['thirdcolor'];
			$valid_input['custom_css'] = $our_parser->defaults['custom_css'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "Theme options for section '". $tab ."' were successfully reset.";
			break;

		case 'submit-examples':

 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "'".$tab."' section Theme options updated successfully.";
			break;

		case 'reset-examples':
			$valid_input['example_typography'] = $our_parser->defaults['example_typography'];
			$valid_input['example_border'] = $our_parser->defaults['example_border'];
			$valid_input['example_colorpicker'] = $our_parser->defaults['example_colorpicker'];
			$valid_input['example_colorpicker_2'] = $our_parser->defaults['example_colorpicker_2'];
			$valid_input['example_uploader'] = $our_parser->defaults['example_uploader'];
			$valid_input['example_uploader2'] = $our_parser->defaults['example_uploader2'];
			$valid_input['example_text'] = $our_parser->defaults['example_text'];
			$valid_input['example_checkbox_false'] = $our_parser->defaults['example_checkbox_false'];
			$valid_input['example_checkbox_true'] = $our_parser->defaults['example_checkbox_true'];
			$valid_input['example_select'] = $our_parser->defaults['example_select'];
			$valid_input['example_select_wide'] = $our_parser->defaults['example_select_wide'];
			$valid_input['example_radio'] = $our_parser->defaults['example_radio'];
			$valid_input['example_images'] = $our_parser->defaults['example_images'];
			$valid_input['example_textarea'] = $our_parser->defaults['example_textarea'];
			$valid_input['example_multicheck'] = $our_parser->defaults['example_multicheck'];
			$valid_input['example_category'] = $our_parser->defaults['example_category'];
 			$valid_input['validation-error'] = false; // no errors, we are clean
 			$valid_input['message'] = "Theme options for section '". $tab ."' were successfully reset.";
			break;
	}

	return $valid_input;

}

?>