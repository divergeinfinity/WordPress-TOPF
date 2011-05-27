<?php
/**
 *	This is the 'Theme Options Panel Framework' or TOPF.  It provides an Admin panel interface in WordPress
 *	for administering a Themes Options.
 *
 *	This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 *	General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 *	that you can use any other version of the GPL.
 *
 *	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 *	even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *	You should have received a copy of the GNU General Public License along with this program; if not,
 *	write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 *
 *	@package TOPF
 *	@version 0.9
 *	@author Jeff Parsons <jeffrey.allen.parsons@gmail.com>
 *	@copyright Copyright (c) 2011, Jeff Parsons
 *	@link http://diverge.blogdns.com
 *	@license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/*---------------------------------------------------------------------------*/
//
//	TOPF - Setup and Display Functions
//
//	- Define Default Theme Options
//	- Register/Initialize Theme Options
//	- Define Admin Settings Page
//	- Handle and validate Theme options via the WordPress Settings API
//
/*---------------------------------------------------------------------------*/

//	http://ottopress.com/2009/wordpress-settings-api-tutorial/
//	http://www.chipbennett.net/2011/02/17/incorporating-the-settings-api-in-wordpress-themes/
//	http://wptheming.com/2010/11/thematic-options-panel-v2/

/*---------------------------------------------------------------------------*/
//	TOPF - Initial Setup Requirements.  Both Blog and Admin use.
//	We run these on the 'after_setup_theme' action hook,
//	high up on the action hook food chain so that they are first
/*---------------------------------------------------------------------------*/
function topf_options_setup()
{
	// load default options and setup the defaults array '$topf_theme_options'
	require_once( get_stylesheet_directory() . '/options/theme-default-options.php' );
	// activate our Parser '$our_parser' to scan the defaults and ready the
	// main options array '$topf_options'
	require_once( get_stylesheet_directory() . '/options/Options-Parser.php' );
	// unused header and background panels - undeveloped
//	require_once( get_stylesheet_directory() . '/options/options-setup.php' );
}
add_action( 'after_setup_theme', 'topf_options_setup', 9 );


/*---------------------------------------------------------------------------*/
//	Once WP is loaded, we want our options loaded, or created from defaults if
//	this is our first run and the options don't exist.
//	TOPF_OPTIONS should be defined with the name of the WP table entry for our
//	options before loading the options.php file.  All options are enclosed in an
//	array and saved in TOPF_OPTIONS.
//	For normal Blog or Admin use they are run on the 'wp_loaded' action hook
/*---------------------------------------------------------------------------*/
function topf_options_loaded()
{
	global $topf_options;			// the normal options once created
	global $topf_theme_options;		// theme default options
	global $our_parser;				// object to parse default options

	$our_parser = new Options_Parser( $topf_theme_options, TOPF_OPTIONS );

	$topf_options = get_option( TOPF_OPTIONS );

	if ( false === $topf_options )
	{
		$topf_options = $our_parser->defaults;
	}
	update_option( TOPF_OPTIONS, $topf_options );

	// Update New Options for later versions
// 	if ( '1.0' > $topf_options['theme_version'] )
// 	{
// 		$default_options = topf_get_default_options();
// 		$topf_options['theme_version'] = '1.0';
// 		update_option( TOPF_OPTIONS, $topf_options );
// 	}

}
add_action( 'wp_loaded', 'topf_options_loaded' );


/*---------------------------------------------------------------------------*/
//	Add Options Menu Item to the WordPress Admin Bar
/*---------------------------------------------------------------------------*/
function topf_adminbar()
{
	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array(
		'parent'	=> 'appearance',
		'id'		=> 'topf_theme_options',
		'title'		=> __( 'Options' ),
		'href'		=> admin_url( 'themes.php?page=options-panel' )
	));
}
add_action( 'wp_before_admin_bar_render', 'topf_adminbar' );


/*---------------------------------------------------------------------------*/
//	Setup the Theme Options Menu in the Admin -> Appearance Menu
//	Register our options with WP and load all styles and scripts
//	Runs on the 'admin_menu' action hook for Admin use only
/*---------------------------------------------------------------------------*/
function topf_menu_options()
{
//	if ( isset( $_GET['page'] ) && $_GET['page'] == 'options-panel' )
//	{
		$page = add_theme_page( 'Options', 'Options', 'edit_theme_options', 'options-panel', 'topf_admin_options_markup' );
		add_action( 'admin_init', 'topf_register_options' );
		add_action( "admin_print_styles-$page", 'topf_enqueue_admin_style' );
		add_action( "admin_print_scripts-$page", 'topf_enqueue_admin_scripts' );
		add_action( "admin_head-$page", 'topf_enqueue_other_javascript' );
//	}
}
add_action( 'admin_menu', 'topf_menu_options' );


/*---------------------------------------------------------------------------*/
//	Everything from here onwards is called at the appropriate time as set
//	in the above topf_menu_options() function
/*---------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------*/
//	Register our settings with WordPress - admin_init
//	Provide data validation for User Input
//	Codex Reference: http://codex.wordpress.org/Settings_API
/*---------------------------------------------------------------------------*/
function topf_register_options()
{
	require_once( get_stylesheet_directory() . '/options/options-register.php' );
}


/*---------------------------------------------------------------------------*/
//	Enqueue Custom Admin Page Stylesheets on our Options Panel only
//	admin_print_styles - appearance_page_options-panel
/*---------------------------------------------------------------------------*/
function topf_enqueue_admin_style()
{
	// define admin stylesheet
	$admin_handle = 'topf_admin_stylesheet';
	$admin_stylesheet = get_stylesheet_directory_uri() . '/options/css/options-admin.css';
	wp_enqueue_style( $admin_handle, $admin_stylesheet, '', false );
	wp_enqueue_style( 'thickbox' );
	wp_enqueue_style( 'jpicker' , get_stylesheet_directory_uri() . '/options/js/jpicker-1.1.6/css/jPicker-1.1.6.min.css', false, '1.1.6' );
//	wp_enqueue_style( 'jpickercss' , get_stylesheet_directory_uri() . '/options/js/jpicker-1.1.6/jPicker.css', array( 'jpicker' ), '1.1.6' );
}


/*---------------------------------------------------------------------------*/
//	Enqueue Custom Admin Page Javascript on our Options Panel only
//	admin_print_scripts - appearance_page_options-panel
/*---------------------------------------------------------------------------*/
function topf_enqueue_admin_scripts()
{
    wp_register_script( 'jquery-tools', 'http://cdn.jquerytools.org/1.2.5/all/jquery.tools.min.js', array( 'jquery' ), '1.2.5' );
    wp_enqueue_script( 'jquery-tools' );
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_register_script( 'jpicker', get_stylesheet_directory_uri() . '/options/js/jpicker-1.1.6/jpicker-1.1.6.min.js', array( 'jquery' ), '1.1.6' );
	wp_enqueue_script( 'jpicker' );
}


/*---------------------------------------------------------------------------*/
//	Markup for the Theme Options Panel Framework
/*---------------------------------------------------------------------------*/
function topf_admin_options_markup()
{
	global $topf_options;
	global $our_parser;
?>
	<div id="topf-container">

		<noscript>
			<div id="topf-js-warning" class="error">Warning- This options panel will not work properly without javascript!</div>
		</noscript>

		<?php
		if ( isset( $_GET['settings-updated'] ) )
		{
			if ( $topf_options['validation-error'] )
			{
				echo '<div class="error"><p>'.$topf_options['message'].'</p></div>';
				$topf_options['validation-error'] = false;
				$topf_options['message'] = "";
			}
			else
				echo "<div class='updated'><p>".$topf_options['message']."</p></div>";
		}
		?>

		<div id="topf-header">
			<div class="logo">
				<?php $themedata = get_theme_data( get_stylesheet_directory() . '/style.css' ); ?>
				<?php screen_icon(); ?><h2><?php echo $themedata['Name'] . ' Theme Options'; ?></h2>
			</div>
			<div style="float:right;">
				<form method="post" action="https://www.paypal.com/cgi-bin/webscr" target="paypal">
					<input type="hidden" name="cmd" value="_xclick">
					<input type="hidden" name="business" value="jeffrey.allen.parsons@gmail.com">
					<input type="hidden" name="item_name" value="Theme Options Panel Framework">
					<input type="hidden" name="bn"  value="ButtonFactory.PayPal.001">
					<input type="image" name="add" src="http://www.powersellersunite.com/buttonfactory/x-click-but04.gif">
				</form>
			</div>
			<div class="clear"></div>
		</div>

		<ul class="topf-tabs">
			<?php echo $our_parser->menus_li; ?>
		</ul>
		<div class="topf-panes">
			<form action="options.php" method="post">
				<?php
				settings_fields( TOPF_OPTIONS );
				echo '<div id="topf-content">';
				do_settings_sections( 'topf_sections' );
				echo '</div>' . "\n";
				$tabslug = $our_parser->get_current_tab_slug();
				$tabname = $our_parser->get_current_tab_name();
				?>
				<div class="topf-submit-buttons">
					<input id ="topf_save" name="<?php echo TOPF_OPTIONS ?>[submit-<?php echo $tabslug; ?>]" type="submit" class="button-primary" value="<?php esc_attr_e( "Save $tabname Settings", 'topf_international' ); ?>" />
					<input id ="topf_reset" name="<?php echo TOPF_OPTIONS ?>[reset-<?php echo $tabslug; ?>]" type="submit" class="button-secondary" value="<?php esc_attr_e( "Reset $tabname Defaults", 'topf_international' ); ?>" />
				</div>
			</form>
		</div>
	</div>
<?php
}


/*---------------------------------------------------------------------------*/
//  Add our own Javascript code to the Admin Head on our Options Panel
//	load at 'admin_head' action hook for Admin use only
/*---------------------------------------------------------------------------*/
function topf_enqueue_other_javascript()
{
	global $topf_options;
?>
	<script type="text/javascript" language="javascript">
	/*<![CDATA[*/


	jQuery(document).ready( function()
	{

		// Fade out the status message
		jQuery('.updated').delay(2000).fadeOut(1000);


		// confirm reset of Options
		jQuery('#topf_reset').click( function()
		{
			var answer = confirm( "<?php _e( 'Click OK to reset. All settings will be lost!' ); ?>")
			if ( answer ) { return true; } else { return false; }
		});


		// when the 'images' option type is in use as radio buttons, switch selected item
		jQuery('.topf-radio-img-img').click( function()
		{
			jQuery(this).parent().parent().find('.topf-radio-img-img').removeClass('topf-radio-img-selected');
			jQuery(this).addClass('topf-radio-img-selected');
		});


		// WordPress Image Uploader
		var uploadButtonObject;
		jQuery('.topf-upload-button').click( function()
		{
			uploadButtonObject = jQuery(this);
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			return false;
		});
		window.send_to_editor = function( html )
		{
			var imgurl = jQuery('img',html).attr('src');
			uploadButtonObject.prev('input').val(imgurl);
			tb_remove();
		}


		// jQuery Tools range tool
		jQuery(":range").rangeinput();


		// http://www.digitalmagicpro.com/jPicker/   (for settings)
		var jPickerImagesPath = "<?php echo get_stylesheet_directory_uri() ?>/options/js/jPicker-1.1.6/images/";
 		jQuery('.topf-color').jPicker(
 		{
 			window:
 			{
 				// set to true to make an expandable picker (small icon with popup)
 				expandable: true,
 				position:
 				{
 					// acceptable values "left", "center", "right", "screenCenter", or relative px value
 					x: 'screenCenter',
 					// acceptable values "top", "bottom", "center", or relative px value
 					y: 'center',
 				}
 			},
 			images:
 			{
 				clientPath: jPickerImagesPath // Path to image files
     		}
		});

	});


	/*]]>*/
	</script>
<?php
}

?>