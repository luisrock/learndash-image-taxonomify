<?php

function trit_image_grid($item_html, $post, $shortcode_atts, $user_id) {


    if(TRIT_WHO_CAN_SEE && TRIT_WHO_CAN_SEE == 'trit_who_logged' && !is_user_logged_in()) {
        return $item_html;
    }

    if(TRIT_WHO_CAN_SEE && TRIT_WHO_CAN_SEE == 'trit_who_visitors' && is_user_logged_in()) {
        return $item_html;
    }

	$taxonomy = TRIT_WHICH_TAXONOMY;
	$position = (TRIT_POSITION) ? TRIT_POSITION : 'tl';
	$custom_text = TRIT_CUSTOM_TEXT;
	$color = (TRIT_COLOR) ? 'color:' . TRIT_COLOR . ';' : '';
	$background = (TRIT_BACKGROUND_COLOR) ? 'background-color:' . TRIT_BACKGROUND_COLOR . ';' : '';
	$font_size = (TRIT_FONT_SIZE) ? 'font-size:' . TRIT_FONT_SIZE . ';' : '';
	$uppercase = (TRIT_UPPERCASE) ? 'text-transform: uppercase;' : '';
	
	$style = $color . $background . $font_size . $uppercase;
	$course_id = $post->ID;
    
	if(!$taxonomy || !$position || !$course_id) {
		return $item_html;
	}

	$term_list = get_the_terms( $course_id, $taxonomy );
	if(empty($term_list) && !$custom_text) {
		return $item_html;
	}
	$text = (!empty($term_list)) ? $term_list[0]->name : $custom_text;
	$text = esc_html($text);

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