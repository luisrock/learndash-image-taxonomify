<?php


function is_buddyboss_theme() {

	$theme = wp_get_theme();
	return strpos($theme->name, 'BuddyBoss') !== false || strpos($theme->parent_theme, 'BuddyBoss') !== false;
}

function trit_settings_pass() {
  if(TRIT_WHO_CAN_SEE && TRIT_WHO_CAN_SEE == 'trit_who_logged' && !is_user_logged_in()) {
    return false;
  }

  if(TRIT_WHO_CAN_SEE && TRIT_WHO_CAN_SEE == 'trit_who_visitors' && is_user_logged_in()) {
    return false;
  }
  return true;
}

function trit_get_styles() {
	$position = (TRIT_POSITION) ? TRIT_POSITION : 'tl';
	$color = (TRIT_COLOR) ? 'color:' . TRIT_COLOR . ';' : '';
	$background = (TRIT_BACKGROUND_COLOR) ? 'background-color:' . TRIT_BACKGROUND_COLOR . ';' : '';
	$font_size = (TRIT_FONT_SIZE) ? 'font-size:' . TRIT_FONT_SIZE . ';' : '';
	$uppercase = (TRIT_UPPERCASE) ? 'text-transform: uppercase;' : '';
  return [
    'position' => $position,
    'color' => $color,
    'background' => $background,
    'font_size' => $font_size,
    'uppercase' => $uppercase,
  ];
}

function trit_get_text($course_id, $taxonomy) {	
  $term_list = get_the_terms( $course_id, $taxonomy );
  $custom_text = TRIT_CUSTOM_TEXT;
	if(empty($term_list) && !$custom_text) {
		return '';
	}
	$text = (!empty($term_list)) ? $term_list[0]->name : $custom_text;
	return esc_html($text);
}

function trit_proceed_with_text($course_id,$taxonomy) {  
  if(!trit_settings_pass()) {
    return false;
  }

	if(!$course_id) {
		return false;
	}
  
  if(!$taxonomy) {
		return false;
  }
    
  $text = trit_get_text($course_id, $taxonomy);
  if(!$text) {
		return false;
  }
  
  return $text;
}

function trit_image_grid($item_html, $post, $shortcode_atts, $user_id) {
  
  $course_id = $post->ID;
  $taxonomy = TRIT_WHICH_TAXONOMY;
  $text = trit_proceed_with_text($course_id,$taxonomy);
  if(!$text) {
    return $item_html;
  }  
  $styles = trit_get_styles();
	$style = $styles['color'] . $styles['background'] . $styles['font_size'] . $styles['uppercase'];
  $position = $styles['position'];
  
  
	$item_html = mb_convert_encoding($item_html, 'HTML-ENTITIES', "UTF-8");
	@$dom = new DOMDocument();
	@$dom->loadHTML($item_html);
	if(!$dom) {
		return $item_html;
	}
	
	$target_element = $dom->getElementsByTagName('img')->item(0);
	if(!$target_element) {
		return $item_html;
	}
	
	//create container
	$div_container = $dom->createElement('div');
	if(!$div_container) {
		return $item_html;
	}
	$div_container->setAttribute('class', 'trit-image-tax-container');
	if (is_buddyboss_theme()) {
		$div_container->setAttribute('style', 'position:initial;');  
	}

	//create text block
	$text_block = $dom->createElement('div', $text);
	if(!$text_block) {
		return $item_html;
	}
	$text_block->setAttribute('class', "trit-image-tax-text-block trit-image-tax-text-block-$position trit-grid-item");
	if(!empty($style)) {
		$text_block->setAttribute('style', $style);
	}

	//remove target element and place it in the new div container created, with text block appendend
	$parent = $target_element->parentNode;
	$parent->removeChild($target_element);
	$div_container->appendChild($target_element);
	$div_container->appendChild($text_block);
	$parent->appendChild($div_container);
	
	return $dom->saveHTML();
}

function trit_buddyboss_course_grid( $item_html ) {
  
  if (!is_buddyboss_theme()) { 
    return $item_html;
  }
  
  if(!is_archive() || 'sfwd-courses' !== get_post_type() ) {
    return $item_html;
  }
  
  $course_id = get_the_ID();
  $taxonomy = TRIT_WHICH_TAXONOMY;
  $text = trit_proceed_with_text($course_id,$taxonomy);
  if(!$text) {
    return $item_html;
  }  
  $styles = trit_get_styles();
	$style = $styles['color'] . $styles['background'] . $styles['font_size'] . $styles['uppercase'];
  $position = $styles['position'];

  $output = '<div class="trit-image-tax-container" style="position:initial;">';
  $output .= $item_html;
  $output .= '<div class=';
  $output .= '"trit-image-tax-text-block trit-image-tax-text-block-' . $position . ' trit-grid-item">';
  $output .= $text;
  $output .= '</div>';
  $output .= '</div>';
  
  return $output;
}
add_filter( 'post_thumbnail_html', 'trit_buddyboss_course_grid' );