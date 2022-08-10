<?php
/**
 *
 * This class file is for the Next & Previous.
 *
 * @package Woo Next Previous Product
 *  Next & Previous Controller.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wnp {
    public function __construct(){
        add_action( 'wp_footer', array( $this,'wnp_prev_next_product' ) );
        add_action( 'wp_enqueue_scripts', array( $this,'wnp_add_styles' ));
    }
    public function wnp_add_styles(){
        wp_enqueue_style(
            'wnpcss',
            plugin_dir_url( dirname(__FILE__) ) . '/css/wnp.css',
            array(),
            time(),
            false
        );
    }
    public function wnp_prev_next_product() {
        if(is_singular('product')){
            echo "<div class='wnp_navigation_container'>";
                if( $prev_post = $this->wnp_get_adjacent_post('prev', 'product') ): 
                echo'<a href="'.get_permalink( $prev_post->ID ).'" class="wnp_navigation_span wnp_navigation_previous">';
                    $prevpost_img = get_the_post_thumbnail_url( $prev_post->ID, 'thumbnail'); 
                    echo get_the_title(  $prev_post->ID );
                    echo "<img src='".$prevpost_img."' />" ;
                echo'</a>';
                endif; 

                if( $next_post = $this->wnp_get_adjacent_post('next', 'product') ): 
                echo'<a href="'.get_permalink( $next_post->ID ).'" class="wnp_navigation_span wnp_navigation_next">';
                    $nextpost_img = get_the_post_thumbnail_url( $next_post->ID, 'thumbnail');                 
                    echo "<img src='".$nextpost_img."' />" ;
                    echo get_the_title(  $next_post->ID );
                echo'</a>';
                endif; 
            echo'</div>';
        }
    }

    public function wnp_get_adjacent_post($direction = 'prev', $post_types = 'post') {
        global $post, $wpdb;
    
        if(empty($post)) return NULL;
        if(!$post_types) return NULL;
    
        if(is_array($post_types)){
            $txt = '';
            for($i = 0; $i <= count($post_types) - 1; $i++){
                $txt .= "'".$post_types[$i]."'";
                if($i != count($post_types) - 1) $txt .= ', ';
            }
            $post_types = $txt;
        }
    
        $current_post_date = $post->post_date;
    
        $join = '';
        $in_same_cat = FALSE;
        $excluded_categories = '';
        $adjacent = $direction == 'prev' ? 'previous' : 'next';
        $op = $direction == 'prev' ? '<' : '>';
        $order = $direction == 'prev' ? 'DESC' : 'ASC';
    
        $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
        $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type = '".$post_types."' AND p.post_status = 'publish'", $current_post_date), $in_same_cat, $excluded_categories );
        $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );
    
        $query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
        $query_key = 'adjacent_post_' . md5($query);
        $result = wp_cache_get($query_key, 'counts');
        if ( false !== $result )
            return $result;
    
        $result = $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $sort");
        if ( null === $result )
            $result = '';
    
        wp_cache_set($query_key, $result, 'counts');
        return $result;
    }

}

new Wnp();