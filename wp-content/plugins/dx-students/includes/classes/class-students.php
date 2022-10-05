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
        <input class="widefat" type="text" name="student_birth_date" id="student-post-birth-date" value="<?php echo esc_attr( get_post_meta( $post->ID, 'student_birth_date', true ) ); ?>" size="30" />
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
        if ( $new_meta_value  == $meta_value )
          add_post_meta( $post_id, $meta_key, $new_meta_value, true );
        /* If the new meta value does not match the old value, update it. */
        elseif ( $new_meta_value && $new_meta_value != $meta_value )
          update_post_meta( $post_id, $meta_key, $new_meta_value );
      
        /* If there is no new meta value but an old value exists, delete it. */
        elseif ( '' == $new_meta_value && $meta_value )
          delete_post_meta( $post_id, $meta_key, $meta_value );
      }
    }
}
