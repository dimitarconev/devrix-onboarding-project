<?php
/**
 * The plugin functions file.
 *
 * This is used to define general functions, shortcodes etc.
 *
 * Important: Always use the `dx_` prefix for function names.
 *
 * @link       http://devrix.com
 * @since      1.0.0
 *
 * @package    DX_Students
 * @subpackage DX_Students/includes
 * @author     DevriX <contact@devrix.com>
 */

add_shortcode('students', 'dx_students_list' );

/**
 * Shortcode function for displaying students list. Can be filtered with students_count param 
 *
 * @param array $atts
 * @return void
 */
function dx_students_list( $atts = [] ){
    
    $students_number = ( isset( $atts['students_count']) ) ? $atts[ 'students_count' ] : -1 ;
    $id = ( isset( $atts['id']) ) ? intval($atts[ 'id' ]) : 0 ;
    $query = array(
        'posts_per_page' => $students_number ,
        'post_status' => 'publish,private,draft',
        'post_type' => 'students',
        'p' => $id,
    );
   
    $posts = new WP_Query( $query );
    $posts = $posts->get_posts();
    $output = "<div id='students-list'>";
    foreach( $posts as $post ){

        $meta = get_post_meta( $post->ID );
        $output.= "Name : ".$post->post_title;
        $output.= get_the_post_thumbnail( $post ). "<br>";
    }

    $output .= "</div>";
    if( isset( $atts['students_count'] ) ){
        $output.= '<div id="more_posts">Load More</div>';
        $output.= '<div class="hidden" style="display:none" id="posts_per_page">'.$students_number.'</div>';
    }
    

    return $output;
}