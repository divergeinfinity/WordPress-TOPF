<?php

/*---------------------------------------------------------------------------*/
//	@package TOPF
//	@version 1.0
//	@author Jeff Parsons <jeffrey.allen.parsons@gmail.com>
//	@copyright Copyright (c) 2011, Jeff Parsons
//	@link http://diverge.blogdns.com/blog/wordpress-theme-options-panel-framework/
//	@license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
//
//	Theme Options Panel Framework Class Definition for Options_Parser
/*---------------------------------------------------------------------------*/
class Options_Parser
{
	var $these_items;		// The array of default options items
	var $this_entry;		// The WordPress option table entry name
	var $menus_li;
	var $defaults;
	var $tab_headers;
	var $sections;

	function __construct( $option_items, $this_options_entry )
	{
		$this->these_options = $option_items;
		$this->this_entry = $this_options_entry;
		$this->topf_parser();
	}

	/*---------------------------------------------------------------------------*/
	//	Generates the Options within the Theme Options Panel Framework
	//	Cycles through each item and provides final html markup for each option item,
	//	as well as default values for each item and Section Menus for each group of items
	/*---------------------------------------------------------------------------*/
	private function topf_parser()
	{
		$this->menus_li = '';
		$this->defaults = array();
		$this->tab_headers = array();
		$this->sections = array();
		$section_name = 0;
		$menus_li = '';
		$defaults = array();
		$tab_headers = array();
		$sections = array();

		foreach ( $this->these_options as $item )
		{
			// Keep all ID's lowercase.  Replace non-word chars with underscores.
			if ( isset( $item['id'] ) ) $item['id'] = preg_replace( '/\W/', '_', strtolower( $item['id']) );

			if ( $item['type'] == "heading" )
			{
				$tab_headers[] = $item['name'];
				$tab_hook = str_replace( " ", "-", strtolower( $item['name'] ) );

				// formulate the <li> for jQuery Tools Tabs (add current class to current tab)
				if ( $tab_hook == $this->get_current_tab_slug() ):
					$menus_li .= '<li><a
					class="current"
					title="'.$item['name'].'"
					href="?page=options-panel&tab='.$tab_hook.'">' . $item['name'] . '</a></li>';
				else:
					$menus_li .= '<li><a
					title="'.$item['name'].'"
					href="?page=options-panel&tab='.$tab_hook.'">' . $item['name'] . '</a></li>';
				endif;

				$section_name = $item['name']; // record the heading section name for later use
				continue;
			}

			// Create array of defaults
			if ( $item['type'] == 'multicheck' )
			{
				if ( is_array( $item['std'] ) )
				{
					foreach ( $item['std'] as $i => $key )
					{
						$defaults[$item['id']][$key] = '1';
					}
				}
				else
				{
					$defaults[$item['id']][$item['std']] = '1';
				}
			}
			else if ( $item['type'] <> 'other' )
			{
				if ( isset( $item['id'] )) $defaults[$item['id']] = $item['std'];
			}

			$sections[$section_name][] = $item; // store each option item in it's section heading
		}

		// all option items are parsed, record the findings for exterior public access
		$this->menus_li = $menus_li;
		$this->defaults = $defaults;
		$this->tab_headers = $tab_headers;
		$this->sections = $sections;
	}

	/*---------------------------------------------------------------------------*/
	//	Format html markup for screen display on an individual Option Item
	/*---------------------------------------------------------------------------*/
	public function format_item_for_display( $item )
	{
		$data = get_option( $this->this_entry );
		$display = "";
		$class = "";

		if ( isset( $item['class'] ) )
		{
			$class = $item['class'];
		}

		$display .= '<div class="topf-option topf-option-'.$item['type'].'">'."\n";
		$display .= '<div class="topf-controls '.$class.'">'."\n";

		switch ( $item['type'] )
		{
			case 'background':
				$image_repeat = array( "no-repeat", "repeat-x", "repeat-y", "repeat" );
				$body_pos = array( "top left", "top center", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right" );
// URL
				$display .= '<input
					id="'.$item['id'].'"
					class="topf-background topf-background-image"
					name="'.$this->this_entry.'['.$item['id'].'][image]"
					type="text"
					size="80"
					value="'.$data[$item['id']]['image'].'" />';
				$display .= '<input
					id="topf-upload-image-button-'.$item['id'].'"
					class="topf-background topf-upload-button"
					name="topf-upload-image-button-'.$item['id'].'"
					type="button"
					value="Upload Image" /><div style="clear:both;">  </div>';
// Repeat
				$display .= '<label
					class="topf-background-label"
					for="'.$item['id'].'_repeat">Image Repeat</label>';
				$display .= '<select
					id="'.$item['id'].'"
					class="topf-background topf-background-repeat"
					name="'.$this->this_entry.'['.$item['id'].'][repeat]" >'."\n";
				foreach ( $image_repeat as $option )
				{
					$display .= '<option
						value="'.$option.'" '.selected( $data[$item['id']]['repeat'], $option, '0' ).' />'.$option.'
						</option>';
				}
				$display .= '</select><div style="clear:both;">  </div>';
// Position
				$display .= '<label
					class="topf-background-label"
					for="'.$item['id'].'_position">Image Position</label>';
				$display .= '<select
					id="'.$item['id'].'"
					class="topf-background topf-background-position"
					name="'.$this->this_entry.'['.$item['id'].'][position]" >'."\n";
				foreach ( $body_pos as $option )
				{
					$display .= '<option
						value="'.$option.'" '.selected( $data[$item['id']]['position'], $option, '0' ).' />'.$option.'
						</option>';
				}
				$display .= '</select><div style="clear:both;">  </div>';
// Attachment
				$display .= '<label
					class="topf-background-label">Image Attach</label>';
				$display .= '<input
					class="topf-radio topf-background topf-background-attachment"
					name="'.$this->this_entry.'['.$item['id'].'][attachment]"
					type="radio"
					value="scroll"  '.checked( $data[$item['id']]['attachment'], "scroll", '0' ).' />
					<span class="topf-background-radio-label">Scroll</span>';
				$display .= '<input
					class="topf-radio topf-background topf-background-attachment"
					name="'.$this->this_entry.'['.$item['id'].'][attachment]"
					type="radio"
					value="fixed"  '.checked( $data[$item['id']]['attachment'], "fixed", '0' ).' />
					<span class="topf-background-radio-label">Fixed</span>';
				$display .= '<div style="clear:both;">  </div>';
// Color
				$display .= '<span
					id="'.$item['id'].'-colorSelector"
					class="topf-colorSelector">
					<label class="topf-background-label">Background Color</label>';
				$display .= '<input
					id="'.$item['id'].'"
					class="topf-color"
					name="'.$this->this_entry.'['.$item['id'].'][color]"
					type="text"
					value="'.$data[$item['id']]['color'].'" />';
				$display .= '</span>';
				break;

			case 'range':
				$display .= '<input
					id="'.$item['id'].'"
					class="topf-input"
					name="'.$this->this_entry.'['.$item['id'].']"
					type="'.$item['type'].'"
					min="'.$item['options']['min'].'"
					max="'.$item['options']['max'].'"
					value="'.$data[$item['id']].'" />';
				break;

			case 'text':
				$display .= '<input
					id="'.$item['id'].'"
					class="topf-input"
					name="'.$this->this_entry.'['.$item['id'].']"
					type="'.$item['type'].'"
					value="'.$data[$item['id']].'" />';
				break;

			case 'select':
				$display .= '<select
					id="'.$item['id'].'"
					class="topf-input"
					name="'.$this->this_entry.'['.$item['id'].']" >'."\n";
				foreach ( $item['options'] as $option )
				{
					$display .= '<option
						value="'.$option.'" '.selected( $data[$item['id']], $option, '0' ).' />'.$option.'
						</option>';
				}
				$display .= '</select>';
				break;

			case 'select2':
				$display .= '<select
					id="'.$item['id'].'"
					class="topf-input-select-wide"
					name="'.$this->this_entry.'['.$item['id'].']" >';
				foreach ( $item['options'] as $option => $name )
				{
					$display .= '<option
					value="'.$option.'" '.selected( $data[$item['id']], $option, '0' ).' />'.$name.'</option>';
				}
				$display .= '</select>';
				break;

			case 'textarea':
				$cols = '15';
				$ta_value = '';
				if ( isset( $item['options'] ) )
				{
					$ta_options = $item['options'];
					if ( isset( $ta_options['cols'] ) )
					{
						$cols = $ta_options['cols'];
					}
				}
				$ta_value = stripslashes( $data[$item['id']] );
				$display .= '<textarea
					id="'.$item['id'].'"
					class="topf-input"
					name="'.$this->this_entry.'['.$item['id'].']"
					cols="'.$cols.'"
					rows="8">'.$ta_value.'</textarea>';
				break;

			case "radio":
				foreach ( $item['options'] as $option => $name )
				{
					$display .= '<input
						class="topf-input topf-radio"
						name="'.$this->this_entry.'['.$item['id'].']"
						type="radio"
						value="'.$option.'" '.checked( $data[$item['id']], $option, '0' ).' /><label>'.$name.'</label>';
				}
				break;

			case 'checkbox':
				$display .= '<input
					id="'.$item['id'].'"
					type="checkbox"
					class="topf-input topf-checkbox"
					name="'.$this->this_entry.'['.$item['id'].']"
					value="1" '.checked( $data[$item['id']], '1', '0' ).' />';
				break;

			case 'multicheck':
				$multi_stored = $data[$item['id']];
				foreach ( $item['options'] as $key => $option )
				{
					$display .= '<input
						id="'.$item['id'].'_'.$key.'"
						class="topf-input topf-checkbox"
						name="'.$this->this_entry.'['.$item['id'].']['.$key.']"
						type="checkbox"
						value="1" '.checked( $multi_stored[$key], '1', '0' ).' />
						<label for="'.$item['id'].'_'.$key.'">'.$option.'</label>';
				}
				break;

			case 'upload':
				$display .= '<input
					id="'.$this->this_entry.'['.$item['id'].']"
					class="topf-input"
					name="'.$this->this_entry.'['.$item['id'].']"
					type="text"
					size="80"
					value="'.$data[$item['id']].'" />';
				$display .= '<input
					id="topf-upload-image-button-'.$item['id'].'"
					class="topf-upload-button"
					name="topf-upload-image-button-'.$item['id'].'"
					type="button"
					value="Upload Image" />';
				break;

			case 'color':
				$display .= '<span
					id="'.$this->this_entry.'['.$item['id'].']-colorSelector"
					class="topf-colorSelector">
					<label for="'.$this->this_entry.'['.$item['id'].']">';
				$display .= '<input
					id="'.$this->this_entry.'['.$item['id'].']"
					class="topf-color"
					name="'.$this->this_entry.'['.$item['id'].']"
					type="text"
					value="'.$data[$item['id']].'" />';
				$display .= '</label></span>';
				break;

			case 'typography':
				$typography = $data[$item['id']];
// Font Size
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_size">Font Size (px)</label>';
				$display .= '<input
					id="'.$item['id'].'_size"
					class="topf-typography topf-typography-size"
					name="'.$this->this_entry.'['.$item['id'].'][size]"
					type="range"
					min="9"
					max="72"
					step="1"
					value="'.$typography['size'].'" /><div style="clear:both;">  </div>';
// Line Height
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_height">Line Height (px)</label>';
				$display .= '<input
					id="'.$item['id'].'_height"
					class="topf-typography topf-typography-height"
					name="'.$this->this_entry.'['.$item['id'].'][height]"
					type="range"
					min="12"
					max="90"
					step="1"
					value="'.$typography['height'].'" /><div style="clear:both;">  </div>';
// Font Family
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_face">Font Family</label>';
				$display .= '<input
					id="'.$item['id'].'_face"
					class="topf-typography topf-typography-face"
					name="'.$this->this_entry.'['.$item['id'].'][face]"
					type="text"
					value="'.$typography['face'].'" /><div style="clear:both;">  </div>';
// Text Color
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_color">Font Color</label>';
				$display .= '<span
					id="'.$this->this_entry.'['.$item['id'].']-colorSelector"
					class="topf-colorSelector">';
				$display .= '<input
					class="topf-color"
					name="'.$this->this_entry.'['.$item['id'].'][color]"
					id="'.$item['id'].'_color"
					type="text"
					value="'.$typography['color'].'" />';
				$display .= '</span><div style="clear:both;">  </div>';
// Style
				$styles = array(
					'normal' => 'Normal',
					'italic' => 'Italic',
					'bold' => 'Bold',
					'bold italic' => 'Bold Italic'
				);
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_style">Font Style</label>';
				$display .= '<select
					class="topf-typography topf-typography-style"
					name="'.$this->this_entry.'['.$item['id'].'][style]"
					id="'.$item['id'].'_style">';
				foreach ( $styles as $i => $style )
				{
					$display .= '<option
						value="'.$i.'" '.selected( $typography['style'], $i, '0' ).'>'.$style.'</option>';
				}
				$display .= '</select><div style="clear:both;">  </div>';
// Weight
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_size">Font Weight</label>';
				$display .= '<input
					id="'.$item['id'].'_weight"
					class="topf-typography topf-typography-weight"
					name="'.$this->this_entry.'['.$item['id'].'][weight]"
					type="range"
					min="100"
					max="900"
					step="100"
					value="'.$typography['weight'].'" /><div style="clear:both;">  </div>';
// Variant
				$display .= '<label
					class="topf-typography-label">Font Variant</label>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][variant]"
					type="radio"
					value="normal"  '.checked( $typography['variant'], "normal", '0' ).' /><span class="topf-typography-radio-label">Normal</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][variant]"
					type="radio"
					value="small-caps"  '.checked( $typography['variant'], "small-caps", '0' ).' /><span class="topf-typography-radio-label">Small Caps</span>';
				$display .= '<div style="clear:both;">  </div>';
// Transform
				$display .= '<label
					class="topf-typography-label">Text Transformation</label>';
				$display .= '<input
					class="topf-typography topf-typography-transform"
					name="'.$this->this_entry.'['.$item['id'].'][transform]"
					type="radio"
					value="none"  '.checked( $typography['transform'], "none", '0' ).' /><span class="topf-typography-radio-label">None</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][transform]"
					type="radio"
					value="capialize"  '.checked( $typography['transform'], "capitalize", '0' ).' /><span class="topf-typography-radio-label">Capitalize</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][transform]"
					type="radio"
					value="uppercase"  '.checked( $typography['transform'], "uppercase", '0' ).' /><span class="topf-typography-radio-label">Uppercase</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][transform]"
					type="radio"
					value="lowercase"  '.checked( $typography['transform'], "lowercase", '0' ).' /><span class="topf-typography-radio-label">Lowercase</span>';
				$display .= '<div style="clear:both;">  </div>';
// Decoration
				$display .= '<label
					class="topf-typography-label">Text Decoration</label>';
				$display .= '<input
					class="topf-typography topf-typography-decoration"
					name="'.$this->this_entry.'['.$item['id'].'][decoration]"
					type="radio"
					value="none"  '.checked( $typography['decoration'], "none", '0' ).' /><span class="topf-typography-radio-label">None</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][decoration]"
					type="radio"
					value="underline"  '.checked( $typography['decoration'], "underline", '0' ).' /><span class="topf-typography-radio-label">Underline</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][decoration]"
					type="radio"
					value="line-through"  '.checked( $typography['decoration'], "line-through", '0' ).' /><span class="topf-typography-radio-label">Line Through</span>';
				$display .= '<input
					class="topf-typography topf-typography-variant"
					name="'.$this->this_entry.'['.$item['id'].'][decoration]"
					type="radio"
					value="overline"  '.checked( $typography['decoration'], "overline", '0' ).' /><span class="topf-typography-radio-label">Overline</span>';
				$display .= '<div style="clear:both;">  </div>';
// Shadow
				$offsets = array(
					'none' => 'none',
					'one' => '1px 1px 1px',
					'two' => '2px 2px 2px',
					'three' => '3px 3px 3px',
					'four' => '4px 4px 4px',
					'five' => '5px 5px 5px',
					'six' => '6px 6px 6px'
				);
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_shadow">Text Shadow</label>';
				$display .= '<select
					class="topf-typography topf-typography-shadow"
					name="'.$this->this_entry.'['.$item['id'].'][shadow]"
					id="'.$item['id'].'_shadow">';
				foreach ( $offsets as $i => $offset )
				{
					$display .= '<option
						value="'.$offset.'" '.selected( $typography['shadow'], $offset, '0' ).'>'.$offset.'</option>';
				}
				$display .= '</select>';
				$display .= '<span
					id="'.$this->this_entry.'['.$item['id'].']-colorSelector"
					class="topf-colorSelector">';
				$display .= '<input
					class="topf-color"
					name="'.$this->this_entry.'['.$item['id'].'][shadowcolor]"
					id="'.$item['id'].'_shadowcolor"
					type="text"
					value="'.$typography['shadowcolor'].'" />';
				$display .= '</span><div style="clear:both;">  </div>';
// Letter Spacing
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_lttrspace">Letter Spacing (em)</label>';
				$display .= '<input
					id="'.$item['id'].'_lttrspace"
					class="topf-typography topf-typography-lttrspace"
					name="'.$this->this_entry.'['.$item['id'].'][lttrspace]"
					type="range"
					min="-0.1"
					max="1"
					step="0.002"
					value="'.$typography['lttrspace'].'" /><div style="clear:both;">  </div>';
// Word Spacing
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_size">Word Spacing (em)</label>';
				$display .= '<input
					id="'.$item['id'].'_wordspace"
					class="topf-typography topf-typography-wordspace"
					name="'.$this->this_entry.'['.$item['id'].'][wordspace]"
					type="range"
					min="-0.2"
					max="1"
					step="0.1"
					value="'.$typography['wordspace'].'" /><div style="clear:both;">  </div>';
// Opacity
				$display .= '<label
					class="topf-typography-label"
					for="'.$item['id'].'_size">Opacity</label>';
				$display .= '<input
					id="'.$item['id'].'_opacity"
					class="topf-typography topf-typography-opacity"
					name="'.$this->this_entry.'['.$item['id'].'][opacity]"
					type="range"
					min="1"
					max="100"
					step="1"
					value="'.$typography['opacity'].'" /><div style="clear:both;">  </div>';
				break;

			case 'border':
				$default = $item['std'];
				$border_stored = array(
					'width' => $data[$item['id'] . '_width'],
					'style' => $data[$item['id'] . '_style'],
					'color' => $data[$item['id'] . '_color']
				);
				/* Border Width */
				$border_stored = $data[$item['id']];
				$display .= '<select
					class="topf-border topf-border-width"
					name="'.$this->this_entry.'['.$item['id'].'][width]"
					id="'.$item['id'].'_width">';
				for ( $i = 0; $i < 21; $i++ )
				{
					$display .= '<option
						value="' . $i . '" ' . selected( $border_stored['width'], $i, '0' ) . '>' . $i . '</option>';
				}
				$display .= '</select>';
				/* Border Style */
				$display .= '<select
					class="topf-border topf-border-style"
					name="'.$this->this_entry.'['.$item['id'].'][style]"
					id="' . $item['id'] . '_style">';
				$styles = array(
					'none' => 'None',
					'dotted' => 'Dotted',
					'dashed' => 'Dashed',
					'solid' => 'Solid',
					'double' => 'Double',
					'groove' => 'Groove',
					'ridge' => 'Ridge',
					'inset' => 'Inset',
					'outset' => 'Outset'
				);
				foreach ( $styles as $i => $style )
				{
					$display .= '<option
						value="' . $i . '" ' . selected( $border_stored['style'], $i, '0' ) . '>' . $style . '</option>';
				}
				$display .= '</select>';
				/* Border Color */
				$display .= '<span
					id="'.$this->this_entry.'['.$item['id'].']-colorSelector"
					class="topf-colorSelector">
					<label for="'.$item['id'].'_color">';
				$display .= '<input
					class="topf-color"
					name="'.$this->this_entry.'['.$item['id'].'][color]"
					id="'.$item['id'].'_color"
					type="text"
					value="'.$border_stored['color'].'" />';
				$display .= '</label></span>';
				break;

			case 'images':
				$i = 0;
				$select_value = $data[$item['id']];
				foreach ( $item['options'] as $key => $option )
				{
					$i++;
					$checked = '';
					$selected = '';
					if ( NULL != checked( $data[$item['id']], $key, '0' ) )
					{
						$checked = checked( $data[$item['id']], $key, '0' );
						$selected = 'topf-radio-img-selected';
					}
					$display .= '<span>';
					$display .= '<img src="'.$option.'"
						alt="" class="topf-radio-img-img '.$selected.'"
						onClick="document.getElementById(\'topf-radio-img-'.$item['id'].$i.'\').checked = true;" />';
					$display .= '<input
						id="topf-radio-img-'.$item['id'].$i.'"
						class="topf-radio-img-radio"
						type="radio"
						style="display: none "
						value="'.$key.'"
						name="'.$this->this_entry.'['.$item['id'].']" ' . $checked . ' />';
					$display .= '</span>';
				}
				$display .= '<div class="clear"> </div>';
				break;

			case "info":
				$default = $item['std'];
				$display .= $default;
				break;

		}

		if ( $item['type'] != 'heading' )
		{
			if ( !isset( $item['desc'] ) )
			{
				$explain_value = '';
			}
			else
			{
				$explain_value = $item['desc'];
			}
			$display .= '</div><div class="topf-explain">' . $explain_value . '</div>' . "\n";
			$display .= '<div class="clear"> </div></div>' . "\n";
		}

		return $display;
	}

	/*---------------------------------------------------------------------------*/
	//	Get the current Tab slug
	/*---------------------------------------------------------------------------*/
	public function get_current_tab_slug()
	{
		$current = "";

		if ( isset( $_GET['tab'] ) ):
			$current = $_GET['tab'];
		else:
			$current = str_replace( " ", "-", strtolower( $this->these_options[0]['name'] ) );
		endif;
		return $current;
	}

	/*---------------------------------------------------------------------------*/
	//	Get the current Tab Display Name
	/*---------------------------------------------------------------------------*/
	public function get_current_tab_name()
	{
		$current = "";

		if ( isset( $_GET['tab'] ) ):
			$current = str_replace( "-", " ", ucwords( $_GET['tab'] ) );
		else:
			$current = str_replace( "-", " ", ucwords( $this->these_options[0]['name'] ) );
		endif;
		return $current;
	}

}

?>
