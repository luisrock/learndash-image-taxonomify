<?php
/**
 * Plugin Name: Image Taxonomify for Learndash
 * Plugin URI: https://wptrat.com/image-taxonomify-for-learndash/
 * Description: Image Taxonomify for Learndash is the ultimate way to place a text box containing taxonomy text (category,tag,etc) on top of your LearnDash course image grid.
 * Author: Luis Rock
 * Author URI: https://wptrat.com/
 * Version: 1.1.0
 * Text Domain: image-taxonomify
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package LearnDash Image Taxonomify
 */

if ( ! defined( 'ABSPATH' ) ) exit;
		
// Check if LearnDash is active. If not, deactivate...
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if( !is_plugin_active('sfwd-lms/sfwd_lms.php' ) ) {
    add_action( 'admin_init', 'trit_deactivate' );
    add_action( 'admin_notices', 'trit_admin_notice' );
    function trit_deactivate() {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
    // Notice
    function trit_admin_notice() { ?>
        <div class="notice notice-error is-dismissible">
            <p>
                <strong>
                    <?php echo esc_html_e( 'LearnDash LMS is not active: IMAGE TAXONOMIFY FOR LEARNDASH needs it, that\'s why was deactivated', 'image-taxonomify' ); ?>
                </strong>
            </p>
            <button type="button" class="notice-dismiss">
                <span class="screen-reader-text">
                    Dismiss this notice.
                </span>
            </button>
        </div><?php
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] ); 
        }        
    }
}

require_once('admin/trit-settings.php');
require_once('includes/functions.php');
require_once('includes/custom-taxonomy.php');

//ADMIN CSS
function trit_enqueue_admin_script( $hook ) {
    global $trit_settings_page;
	if( $hook != $trit_settings_page ) {
        return;
    }
    wp_enqueue_style('trit_admin_style', plugins_url('assets/css/trit_admin.css',__FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'trit_enqueue_admin_script' );


//FRONTEND CSS (only for BuddyBoss or pages with ld course list shortcode) - copied and adapted from learndash
function trit_enqueue_script() {
	global $post, $ld_course_grid_assets_needed;
    wp_register_style('trit_style', plugins_url('assets/css/trit.css',__FILE__ ));
    if (is_buddyboss_theme()) { 
        wp_enqueue_style('trit_style');
    }
	if (  ( is_a( $post, 'WP_Post' ) && ( preg_match( '/(\[ld_\w+_list)/', $post->post_content ) 
            || preg_match( '/wp:learndash\/ld-course-list/', $post->post_content ) ) 
          )
		  || ( isset( $ld_course_grid_assets_needed ) && $ld_course_grid_assets_needed === true )
	    ) {
        wp_enqueue_style('trit_style');        
    }
}
add_action( 'wp_enqueue_scripts', 'trit_enqueue_script' );


define("TRIT_WHICH_TAXONOMY", get_option('trit_which_taxonomy'));
define("TRIT_POSITION", get_option('trit_position'));
define("TRIT_CUSTOM_TEXT", get_option('trit_custom_text'));
define("TRIT_WHO_CAN_SEE", get_option('trit_who_can_see'));
define("TRIT_COLOR", get_option('trit_color'));
define("TRIT_BACKGROUND_COLOR", get_option('trit_background_color'));
define("TRIT_FONT_SIZE", get_option('trit_font_size'));
define("TRIT_UPPERCASE", get_option('trit_uppercase'));


add_filter(
    'learndash_course_grid_html_output', 
    'trit_image_grid',
    999,
    4
);