<?php
/**
 * Plugin Name:       Students Block
 * Description:       A Gutenberg block to show your pride! This block enables you to type text and style it with the color font Gilbert from Type with Pride.
 * Version:           0.1.0
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       students-block
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_students_block_block_init() {
	register_block_type( __DIR__ . '/build', array(

		'render_callback' => 'dynamicblock_renderer',

	) );
}
add_action( 'init', 'create_block_students_block_block_init' );


/**
 * Callback function to display students
 *
 * @return void
 */
function dynamicblock_renderer( $block ){
    
	$students_number = ( isset(  $block[ 'students_count' ] ) ) ?  $block[ 'students_count' ] : -1 ;
    $id = ( isset(  $block[ 'student' ] ) ) ? intval(  $block[ 'student' ] ) : 0 ;
	$onlyActive = ( isset(  $block[ 'student_active' ] ) ) ? intval(  $block[ 'student_active' ] ) : 0 ;
	if( $onlyActive ){
		$query = array(
			'posts_per_page' => $students_number ,
			'post_status' => 'publish,private,draft',
			'post_type' => 'students',
			'p' => $id,
			'meta_query' => array(
					array(
						'key' => "student_active",
						'value' => 'true',
						'compare' => ""
					)
			)
		);
	} else {
		$query = array(
			'posts_per_page' => $students_number ,
			'post_status' => 'publish,private,draft',
			'post_type' => 'students',
			'p' => $id,
		);
	}
    
   
    $posts = new WP_Query( $query );
    $posts = $posts->get_posts();
    $output = "<div id='students-list'>";
    foreach( $posts as $post ){

        $meta = get_post_meta( $post->ID );
        $output.= "Name : ".$post->post_title;
        $output.= get_the_post_thumbnail( $post ). "<br>";
    }

    $output .= "</div>";
    if ( isset( $atts['infinite-scroll'] ) ){
        $output.= '<div style="display:none" id="infinite-scroll"></div>';
        $output.= '<div class="hidden" style="display:none" id="posts_per_page">'.$students_number.'</div>';
    } elseif( isset( $atts['students_count'] ) ){
        $output.= '<div id="more_posts">Load More</div>';
        $output.= '<div class="hidden" style="display:none" id="posts_per_page">'.$students_number.'</div>';
    }
    

    return $output;
}