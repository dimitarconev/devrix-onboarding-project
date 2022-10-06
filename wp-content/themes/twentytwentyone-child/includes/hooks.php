<?php

class Hooks{
    
    public function __construct(){

        add_filter( 'custom_the_content', array( $this, 'singular_posts_filter' ), 10, 2);
        add_filter( 'the_content', array( $this, 'filter_two' ), 10, 1);
        add_filter( 'the_content', array( $this, 'filter_one' ), 9, 1);
        add_filter( 'the_content', array( $this, 'filter_three' ), 11, 1);
        add_filter( 'wp_nav_menu_items', array( $this, 'add_menu_items' ), 10, 2 );
        add_action( 'profile_update', array( $this, 'profile_update' ), 10, 1 );
        add_action( "template_the_content", array( $this, 'template_page_content'), 10, 1 );
    }

    function template_page_content( $content ){
       
       echo $content;
    }

    function profile_update($user) {
        wp_mail( "dtsonev@devrix.com", 'Profile Update', 'Profile update of user'. $user->user_nicename.' has been updated');
    }

    public function singular_posts_filter( $content, $arg1 ){

        if ( is_single() ){
            $content .= _e( $arg1 , 'twentytwentyone-child' );;
        }

        return $content;
    }

    public function filter_two( $content ){

        $content .= "<div>Two</div>";
        return $content;
    }

    public function filter_one( $content ){

        $content .= "<div>One</div>";
        return $content;
    }

    public function filter_three( $content ){

        $content .= "<div>Three</div>";
        return $content;
    }

    public function add_menu_items( $items, $args ){
        if ( is_user_logged_in() ){
            $items .=  '<li class="menu-item "><a href="'.get_edit_user_link().'" >Profile Page</a></li>';
        }
        return $items;

    }

}

$hooks = new Hooks();