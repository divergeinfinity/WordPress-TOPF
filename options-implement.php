<?php
/*---------------------------------------------------------------------------*/
//	This file is Divergence Theme secific and implements the options setup in
//	The Theme Options Panel Framework
//	The bulk of the file formulates final CSS that is added on after all other CSS
/*---------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------*/
//	Very little of this file will be applicable to your Theme.  It serves as an
//	example only of how to implement options from TOPF, as most of the file is for
//	the Divergence Theme and the Thematic Theme Framework.  The bulk of this file
//	outputs final CSS for Divergence.
/*---------------------------------------------------------------------------*/

function topf_wp_head()
{
	global $topf_options;

	// Layouts
	$layout = $topf_options['layout'];
	if ( $layout == '' )
	{
		$layout = '2c-r-fixed.css';
	}
	wp_register_style( 'layout', get_stylesheet_directory_uri() . "/layouts/" . $layout );
	wp_enqueue_style( 'layout' );


	// Kills sidebar if single column layout is selected
	if ( $layout == '1col-fixed.css' )
	{
		add_action( 'thematic_sidebar', 'kill_sidebar' );
	}


	// Alt Styles
	$alt_style = $topf_options['alt_stylesheet'];
	if ( $alt_style == '' )
	{
		$alt_style = 'divergence.css';
	}
	wp_register_style( 'alt_style', get_stylesheet_directory_uri() . "/styles/" . $alt_style );
	wp_enqueue_style( 'alt_style' );


	// Special Case Typefaces
	// Check for a default font or a Google font
	// load Google Font Stylesheets if any
	$special_case_fonts = array(
		'body_font', 'blog_title_font', 'blog_description_font', 'post_title_font', 'post_font' );
	foreach ( $special_case_fonts as $font )
	{
		$typography = $topf_options[$font];
		$typeface = topf_default_font_stack( $typography['face'] );
		if ( $typeface == "" )
		{
			// it's not in our default font stacks, could be a Google font
			if ( $typography['face'] <> "" )
			{
				// should have some valid checks, but ...
				// we should also stack multiple fonts with the pipe '|', but ...
				wp_register_style( 'font-'.$typography['face'], "http://fonts.googleapis.com/css?family=".urlencode( $typography['face'] ) );
				wp_enqueue_style( 'font-'.$typography['face'] );
			}
		}
	}

}
add_action( 'wp_print_styles', 'topf_wp_head' );


/*---------------------------------------------------------------------------*/
//  Output CSS from standarized options
//	These are functions specific to the Theme for implementing the Options
//	Other options may need to be implemented elsewhere in templates as you would normally
/*---------------------------------------------------------------------------*/
function topf_head_css()
{
	global $topf_options;

	$output = '';

// Background Colors (may be overridden later on)
	$header_color = $topf_options['header_background_color'];
	if ( $header_color ) $output .= "#header {background-color: #" . $header_color .";}\n";
	$body_color = $topf_options['body_background'];
	if ( $body_color ) $output .= "body {background-color: #" . $body_color .";}\n";
	$footer_color = $topf_options['footer_background'];
	if ( $footer_color ) $output .= "#footer {background-color: #" . $footer_color .";}\n";


// Header Backgrounds
	if ( $topf_options['usedefaultheader'] )
		$output .= "#header {background-image: url(".get_stylesheet_directory_uri()."/images/header_bg.gif);}\n";
	else if ( $topf_options['headerbackground']['image'] <> "" )
	{
		$output .= "#header {background-image: url(".$topf_options['headerbackground']['image'].");}\n";
		$output .= "#header {background-repeat: ".$topf_options['headerbackground']['repeat'].";}\n";
		$output .= "#header {background-position: ".$topf_options['headerbackground']['position'].";}\n";
		$output .= "#header {background-attachment: ".$topf_options['headerbackground']['attachment'].";}\n";
		$output .= "#header {background-color: #".$topf_options['headerbackground']['color'].";}\n";
	}
	else
		$output .= "#header {background-image: none;}\n";


// Branding Backgrounds
	$brandingbg = $topf_options['brandingbackground'];
	if ( $topf_options['usedefaultbranding'] )
	{
		$output .= "#branding {background-image: url(".get_stylesheet_directory_uri()."/images/diverge_branding.jpg);}\n";
		$output .= "#branding {background-repeat: no-repeat;}\n";
	}
	else if ( $brandingbg <> "" )
	{	// TimThumb Zoom and Crop Settings: zc=0 Resize to Fit specified dimensions (no cropping), zc=1 rop and resize to best fit the dimensions (default behaviour), zc=2 Resize proportionally to fit entire image into specified dimensions, and add borders if required, zc=3 Resize proportionally adjusting size of scaled image so there are no borders gaps
		$path = get_stylesheet_directory_uri().'/library/timthumb.php?w=960&h=150&zc=0&q=90&s=1&src='.$brandingbg;
		$output .= "#branding {background-image: url(".$path.");}\n";
		$output .= "#branding {background-repeat: no-repeat;}\n";
	}
	else
		$output .= "#branding {background-image: none;}\n";


// Body Backgrounds
	if ( $topf_options['usedefaultbody'] )
	{
		$output .= "body {background-image: url(".get_stylesheet_directory_uri()."/images/body_gradient.png);}\n";
		$output .= "body {background-repeat: repeat-x;}\n";
	}
	else if ( $topf_options['bodybackground']['image'] <> "" )
	{
		$output .= "body {background-image: url(".$topf_options['bodybackground']['image'].");}\n";
		$output .= "body {background-repeat: ".$topf_options['bodybackground']['repeat'].";}\n";
		$output .= "body {background-position: ".$topf_options['bodybackground']['position'].";}\n";
		$output .= "body {background-attachment: ".$topf_options['bodybackground']['attachment'].";}\n";
		$output .= "body {background-color: #".$topf_options['bodybackground']['color'].";}\n";
	}
	else
		$output .= "body {background-image: none;}\n";


// Footer Backgrounds
	if ( $topf_options['usedefaultfooter'] )
	{
		$output .= "#footer {background-image: url(".get_stylesheet_directory_uri()."/images/footer_gradient.png);}\n";
		$output .= "#footer {background-repeat: repeat-x;}\n";
		$output .= "#footer {background-position: 0 -75px;}\n";
	}
	else
		$output .= "#footer {background-image: none;}\n";


// Blog titles and descriptions
// For Thematic, the blog title may be replaced with a logo which is added later in an action hook
	if ( ! $topf_options['displayblogtitle'] )
		$output .= "#blog-title {display: none;}\n";

	if ( ! $topf_options['displayblogdesc'] )
		$output .= "#blog-description {display: none;}\n";


// Format opacity for a sylized Calendar
	$opacity = $topf_options['calendar_opacity'];
	if ( $opacity <> "" )
	{
		$opacity = $opacity < "1" ? "1" : $opacity;
		$opacity = $opacity > "100" ? "100" : $opacity;
		$opacity = $opacity / 100;
		$output .= ".month, .day {opacity: " . $opacity . ";}\n";
	}


// First Post
	if ( $topf_options['hilitefirst'] )
	{
		$values = $topf_options['firstpost_border'];
		$output .= ".firstindexpost {border: ".$values['width']."px ".$values['style']." #".$values['color']."; padding: 36px 20px 0px 20px;}\n";
	}
	else
	{
		$output .= ".firstindexpost {background: none; border: none; padding: 0px 25px 0px 25px;}\n";
	}


// Colors (third)
	if ( $topf_options['thirdcolor'] == "" ) $third_color = '#444444';
	else $third_color = "#".$topf_options['thirdcolor'];
	$output .=
"#cancel-comment-reply a,
#comments-list .comment-meta a,
#comments-list .comment-meta,
#form-allowed-tags p,
#searchform input,
#siteinfo a,
#siteinfo,
.aside .current_page_item .page_item a,
.aside a,
.aside,
.comment-navigation a,
.comment-reply-link a,
.comment_license,
.entry-meta a,
.entry-meta,
.entry-title a,
.entry-title,
.entry-utility a,
.entry-utility,
.gallery-caption,
.navigation a,
.navigation,
.page-link a,
.page-title a,
.page-title,
.sf-menu a, .sf-menu a:visited,
.solo-subscribe-to-comments,
.subscribe-to-comments,
.wp-caption-text,
.wp-pagenavi a,
.wp-pagenavi a:link,
.wp-pagenavi a:visited,
.wp-pagenavi span,
.wp-pagenavi span.pages,
blockquote,
hr {color: " . $third_color . ";}\n";


// Colors (second)
	if ( $topf_options['secondcolor'] == "" ) $second_color = '#3E853E';
	else $second_color = "#".$topf_options['secondcolor'];
	$output .=
".sticky {border: 3px double " . $second_color . ";}
.page-title {color: " . $second_color . ";}
.month {color: " . ($body_color ? $body_color : "#fff") . "; border-left: 1px solid " . $second_color . "; border-top: 1px solid " . $second_color . "; border-right: 1px solid " . $second_color . "; background: " . $second_color . ";}
.day {border-left: 1px solid " . $second_color . "; border-bottom: 1px solid " . $second_color . "; border-right: 1px solid " . $second_color . "; color: " . $second_color . ";}
#blog-description,
#respond .required,
.aside .current_page_item a {color: " . $second_color . ";}
#searchform input,
#content .aside {border: 1px solid " . $second_color . ";}
.wp-pagenavi a:active {border: 1px solid " . $second_color . "; color: " . $second_color . ";}
.wp-pagenavi span.current {border: 1px solid " . $second_color . "; color: " . $second_color . ";}
#siteinfo {border-top: 1px solid " . $second_color . ";}
#access {border-bottom: 1px solid " . $second_color . ";}
.sf-menu {border-right: 1px solid " . $second_color . ";}
.sf-menu a {border-left: 1px solid " . $second_color . "; border-top: 1px solid " . $second_color . "; border-bottom: 1px solid " . $second_color . ";}
.sf-menu ul {border-right: 1px solid " . $second_color . "; border-bottom: 1px solid " . $second_color . ";}
#footer {border-top: 1px solid " . $second_color . ";}\n";


// Colors (primary)
	if ( $topf_options['primarycolor'] == "" ) $primary_color = '#A64E4E';
	else $primary_color = "#".$topf_options['primarycolor'];
	$output .=
".sf-menu li:hover,
.sf-menu li.sfHover,
.sf-menu a:focus,
.sf-menu a:hover,
.sf-menu a:active {color: " . $primary_color . "; background: #000;}
#blog-title a,
#blog-title a:hover,
.aside h3,
.aside h3 a,
.readmore a,
a:active,
a:hover,
#blog-title a:active,
.sf-menu ul a:hover,
.entry-title a:hover,
.page-title a:hover,
.page-title a:active,
.entry-title a:active,
.entry-meta a:active,
.entry-meta a:hover,
.entry-utility a:active,
.entry-utility a:hover,
.page-link a:active,
.page-link a:hover,
.navigation a:active,
.navigation a:hover,
#comments-list .comment-meta a:active,
#comments-list .comment-meta a:hover,
.comment-reply-link a:active,
.comment-reply-link a:hover,
.comment-navigation a:active,
.comment-navigation a:hover,
.aside .current_page_item .page_item a:hover,
.aside .current_page_item .page_item a:active,
.aside a:active,
.aside a:hover,
.sf-menu .current_page_item a,
.menu .current-menu-item a,
.sf-menu .current_page_ancestor a,
.menu .current-menu-ancestor a,
.sf-menu .current_page_parent a,
.menu .current-menu-parent a,
#siteinfo a:active,
#siteinfo a:hover {color: " . $primary_color . ";}
.wp-pagenavi a:hover {border: 1px solid " . $primary_color . "; color: " . $primary_color . ";}\n";


// Fonts
	if ( ! $topf_options['ignorefonts'] )
	{
		/**
		 *	For each of the following special font checks we search for a default font and if a value
		 *	is returned we use that Font Stack.  Otherwise we assume it's a Google font and use that.
		 *	If no value supplied, then a default serif font is used.
		 */
		if ( $typography = $topf_options['body_font'] )
		{
			$typeface = topf_default_font_stack( $typography['face'] );
			if ( $typeface == "" && $typography['face'] == "" ) $typeface = "serif";
			elseif ( $typeface == "" ) $typeface = $typography['face'];
			$output .= "body, input, textarea {\n     font-family: " . $typeface . "; \n";
			$output .= "     font-size: " . $typography['size'] . "px; \n";
			$output .= "     line-height: " . $typography['height'] . "px; \n";
			$output .= "     font-style: ".$typography['style'] . "; \n";
			$output .= "     font-weight: ".$typography['weight'] . "; \n";
			$output .= "     font-variant: ".$typography['variant'] . "; \n";
			$output .= "     text-transform: ".$typography['transform'] . "; \n";
			$output .= "     text-decoration: ".$typography['decoration'] . "; \n";
			$output .= "     text-shadow: ".$typography['shadow'] . " #".$typography['shadowcolor']."; \n";
			$output .= "     letter-spacing: ".$typography['lttrspace'] . "em; \n";
			$output .= "     word-spacing: ".$typography['wordspace'] . "em; \n";
			$output .= "     opacity: ".$typography['opacity']/100 . "; \n";
			$output .= "     color: #".$typography['color'] . "; \n";
			$output .= "}\n";
		}
		if ( $typography = $topf_options['blog_title_font'] )
		{
			$typeface = topf_default_font_stack( $typography['face'] );
			if ( $typeface == "" && $typography['face'] == "" ) $typeface = "serif";
			elseif ( $typeface == "" ) $typeface = $typography['face'];
			$output .= "#blog-title a {\n     font-family: " . $typeface . "; \n";
			$output .= "     font-size: " . $typography['size'] . "px; \n";
			$output .= "     line-height: " . $typography['height'] . "px; \n";
			$output .= "     font-style: ".$typography['style'] . "; \n";
			$output .= "     font-weight: ".$typography['weight'] . "; \n";
			$output .= "     font-variant: ".$typography['variant'] . "; \n";
			$output .= "     text-transform: ".$typography['transform'] . "; \n";
			$output .= "     text-decoration: ".$typography['decoration'] . "; \n";
			$output .= "     text-shadow: ".$typography['shadow'] . " #".$typography['shadowcolor']."; \n";
			$output .= "     letter-spacing: ".$typography['lttrspace'] . "em; \n";
			$output .= "     word-spacing: ".$typography['wordspace'] . "em; \n";
			$output .= "     opacity: ".$typography['opacity']/100 . "; \n";
			$output .= "     color: #".$typography['color'] . "; \n";
			$output .= "}\n";
		}
		if ( $typography = $topf_options['blog_description_font'] )
		{
			$typeface = topf_default_font_stack( $typography['face'] );
			if ( $typeface == "" && $typography['face'] == "" ) $typeface = "serif";
			elseif ( $typeface == "" ) $typeface = $typography['face'];
			$output .= "#blog-description {\n     font-family: " . $typeface . "; \n";
			$output .= "     font-size: " . $typography['size'] . "px; \n";
			$output .= "     line-height: " . $typography['height'] . "px; \n";
			$output .= "     font-style: ".$typography['style'] . "; \n";
			$output .= "     font-weight: ".$typography['weight'] . "; \n";
			$output .= "     font-variant: ".$typography['variant'] . "; \n";
			$output .= "     text-transform: ".$typography['transform'] . "; \n";
			$output .= "     text-decoration: ".$typography['decoration'] . "; \n";
			$output .= "     text-shadow: ".$typography['shadow'] . " #".$typography['shadowcolor']."; \n";
			$output .= "     letter-spacing: ".$typography['lttrspace'] . "em; \n";
			$output .= "     word-spacing: ".$typography['wordspace'] . "em; \n";
			$output .= "     opacity: ".$typography['opacity']/100 . "; \n";
			$output .= "     color: #".$typography['color'] . "; \n";
			$output .= "}\n";
		}
		if ( $typography = $topf_options['post_title_font'] )
		{
			$typeface = topf_default_font_stack( $typography['face'] );
			if ( $typeface == "" && $typography['face'] == "" ) $typeface = "serif";
			elseif ( $typeface == "" ) $typeface = $typography['face'];
			$output .= ".entry-title, .entry-title a {\n     font-family: " . $typeface . "; \n";
			$output .= "     font-size: " . $typography['size'] . "px; \n";
			$output .= "     line-height: " . $typography['height'] . "px; \n";
			$output .= "     font-style: ".$typography['style'] . "; \n";
			$output .= "     font-weight: ".$typography['weight'] . "; \n";
			$output .= "     font-variant: ".$typography['variant'] . "; \n";
			$output .= "     text-transform: ".$typography['transform'] . "; \n";
			$output .= "     text-decoration: ".$typography['decoration'] . "; \n";
			$output .= "     text-shadow: ".$typography['shadow'] . " #".$typography['shadowcolor']."; \n";
			$output .= "     letter-spacing: ".$typography['lttrspace'] . "em; \n";
			$output .= "     word-spacing: ".$typography['wordspace'] . "em; \n";
			$output .= "     opacity: ".$typography['opacity']/100 . "; \n";
			$output .= "     color: #".$typography['color'] . "; \n";
			$output .= "}\n";
		}
		if ( $typography = $topf_options['post_font'] )
		{
			$typeface = topf_default_font_stack( $typography['face'] );
			if ( $typeface == "" && $typography['face'] == "" ) $typeface = "serif";
			elseif ( $typeface == "" ) $typeface = $typography['face'];
			$output .= ".entry-content {\n     font-family: " . $typeface . "; \n";
			$output .= "     font-size: " . $typography['size'] . "px; \n";
			$output .= "     line-height: " . $typography['height'] . "px; \n";
			$output .= "     font-style: ".$typography['style'] . "; \n";
			$output .= "     font-weight: ".$typography['weight'] . "; \n";
			$output .= "     font-variant: ".$typography['variant'] . "; \n";
			$output .= "     text-transform: ".$typography['transform'] . "; \n";
			$output .= "     text-decoration: ".$typography['decoration'] . "; \n";
			$output .= "     text-shadow: ".$typography['shadow'] . " #".$typography['shadowcolor']."; \n";
			$output .= "     letter-spacing: ".$typography['lttrspace'] . "em; \n";
			$output .= "     word-spacing: ".$typography['wordspace'] . "em; \n";
			$output .= "     opacity: ".$typography['opacity']/100 . "; \n";
			$output .= "     color: #".$typography['color'] . "; \n";
			$output .= "}\n";
		}
	}


// The qTip tooltips don't look good with shadows, disable them
	$output .= ".tooltip { text-shadow: none; }\n";


// Give user last chance with their own CSS
	$custom_css = $topf_options['custom_css'];
	if ( $custom_css <> '' )
		$output .= $custom_css . "\n";


// Dump it out
	$output = "<!-- Custom Thematic Child Theme Divergence Options Styling -->\n<style type=\"text/css\">\n" . $output
			. "</style>\n"
			. "<!-- End of Thematic Child Theme Divergence Options Styling -->\n\n";
	echo $output;
}
add_action( 'wp_head', 'topf_head_css' );


/*---------------------------------------------------------------------------*/
//  Add Favicon
/*---------------------------------------------------------------------------*/
function childtheme_favicon()
{
		global $topf_options;
		if ( $topf_options['custom_favicon'] != '' )
		{
	        echo '<link rel="shortcut icon" href="'.  $topf_options['custom_favicon']  .'"/>'."\n";
	    }
		else
		{
			?>
			<!--link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" /-->
			<?php
		}
}
add_action( 'wp_head', 'childtheme_favicon' );


/*---------------------------------------------------------------------------*/
//  Show analytics code in footer
/*---------------------------------------------------------------------------*/
function childtheme_analytics()
{
	global $topf_options;

	$output = $topf_options['google_analytics'];
	if ( $output <> "" )
		echo stripslashes( $output ) . "\n";
}
add_action( 'wp_footer', 'childtheme_analytics' );


/*---------------------------------------------------------------------------*/
//  Adds a Short Code for the child theme Link
/*---------------------------------------------------------------------------*/
function childfooter_theme_link()
{
    $themelink = "<a class=\"theme-link\" href=\"".TEMPLATEURI."\" title=\"Thematic Child Theme - ".TEMPLATENAME."\" rel=\"designer\">".TEMPLATENAME."</a>";
    return $themelink;
}
add_shortcode( 'child-link', 'childfooter_theme_link' );


/*---------------------------------------------------------------------------*/
//  Everything from here on is mostly Thematic Theme Framework specific
//	They serve as examples of how some options may be implemented
/*---------------------------------------------------------------------------*/

/*---------------------------------------------------------------------------*/
//  Replace Blog Title With Logo
//  If a logo is uploaded, unhook the blog title
/*---------------------------------------------------------------------------*/
function add_childtheme_logo()
{
	global $topf_options;

	$logo = $topf_options['logo'];
	if ( ! empty( $logo ))
	{
		remove_action( 'thematic_header', 'thematic_blogtitle', 3 );
//		remove_action( 'thematic_header', 'thematic_blogdescription', 5 );
		add_action( 'thematic_header', 'childtheme_logo', 3 );
	}
}
add_action( 'wp_loaded', 'add_childtheme_logo' );


/*---------------------------------------------------------------------------*/
//  Displays the logo
/*---------------------------------------------------------------------------*/
function childtheme_logo()
{
	global $topf_options;

	$logo = get_stylesheet_directory_uri().'/library/timthumb.php?h=113&zc=0&q=90&s=1&src='.$topf_options['logo'];
    $heading_tag = ( is_home() || is_front_page() ) ? 'h1' : 'div';
    ?>
    <<?php echo $heading_tag; ?> id="site-title">
	<a href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>">
    	<img src="<?php echo $logo; ?>" alt="<?php bloginfo('name'); ?>"/>
	</a>
    </<?php echo $heading_tag; ?>>
	<?php
}


/*---------------------------------------------------------------------------*/
//  Home Page exclude posts from certain categories (TOPF Options)
/*---------------------------------------------------------------------------*/
function homepage_excludecategories()
{
	global $wp_query;
	global $topf_options;

	if ( is_home() )
	{
		$exclude_categories = $topf_options['exclude_cats_home'];
		if ( $exclude_categories == "" ) $exclude_categories = array();
		$exclude_list = array();
		foreach ( $exclude_categories as $key => $value )
		{
			$exclude_list[] = $key;
		}
		$defaults = $wp_query->query_vars;
		$exclude = array( 'category__not_in' => $exclude_list );
		$args = wp_parse_args( $exclude, $defaults );
		query_posts( $args );
	}
}
add_action( 'thematic_above_indexloop', 'homepage_excludecategories' );


/*---------------------------------------------------------------------------*/
//  Filter Footer Text
/*---------------------------------------------------------------------------*/
function childtheme_footer( $thm_footertext )
{
	global $topf_options;

	if ( $footertext = $topf_options['footer_text'] )
    	return $footertext;
}
add_filter( 'thematic_footertext', 'childtheme_footer' );


?>
