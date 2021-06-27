<?php
function trit_register_taxonomy_ld_course_box() {

    /**
     * Taxonomy: Course Box Text.
     */

    $labels = [
        "name" => __( "Course Box Text", "image-taxonomify" ),
        "singular_name" => __( "Course Box Text", "image-taxonomify" ),
    ];


    $args = [
        "label" => __( "Course Box Text", "image-taxonomify" ),
        "labels" => $labels,
        "public" => true,
        "publicly_queryable" => true,
        "hierarchical" => false,
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => [ 'slug' => 'ld_course_box', 'with_front' => true, ],
        "show_admin_column" => false,
        "show_in_rest" => true,
        "rest_base" => "ld_course_box",
        "rest_controller_class" => "WP_REST_Terms_Controller",
        "show_in_quick_edit" => false,
        "show_in_graphql" => false,
    ];
    register_taxonomy( "ld_course_box", [ "sfwd-courses" ], $args );
}
add_action( 'init', 'trit_register_taxonomy_ld_course_box' );
