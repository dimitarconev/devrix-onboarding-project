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

use WP_Query;
use WP_REST_Request;
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
    public static function seed_posts(){

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
            $theme_files = array( 'archive-students.php'  );
            $exists_in_theme = locate_template($theme_files, false);
            if ( $exists_in_theme != '' ) {
              return $exists_in_theme;
            } else {
              return DXS_DIR . '/templates/archive-students.php';
            }
          }

          if ( is_singular ( 'students' )){
            $theme_files = array( 'single-students.php'  );
            $exists_in_theme = locate_template($theme_files, false);
            if ( $exists_in_theme != '' ) {
              return $exists_in_theme;
            } else {
              return DXS_DIR . '/templates/single-students.php';
            }
          }
          return $template;
    }

    public function add_meta_boxes(){

      add_meta_box( 'personal-information', "Personal information", array( $this, 'personal_info_callback' ), 'students', 'side', 'default');
    
    }

    public function personal_info_callback( $post ){
      ?>

      <?php wp_nonce_field( basename( __FILE__ ), 'students_post_class_nonce' ); ?>

      <p>
        <label for="student-post-country"><?php _e( "Lives In" ); ?></label>
        <br />
        <input class="widefat" type="text" name="student_country" id="student-post-country" value="<?php echo esc_attr( get_post_meta( $post->ID, 'student_country', true ) ); ?>" size="30" />
      </p>
      <p>
        <label for="student-post-adress"><?php _e( "Adress" ); ?></label>
        <br />
        <input class="widefat" type="text" name="student_adress" id="student-post-adress" value="<?php echo esc_attr( get_post_meta( $post->ID, 'student_adress', true ) ); ?>" size="30" />
      </p>
      <p>
        <label for="student-post-birth-date"><?php _e( "Birth Date" ); ?></label>
        <br />
        <input class="widefat" type="date" name="student_birth_date" id="student-post-birth-date" value="<?php echo esc_attr( get_post_meta( $post->ID, 'student_birth_date', true ) ); ?>" size="30" />
      </p>
      <p>
        <label for="student-post-active"><?php _e( "Active ?" ); ?></label>
        <br />
        <input class="widefat" type="checkbox" name="student_active" id="student-post-active" value="true" <?php echo  ( ( get_post_meta( $post->ID, 'student_active', true ) == "true" ) ? 'checked' : '' ); ?> size="30" />
      </p>
    <?php 
    }

    public function save_meta_boxes( $post_id, $post ) {

      /* Verify the nonce before proceeding. */
      if ( !isset( $_POST['students_post_class_nonce'] ) || !wp_verify_nonce( $_POST['students_post_class_nonce'], basename( __FILE__ ) ) )
        return $post_id;
    
      /* Get the post type object. */
      $post_type = get_post_type_object( $post->post_type );
    
      /* Check if the current user has permission to edit the post. */
      if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
        return $post_id;
    
      $metaKeys = array(
        'student_country',
        'student_adress',
        'student_birth_date',
        'student_active'
      );

      foreach( $metaKeys as $meta_key ){

        $meta_value = get_post_meta( $post_id, $meta_key, true );
        $new_meta_value = ( isset( $_POST[ $meta_key] ) ? sanitize_text_field( $_POST[ $meta_key ] ) : '' );
        /* If the new meta value does not match the old value, update it. */
        if ( $new_meta_value && $new_meta_value != $meta_value ){
            update_post_meta( $post_id, $meta_key, $new_meta_value );
        } 
      }
    }

    public static function register_settings(){
      register_setting( 'students-cpt-settings', 'student_country' );
      register_setting( 'students-cpt-settings', 'student_adress' );
      register_setting( 'students-cpt-settings', 'student_birth_date' );
      register_setting( 'students-cpt-settings', 'student_active' );

    }

    public function students_option_page(){

      add_menu_page( 'Students', 'Students', 'administrator', __FILE__,  array( $this, 'student_options_callback' ) );
      
    }

    public function student_options_callback(){
      ?>
      <div class="wrap">
      <h1>Student CPT Settings</h1>
      
      <form method="post" action="options.php">
          <?php settings_fields( 'students-cpt-settings' ); ?>
          <?php do_settings_sections( 'students-cpt-settings' ); ?>
          <table class="form-table">
              <tr valign="top">
              <th scope="row">Country: </th>
              <td><input class="widefat" type="checkbox" name="student_country" id="student-post-country" value="true" <?php echo  ( (  esc_attr( get_option('student_country') ) == "true" ) ? 'checked' : '' ); ?> size="30" /> </td>
              </tr>
               
              <tr valign="top">
              <th scope="row">Adress: </th>
              <td><input class="widefat" type="checkbox" name="student_adress" id="student-post-adress" value="true" <?php echo  ( (  esc_attr( get_option('student_adress') ) == "true" ) ? 'checked' : '' ); ?> size="30" /> </td>
              </tr>
              
              <tr valign="top">
              <th scope="row">Birth date: </th>
              <td><input class="widefat" type="checkbox" name="student_birth_date" id="student-post-birth-date" value="true" <?php echo  ( (  esc_attr( get_option('student_birth_date') ) == "true" ) ? 'checked' : '' ); ?> size="30" /> </td>
              </tr>

              <tr valign="top">
              <th scope="row">Active: </th>
              <td><input class="widefat" type="checkbox" name="student_active" id="student-post-active" value="true" <?php echo  ( (  esc_attr( get_option('student_active') ) == "true" ) ? 'checked' : '' ); ?> size="30" /> </td>
              </tr>
          </table>
          
          <?php submit_button(); ?>
      
      </form>
      </div>
      <?php
    }

    public function students_register_rest_routes(){

      register_rest_route( 'dx-students/v1', '/students', array(
        'methods' => 'GET',
        'callback' => array( $this, 'rest_endpoint_students_fetch' ),
      ) );
      register_rest_route( 'dx-students/v1', '/id/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => array( $this, 'rest_endpoint_student_fetch' ),
      ) );
    }

    /**
     * Rest Route fetching single student
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function rest_endpoint_student_fetch( $data) { 
      
      $args = [
          'posts_per_page' => -1,
          'post_type' => 'students',
          'post_status' => "publish,private,draft",
          'p' => $data['id']
      ];
  
      $posts = new WP_Query($args);
      $response_data = array();
      foreach( $posts->posts as $post ){
        $temp = array();
        $meta = get_post_meta( $post->ID );
        $temp['name'] = $post->post_title;
        $temp['student_birth_date'] = ( isset( $meta['student_birth_date'][0]) ) ? $meta['student_birth_date'][0] : "";
        $temp['student_birth_date'] = ( isset( $meta['student_adress'][0]) ) ? $meta['student_adress'][0] : "";
        $temp['student_birth_date'] = ( isset( $meta['student_active'][0]) ) ? $meta['student_active'][0] : "";
        $temp['student_birth_date'] = ( isset( $meta['student_country'][0]) ) ? $meta['student_country'][0] : "";
        $response_data[] = $temp;
      }
         
  
      return json_encode( $response_data );
  }
    
    /**
     * REST API endopoint function callback
     *
     * @param WP_REST_Request $request
     * @return void
     */
    public function rest_endpoint_students_fetch( WP_REST_Request $request) { 
  
      $args = [
          'posts_per_page' => -1,
          'post_type' => 'students',
          'post_status' => "publish,private,draft",
      ];
  
      $posts = new WP_Query($args);
      $response_data = array();
      foreach( $posts->posts as $post ){
        $temp = array();
        $meta = get_post_meta( $post->ID );
        $temp['name'] = $post->post_title;
        $temp['student_birth_date'] = ( isset( $meta['student_birth_date'][0]) ) ? $meta['student_birth_date'][0] : "";
        $temp['student_birth_date'] = ( isset( $meta['student_adress'][0]) ) ? $meta['student_adress'][0] : "";
        $temp['student_birth_date'] = ( isset( $meta['student_active'][0]) ) ? $meta['student_active'][0] : "";
        $temp['student_birth_date'] = ( isset( $meta['student_country'][0]) ) ? $meta['student_country'][0] : "";
        $response_data[] = $temp;
      }
         
  
      return json_encode( $response_data );
  }
}
