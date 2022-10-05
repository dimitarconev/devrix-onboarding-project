<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link       http://devrix.com
 * @since      1.0.0
 *
 * @package    DX_Students
 * @subpackage DX_Students/includes/classes
 * @author     DevriX <contact@devrix.com>
 */

namespace DXS;

class Students
{

    /**
     * Registering Students CPT
     *
     * @return void
     */
    public static function register_students_type()
    {
        register_post_type(
            'students',
            array(
                'labels' => array(
                    'name' => __('Students'),
                    'singular_name' => __('Student')
                ),
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'students'),
                'show_in_rest' => true,
                'supports'  => array('title', 'excerpt', 'thumbnail', 'editor', '')

            )
        );
    }

    /**
     * Populate some dummy posts
     *
     * @return void
     */
    public static function seedPosts(){

        for ( $i = 1; $i < 4; $i++ ){
            wp_insert_post( array(
                'post_content' => '<h1>Hey, this is the body of the post</h1>',
                'post_title'   => 'Student '.$i,
                'post_excerpt' => 'Student '.$i." excerpt",
                'post_status'  => 'publish',
                'post_type'    => 'students'
            ));
        }

    }
    /**
     * Add limit to posts per page 4
     *
     * @param [type] $query - WP query object
     * @return void
     */
    public function modify_students_query( $query ){

        if( ! is_admin()
            && $query->is_post_type_archive( 'students' )
            && $query->is_main_query() ){
                $query->set( 'posts_per_page', 4 );
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] Theme template
     * @return $template
     */
    public static function students_template( $template ){
       
        if ( is_post_type_archive( 'students' ) ) {
            $theme_files = array( 'archive-students.php' );
            $exists_in_theme = locate_template($theme_files, false);
            if ( $exists_in_theme != '' ) {
              return $exists_in_theme;
            } else {
              return DXS_DIR . '/templates/archive-students.php';
            }
          }
          return $template;
    }
}
