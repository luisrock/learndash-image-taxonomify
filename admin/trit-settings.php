<?php

function trit_admin_menu() {
    global $trit_settings_page;
    $trit_settings_page = add_submenu_page(
                            'learndash-lms', //The slug name for the parent menu
                            __( 'Image Taxonomify', 'image-taxonomify' ), //Page title
                            __( 'Image Taxonomify', 'image-taxonomify' ), //Menu title
                            'manage_options', //capability
                            'learndash-image-taxonomify', //menu slug 
                            'trit_admin_page' //function to output the content
                        );
}
add_action( 'admin_menu', 'trit_admin_menu' );

function trit_register_plugin_settings() {
    //register our settings
    register_setting( 'trit-settings-group', 'trit_which_taxonomy' );
    register_setting( 'trit-settings-group', 'trit_position' );
    register_setting( 'trit-settings-group', 'trit_custom_text' );
    register_setting( 'trit-settings-group', 'trit_color' );
    register_setting( 'trit-settings-group', 'trit_background_color' );
    register_setting( 'trit-settings-group', 'trit_font_size' );
    register_setting( 'trit-settings-group', 'trit_uppercase' );
    register_setting( 'trit-settings-group', 'trit_who_can_see' );
}
//call register settings function
add_action( 'admin_init', 'trit_register_plugin_settings' );


function trit_admin_page() {
?>

<div class="trit-head-panel">
    <h1><?php esc_html_e( 'Image Taxonomify for Learndash', 'image-taxonomify' ); ?></h1>
    <p><?php esc_html_e( 'Place a text box containing taxonomy term (category,tag,etc) on top of your LearnDash course image grid', 'image-taxonomify' ); ?></p>
</div>

<div class="wrap trit-wrap-grid">

    <form method="post" action="options.php">

        <?php settings_fields( 'trit-settings-group' ); ?>
        <?php do_settings_sections( 'trit-settings-group' ); ?>

        <div class="trit-form-fields">


            <div class="trit-settings-title">
                <?php esc_html_e( 'Image Taxonomify - Settings', 'image-taxonomify' ); ?>
            </div>

            <div class="trit-form-fields-label">
                <?php esc_html_e( 'Select which taxonomy term to show on top of the course image', 'image-taxonomify' ); ?>
                <span>* <?php esc_html_e( 'the first term of the selected taxonomy will be displayed. Suggestion: select "ld_course_box", a custom taxonomy for LearnDash courses created specifically for this feature. Then, on each course edit page, add only one term on the "Course Box Text" field, so you know exactly what will be displayed.', 'image-taxonomify' ); ?></span>
            </div>
            <div class="trit-form-fields-group">
                <div class="trit-form-div-select">
                    <label>
                        <select name="<?php echo esc_attr( 'trit_which_taxonomy' ); ?>">
                            <?php foreach(get_object_taxonomies( 'sfwd-courses') as $tax) { ?>
                                <option value="<?php echo esc_attr($tax); ?>"
                                        <?php selected($tax, get_option('trit_which_taxonomy'), true); ?>>
                                    <?php echo esc_html($tax); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </label>
                </div>
            </div>
            <hr>

            <div class="trit-form-fields-label">
                <?php esc_html_e( 'Select the block text position on the course image', 'image-taxonomify' ); ?>
                <span>* <?php esc_html_e( 'avoid conflict by checking any elements (ribbons, badges or others placed by your theme) that already exist on top of the image', 'image-taxonomify' ); ?></span>
            </div>
            <div class="trit-form-fields-group">
                <div class="trit-form-div-select">
                    <label>
                        <select name="<?php echo esc_attr( 'trit_position' ); ?>">
                            <option value="tl"
                                    <?php selected("tl", get_option('trit_position'), true); ?>>
                                <?php esc_html_e( 'Top Left', 'image-taxonomify' ); ?>
                            </option>
                            <option value="tr"
                                    <?php selected("tr", get_option('trit_position'), true); ?>>
                                <?php esc_html_e( 'Top Right', 'image-taxonomify' ); ?>
                            </option>
                            <option value="bl"
                                    <?php selected("bl", get_option('trit_position'), true); ?>>
                                <?php esc_html_e( 'Bottom Left', 'image-taxonomify' ); ?>
                            </option>
                            <option value="br"
                                    <?php selected("br", get_option('trit_position'), true); ?>>
                                <?php esc_html_e( 'Bottom Right', 'image-taxonomify' ); ?>
                            </option>
                        </select>
                    </label>
                </div>
            </div>
            <hr>

            <div class="trit-form-fields-label">
                <?php esc_html_e( 'Custom text if/when there is no taxonomy term for the course', 'image-taxonomify' ); ?>
                <span>* <?php esc_html_e( 'if empty, nothing will be displayed', 'image-taxonomify' ); ?></span>
            </div>
            <div class="trit-form-fields-group">
                <input  type="text" 
                        value="<?php echo esc_attr( get_option('trit_custom_text') ); ?>"
                        name="<?php echo esc_attr( 'trit_custom_text' ); ?>">
            </div>
            <hr>

            <div class="trit-form-fields-label">
                <?php esc_html_e( 'Styling (inline)', 'image-taxonomify' ); ?>
                <span>* <?php esc_html_e( 'placeholders indicate defaults', 'image-taxonomify' ); ?></span>
            </div>
            <div class="trit-form-style-fields">

                <div>
                    <div class="trit-form-fields-label">    
                        <?php esc_html_e( 'Text color (CSS)', 'image-taxonomify' ); ?>
                    </div>
                    <div class="trit-form-fields-group">
                        <input  type="text" 
                                placeholder="<?php echo esc_attr( '#fff' ); ?>"
                                value="<?php echo esc_attr( get_option('trit_color') ); ?>"
                                name="<?php echo esc_attr( 'trit_color' ); ?>">
                    </div>
                </div>

                <div>
                    <div class="trit-form-fields-label">
                        <?php esc_html_e( 'Background color (CSS)', 'image-taxonomify' ); ?>
                    </div>
                    <div class="trit-form-fields-group">
                        <input  type="text" 
                                placeholder="<?php echo esc_attr( '#428BCA' ); ?>"
                                value="<?php echo esc_attr( get_option('trit_background_color') ); ?>"
                                name="<?php echo esc_attr( 'trit_background_color' ); ?>">
                    </div>    
                </div>

                <div>
                    <div class="trit-form-fields-label">
                        <?php esc_html_e( 'Font size (CSS)', 'image-taxonomify' ); ?>
                    </div>
                    <div class="trit-form-fields-group">
                        <input  type="text" 
                                placeholder="<?php echo esc_attr( '13px' ); ?>"
                                value="<?php echo esc_attr( get_option('trit_font_size') ); ?>"
                                name="<?php echo esc_attr( 'trit_font_size' ); ?>">
                    </div>    
                </div>

                <div>
                    <div class="trit-form-fields-group">
                        <div class="trit-form-div-checkbox">
                            <label>
                                <input class="trit-checkbox" type="checkbox" name="<?php echo esc_attr( 'trit_uppercase' ); ?>"
                                    value="1" <?php checked(1, get_option('trit_uppercase'), true); ?> />
                                <span class="trit-form-fields-label">
                                    <?php esc_html_e( 'Uppercase', 'image-taxonomify' ); ?>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

            </div>
            <hr>
            
            <div class="trit-form-fields-label">
                <?php esc_html_e( 'Who can see?', 'image-taxonomify' ); ?>
            </div>
            <div class="trit-form-fields-group">
                <div class="trit-form-div-select">
                    <label>
                        <select name="<?php echo esc_attr( 'trit_who_can_see' ); ?>">
                            <option value="trit_who_all"
                                <?php selected("trit_who_all", get_option('trit_who_can_see'), true); ?>>
                                <?php esc_html_e( 'All', 'image-taxonomify' ); ?>
                            </option>
                            <option value="trit_who_visitors"
                                <?php selected("trit_who_visitors", get_option('trit_who_can_see'), true); ?>>
                                <?php esc_html_e( 'Visitors only (non logged)', 'image-taxonomify' ); ?>
                            </option>
                            <option value="trit_who_logged"
                                <?php selected("trit_who_logged", get_option('trit_who_can_see'), true); ?>>
                                <?php esc_html_e( 'Logged users only', 'image-taxonomify' ); ?>
                            </option>
                        </select>
                </div>
            </div>
            <hr>


            <?php submit_button(); ?>

            <div style="float:right; margin-bottom:20px">
              Contact Luis Rock, the author, at 
              <a href="mailto:lurockwp@gmail.com">
                lurockwp@gmail.com
              </a>
            </div>

        </div> <!-- end form fields -->
    </form>
</div> <!-- end trit-wrap-grid -->
<?php } ?>