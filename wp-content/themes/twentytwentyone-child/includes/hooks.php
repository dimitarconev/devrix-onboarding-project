<?php

class Hooks{
    
    public function __construct(){

        add_filter( 'the_content', array( $this, 'singularPostsFilter' ), 10, 1);
        add_filter( 'the_content', array( $this, 'filterTwo' ), 10, 1);
        add_filter( 'the_content', array( $this, 'filterOne' ), 9, 1);
        add_filter( 'the_content', array( $this, 'filterThree' ), 11, 1);
        add_filter( 'wp_nav_menu_items', array( $this, 'addMenuItems' ), 10, 2 );
        add_action( 'profile_update', array( $this, 'profileUpdate' ), 10, 1 );

    }

    function profileUpdate($user) {
        wp_mail( "dtsonev@devrix.com", 'Profile Update', 'Profile update of user'. $user->user_nicename.' has been updated');
    }

    public function singularPostsFilter( $content ){

        if ( is_single() ){
            $content .= _e( "This is my filter" );;
        }

        return $content;
    }

    public function filterTwo( $content ){

        $content .= "<div>Two</div>";
        return $content;
    }

    public function filterOne( $content ){

        $content .= "<div>One</div>";
        return $content;
    }

    public function filterThree( $content ){

        $content .= "<div>Three</div>";
        return $content;
    }

    public function addMenuItems( $items, $args ){
        if ( is_user_logged_in() ){
            $items .=  '<li class="menu-item "><a href="'.get_edit_user_link().'" >Profile Page</a></li>';
        }
        return $items;

    }

}

$hooks = new Hooks();