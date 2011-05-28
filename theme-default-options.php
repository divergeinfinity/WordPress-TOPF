<?php

/*---------------------------------------------------------------------------*/
//	The Options Array with default values for the Theme Options Panel Framework
//	All option controls should be added here.
//
//	The Array depends on proper formatting.
//	Each section of options should have a heading FIRST. These are used as Tabs.
//	Each heading may have one or more sections within each Heading Tab.
//	Option types are added after each heading or section
//
//	Current Option Types supported:
//
//		heading
//		section			(not yet implemented)
//		info
//		other
//		background
//		border
//		checkbox
//		color
//		images
//		multicheck
//		radio
//		range
//		select
//		select2
//		textarea
//		text
//		typography
//		upload
//
/*---------------------------------------------------------------------------*/

add_action( 'init', 'setup_theme_default_options_array' );

function setup_theme_default_options_array()
{
	/*---------------------------------------------------------------------------*/
	//  Preliminary setup -- a few arrays that will used within the Options
	/*---------------------------------------------------------------------------*/
	//	Access the WordPress Categories via an Array
	$topf_categories = array();
	$topf_categories_obj = get_categories( 'hide_empty=0' );
	foreach ( $topf_categories_obj as $topf_cat )
	{
		$topf_categories[$topf_cat->cat_ID] = $topf_cat->cat_name;
	}
//	array_unshift( $topf_categories, "Select a category:" );

	//	Stylesheets Reader
	$alt_stylesheet_path = get_stylesheet_directory()."/styles";
	$alt_stylesheets = array();

	if ( is_dir( $alt_stylesheet_path ) )
	{
		if ( $alt_stylesheet_dir = opendir( $alt_stylesheet_path ) )
		{
			while ( ($alt_stylesheet_file = readdir( $alt_stylesheet_dir )) !== false )
			{
				if ( stristr( $alt_stylesheet_file, ".css" ) !== false )
				{
					$alt_stylesheets[] = $alt_stylesheet_file;
				}
			}
		}
	}


	/*---------------------------------------------------------------------------*/
	/*---------------------------------------------------------------------------*/
	//  This is it! The Default Options Array.
	/*---------------------------------------------------------------------------*/
	/*---------------------------------------------------------------------------*/

	global $topf_theme_options;
	$topf_theme_options = array();

	/*---------------------------------------------------------------------------*/
	//  General Tab Settings
	/*---------------------------------------------------------------------------*/
	$topf_theme_options[] = array(
		"name"		=> "General",
		"type"		=> "heading"
	);

	$topf_theme_options[] = array(
		"name"		=> "Divergence Theme Version",
		"id"		=> "theme_version",
		"std"		=> "0.9",
		"type"		=> "info"
	);

	$topf_theme_options[] = array(
		"name"		=> "Custom Favicon",
		"desc"		=> "Upload a png/gif/ico image that will represent your website's favicon.  (leave blank for none)",
		"id"		=> "custom_favicon",
		"class"		=> "",
		"std"		=> "",
		"type"		=> "upload"
	);

	$topf_theme_options[] = array(
		"name"		=> "Disable Thematic Document Title",
		"desc"		=> "Check to disable Thematic supplying the Doc Title in the Head for Blog pages and posts.  This allows SEO plugins Like Headspace and All In One SEO to do their thing.  Leave checked to use Thematic's SEO functionality.",
		"id"		=> "disabledoctitle",
		"class"		=> "",
		"std"		=> "0",
		"type"		=> "checkbox",
	);

	$topf_theme_options[] = array(
		"name"		=> "Tracking Code",
		"desc"		=> "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
		"id"		=> "google_analytics",
		"class"		=> "",
		"std"		=> "",
		"type"		=> "textarea"
	);

	$topf_theme_options[] = array(
		"name"		=> "Footer SiteInfo Text",
		"desc"		=> "You can use the following shortcodes in your Footer SiteInfo text: [wp-link] [theme-link] [loginout-link] [blog-title] [blog-link] [the-year] [theme-name] [theme-author] [theme-uri] [child-link] [theme-version]",
		"id"		=> "footer_text",
		"class"		=> "",
		"std"		=> "Powered by [wp-link]. Built on the [theme-link]. Child Theme [child-link]. [loginout-link]",
		"type"		=> "textarea"
	);

	/*---------------------------------------------------------------------------*/
	//  Header Tab Settings
	/*---------------------------------------------------------------------------*/
	$topf_theme_options[] = array(
		"name"		=> "Header",
		"type"		=> "heading"
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Default Header Background",
		"desc"		=> "Check to use the default installed Header background.",
		"id"		=> "usedefaultheader",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Custom Header Background",
		"desc"		=> "Upload your custom Header background or gradient for your theme.   You must uncheck Use Default Header (above) for this to be used.  (leave blank for none).  Uses the WordPress Media Uploader.  You should do any resizing beforehand.  Available area is full Window width with a height of 194px.",
		"id"		=> "headerbackground",
		"std"		=> array(
					"image"		=> "",
					"repeat"	=> "repeat-x",
					"position"	=> "left top",
					"posx"		=> "0",
					"posy"		=> "0",
					"attachment"=> "scroll",
					"color"		=> ""
		),
		"type"		=> "background"
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Default Branding Image",
		"desc"		=> "Check to use the default installed Branding background image.",
		"id"		=> "usedefaultbranding",
		"std"		=> "0",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Custom Branding Image",
		"desc"		=> "Upload your custom Branding image as a background for your theme.(will be resized to 960x150). You must uncheck Use Default Branding Image (above) for this to be used.  (leave blank for none)",
		"id"		=> "brandingbackground",
		"std"		=> "1",
		"type"		=> "upload"
	);

	$topf_theme_options[] = array(
		"name"		=> "Show Blog Title",
		"desc"		=> "Check to display the Blog Title in the Branding area of the Header.",
		"id"		=> "displayblogtitle",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Show Blog Description",
		"desc"		=> "Check to display the Blog Description in the Branding area of the Header.",
		"id"		=> "displayblogdesc",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Custom Logo",
		"desc"		=> "Use your Custom Logo instead of the Blog Title. (even if Show Blog Title is checked).  The height will be resized to 113 pixels.  (leave blank for none and to show the Blog Title if checked)",
		"id"		=> "logo",
		"std"		=> "",
		"type"		=> "upload"
	);

	/*---------------------------------------------------------------------------*/
	//  Content Tab Options
	/*---------------------------------------------------------------------------*/
	$topf_theme_options[] = array(
		"name"		=> "Content",
		"type"		=> "heading"
	);

	$url =  get_stylesheet_directory_uri() . '/options/images/';
	$topf_theme_options[] = array(
		"name"		=> "Main Layout",
		"desc"		=> "Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.",
		"id"		=> "layout",
		"std"		=> "2c-r-fixed.css",
		"type"		=> "images",
		"options"	=> array(
						"1col-fixed.css"	=> $url . "1col.png",
						"2c-r-fixed.css"	=> $url . "2cr.png",
						"2c-l-fixed.css"	=> $url . "2cl.png",
						"3c-fixed.css"		=> $url . "3cm.png",
						"3c-r-fixed.css"	=> $url . "3cr.png"
		)
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Default Body Background",
		"desc"		=> "Check to use the default installed Background for the Body.  (you must uncheck this to use your own Custom Body Background below).",
		"id"		=> "usedefaultbody",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Custom Body Background",
		"desc"		=> "Upload your custom Body background or gradient for your theme.   (you must uncheck Use Default Body above for this to work).  (leave blank for none).  Uses the WordPress Media Uploader.",
		"id"		=> "bodybackground",
		"std"		=> array(
					"image"		=> "",
					"repeat"	=> "repeat",
					"position"	=> "left top",
					"posx"		=> "0",
					"posy"		=> "0",
					"attachment"=> "scroll",
					"color"		=> "ffffff"
		),
		"type"		=> "background"
	);

	$topf_theme_options[] = array(
		"name"		=> "Home Page Exclude Categories",
		"desc"		=> "Select which categories to exclude from display on the Home Page.",
		"id"		=> "exclude_cats_home",
		"std"		=> array(),
		"type"		=> "multicheck",
		"options"	=> $topf_categories
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Stylized Calendar On Posts",
		"desc"		=> "Check to display the stylized Calendar for the date on Posts.  Displayed in front of Post Titles.",
		"id"		=> "displaycalendar",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Stylized Post Calendar Opacity",
		"desc"		=> "A percentage number between 1 and 100 for the opacity of the Calendar for the date on Posts.  Leave blank for the default 50% value.",
		"id"		=> "calendar_opacity",
		"std"		=> "50",
		"type"		=> "range",
		"options"	=> array(
					"min"		=> "1",
					"max"		=> "100"
		)
	);

	$topf_theme_options[] = array(
		"name"		=> "Show Post Meta Information",
		"desc"		=> "Check to display the Author and Post Date on posts.  Usually displayed under the Post Title.",
		"id"		=> "displaymeta",
		"std"		=> "0",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Show Default Post Divider",
		"desc"		=> "Check to display the graphical Post Divider at the end of a post.  Will appear between posts on the Home or Category pages.",
		"id"		=> "displaydivider",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Excerpts On Home Page",
		"desc"		=> "Check to display the excerpts instead of full posts on the Home Page.",
		"id"		=> "displayexcerpts",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Accentuate First Post On Home Page",
		"desc"		=> "Check to display a border and special background around the first post on the Home Page.",
		"id"		=> "hilitefirst",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "First Post Border",
		"desc"		=> "Set the attributes for the border of the first post if Accentuate First Post (above) is checked.",
		"id"		=> "firstpost_border",
		"std"		=> array(
					"width"	=> "3",/* px */
					"style"	=> "double",
					"color"	=> "3E853E"
		),
		"type"		=> "border"
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Full First Post On Home Page",
		"desc"		=> "Check to display a full first post instead of excerpts on the Home Page even if Use Excerpts is checked.",
		"id"		=> "displayfullfirst",
		"std"		=> "0",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Index Aside Insert",
		"desc"		=> "The number of posts on the Home page before inserting the widgetized Index Insert Aside.",
		"id"		=> "home_aside_insert",
		"std"		=> "2",
		"type"		=> "text"
	);

	$topf_theme_options[] = array(
		"name"		=> "Use Default Footer Background",
		"desc"		=> "Check to use the default installed Background for the Footer.",
		"id"		=> "usedefaultfooter",
		"std"		=> "1",
		"type"		=> "checkbox"
	);

	/*---------------------------------------------------------------------------*/
	//  Fonts Tab Options
	/*---------------------------------------------------------------------------*/
	$topf_theme_options[] = array(
		"name"		=> "Fonts",
		"type"		=> "heading"
	);

	$topf_theme_options[] = array(
		"name"		=> "Font Information",
		"id"		=> "fontinfo",
		"std"		=> "<p>Available built in Fonts for most Browsers are: Arial, Courier, Courier New, Geneva, Georgia, Helvetica, Monaco, Palatino, Tahoma, Times, Times New Roman, Trebuchet MS, Utopia, Verdana.  The 'Divergence' Theme will build substitute Font Stacks around each of these.</p><p>Just type their names in for the Font Family and that Font, (or a close substitute), will be used by the Theme.  The default Font in use by the 'Divergence' stylesheet is 'Verdana'.</p><p>In addition, the 'Divergence' Theme will use Fonts from the <a href='http://http://www.google.com/webfonts'>Google Font Directory</a>.  Just type the Font Family name EXACTLY as it appears in the Directory, and the Theme will download and use that Font for you.  (NOTE: using a lot of Fonts can slow down page load times.  Use in moderation.)</p>",
		"type"		=> "info"
	);

	$topf_theme_options[] = array(
		"name"		=> "Use StyleSheet Font Settings",
		"desc"		=> "Check to ignore the following Font settings and use the default Stylesheet settings.",
		"id"		=> "ignorefonts",
		"std"		=> "0",
		"type"		=> "checkbox"
	);

	$topf_theme_options[] = array(
		"name"		=> "Body Font",
		"desc"		=> "Specify the body font properties.  Font size/Line height, etc.  You must un-check Use StyleSheet Font (above) for these to work.",
		"id"		=> "body_font",
		"std"		=> array(
					"face"		=> "Verdana",
					"size"		=> "12",/* px */
					"height"	=> "18",/* px */
					"style"		=> "normal",
					"weight"	=> "400",
					"variant"	=> "normal",
					"transform"	=> "none",
					"decoration"=> "none",
					"shadow"	=> "1px 1px 1px",
					"shadowcolor"=> "ffd3d3",
					"lttrspace"	=> "0",/* em */
					"wordspace"	=> "0",/* em */
					"opacity"	=> "100",
					"color"		=> "444444"
		),
		"type"		=> "typography"
	);

	$topf_theme_options[] = array(
		"name"		=> "Blog Title",
		"desc"		=> "Specify the Blog Title properties.  Font size/Line height, etc.  You must un-check Use StyleSheet Font (above) for these to work.  (default font is 'ScriptinaProRegular').",
		"id"		=> "blog_title_font",
		"std"		=> array(
					"face"		=> "ScriptinaProRegular",
					"size"		=> "60",/* px */
					"height"	=> "72",/* px */
					"style"		=> "bold",
					"weight"	=> "900",
					"variant"	=> "normal",
					"transform"	=> "none",
					"decoration"=> "none",
					"shadow"	=> "4px 4px 4px",
					"shadowcolor"=> "444444",
					"lttrspace"	=> "0.01",/* em */
					"wordspace"	=> "0.1",/* em */
					"opacity"	=> "100",
					"color"		=> "A64E4E"
		),
		"type"		=> "typography"
	);

	$topf_theme_options[] = array(
		"name"		=> "Blog Description",
		"desc"		=> "Specify the Blog Description properties.  Font size/Line height, etc.  You must un-check Use StyleSheet Font (above) for these to work.",
		"id"		=> "blog_description_font",
		"std"		=> array(
					"face"		=> "Verdana",
					"size"		=> "13",/* px */
					"height"	=> "18",/* px */
					"style"		=> "italic",
					"weight"	=> "400",
					"variant"	=> "normal",
					"transform"	=> "lowercase",
					"decoration"=> "none",
					"shadow"	=> "1px 1px 1px",
					"shadowcolor"=> "000000",
					"lttrspace"	=> "0",/* em */
					"wordspace"	=> "0",/* em */
					"opacity"	=> "100",
					"color"		=> "3e853e"
		),
		"type"		=> "typography"
	);

	$topf_theme_options[] = array(
		"name"		=> "Post Title Font",
		"desc"		=> "Specify the Post Title properties.  Font size/Line height, etc.  You must un-check Use StyleSheet Font (above) for these to work.",
		"id"		=> "post_title_font",
		"std"		=> array(
					"face"		=> "Verdana",
					"size"		=> "24",/* px */
					"height"	=> "30",/* px */
					"style"		=> "bold",
					"weight"	=> "900",
					"variant"	=> "normal",
					"transform"	=> "none",
					"decoration"=> "none",
					"shadow"	=> "1px 1px 1px",
					"shadowcolor"=> "ffd3d3",
					"lttrspace"	=> "-0.06",/* em */
					"wordspace"	=> "0",/* em */
					"opacity"	=> "100",
					"color"		=> "444444"
		),
		"type"		=> "typography"
	);

	$topf_theme_options[] = array(
		"name"		=> "Post Font",
		"desc"		=> "Specify the Post font properties.  Font size/Line height, etc.  You must un-check Use StyleSheet Font (above) for these to work.",
		"id"		=> "post_font",
		"std"		=> array(
					"face"		=> "Quattrocento",
					"size"		=> "15",/* px */
					"height"	=> "18",/* px */
					"style"		=> "normal",
					"weight"	=> "400",
					"variant"	=> "normal",
					"transform"	=> "none",
					"decoration"=> "none",
					"shadow"	=> "1px 1px 1px",
					"shadowcolor"=> "ffd3d3",
					"lttrspace"	=> "-0.01",/* em */
					"wordspace"	=> "0",/* em */
					"opacity"	=> "100",
					"color"		=> "333333"
		),
		"type"		=> "typography"
	);

	/*---------------------------------------------------------------------------*/
	//  Styling Tab Options
	/*---------------------------------------------------------------------------*/
	$topf_theme_options[] = array(
		"name"		=> "Styling",
		"type"		=> "heading"
	);

	$topf_theme_options[] = array(
		"name"		=> "Theme Stylesheet",
		"desc"		=> "Select your themes alternative color scheme.",
		"id"		=> "alt_stylesheet",
		"std"		=> "divergence.css",
		"type"		=> "select",
		"options"	=> $alt_stylesheets
	);

	$topf_theme_options[] = array(
		"name"		=> "Header Background Color",
		"desc"		=> "Pick a background color for the header (leave blank to use default styling).",
		"id"		=> "header_background_color",
		"std"		=> "",
		"type"		=> "color"
	);

	$topf_theme_options[] = array(
		"name"		=> "Body Background Color",
		"desc"		=> "Pick a background color for the theme (leave blank to use default styling).",
		"id"		=> "body_background",
		"std"		=> "",
		"type"		=> "color"
	);

	$topf_theme_options[] = array(
		"name"		=> "Footer Background Color",
		"desc"		=> "Pick a background color for the footer (leave blank to use default styling).",
		"id"		=> "footer_background",
		"std"		=> "",
		"type"		=> "color"
	);

	$topf_theme_options[] = array(
		"name"		=> "Primary Color",
		"desc"		=> "Pick your main color for the theme.  (default is A64E4E).  Leave blank to use the default color.  Blog title, hovered links, Section Headings, etc.",
		"id"		=> "primarycolor",
		"std"		=> "A64E4E",
		"type"		=> "color"
	);

	$topf_theme_options[] = array(
		"name"		=> "Secondary Color",
		"desc"		=> "Pick your secondary contrast color for the theme.  (default is 3E853E).  Leave blank to use the default color.  Borders, Styled Calendar, Read More, etc.",
		"id"		=> "secondcolor",
		"std"		=> "3E853E",
		"type"		=> "color"
	);

	$topf_theme_options[] = array(
		"name"		=> "Third Color",
		"desc"		=> "Pick your third contrast color for the theme.  (default is 444444).  Leave blank to use the default color.  The bulk of text is in this color.",
		"id"		=> "thirdcolor",
		"std"		=> "444444",
		"type"		=> "color"
	);

	$topf_theme_options[] = array(
		"name"		=> "Custom CSS",
		"desc"		=> "Quickly add some CSS to your theme by adding it to this block.",
		"id"		=> "custom_css",
		"std"		=> "",
		"type"		=> "textarea"
	);

}


/*---------------------------------------------------------------------------*/
//  Formulate Font stacks for CSS
/*---------------------------------------------------------------------------*/
function topf_default_font_stack( $font )
{
/*	Standard Font Stacks
Wide sans-serif stack:
font-family: "Verdana", "Geneva", sans-serif;
Narrow sans-serif stack:
font-family: "Arial", "Tahoma", "Helvetica", sans-serif;
Wide serif stack:
font-family: "Georgia", "Palatino", "Utopia", serif;
Narrow serif stack:
font-family: "Times New Roman", "Times", serif;
Monospace stack:
font-family: "Courier New", "Courier", "Monaco", monospace;
*/
	$stack = '';

	switch ( strtolower( $font ))
	{
		case 'arial':
			$stack .= 'Arial, Tahoma, Helvetica, sans-serif';
			break;
		case 'courier':
			$stack .= 'Courier, "Courier New", monospace';
			break;
		case 'courier new':
			$stack .= '"Courier New", Courier, monospace';
			break;
		case 'geneva':
			$stack .= 'Geneva, Verdana, sans-serif';
			break;
		case 'georgia':
			$stack .= 'Georgia, Palatino, serif';
			break;
		case 'helvetica':
			$stack .= 'Helvetica, Arial, Tahoma, sans-serif';
			break;
		case 'monaco':
			$stack .= 'Monaco, Courier, "Courier New", monospace';
			break;
		case 'palatino':
			$stack .= 'Palatino, Georgia, serif';
			break;
		case 'tahoma':
			$stack .= 'Tahoma, Arial, Helvetica, sans-serif';
			break;
		case 'times':
			$stack .= 'Times, "Times New Roman", serif';
			break;
		case 'times new roman':
			$stack .= '"Times New Roman", Times, serif';
			break;
		case 'trebuchet':
			$stack .= '"Trebuchet MS", sans-serif';
			break;
		case 'utopia':
			$stack .= 'Utopia, Georgia, Palatino, serif';
			break;
		case 'verdana':
			$stack .= 'Verdana, Geneva, sans-serif';
			break;
		case 'serif':
			$stack .= 'serif';
			break;
		case 'sans-serif':
			$stack .= 'sans-serif';
			break;
		case 'cursive':
			$stack .= 'cursive';
			break;
		case 'fantasy':
			$stack .= 'fantasy';
			break;
		case 'monospace':
			$stack .= 'monospace';
			break;
	}
	return $stack;
}

?>
