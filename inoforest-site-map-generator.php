<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }
/**
 * Plugin Name: Inoforest Site Map Generator
 * Plugin URI: https://github.com/hlaporthein/inoforest-weather
 * Description: Inoforest Site Map Generator 
 * Version: 1.0
 * Author: Hla Por Thein
 * Author URI: http://hlaporthein.me
 * License: GPL2+
 * Text Domain: vd
 */


// add_action( 'wp',    '_inoforest_site_map_generator' );

function _inoforest_site_map_generator() {

   if ( current_user_can('administrator') && isset($_GET['site_map']) ) {
      
    $the_query = new WP_Query([
        'posts_per_page' => -1,
        'orderby'     => 'modified',
        'post_type'   => ['page'],
        'order'       => 'DESC',
        'post_status' => ['publish', 'inherit'],
        'suppress_filters' => true,
    ]);

    if ( $the_query->have_posts() ) {

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

    while($the_query->have_posts() ) {
       $the_query->the_post();
       global $post;
       $post_id = get_the_ID();
       
       if ( ($post->post_status == "publish") || ($post->post_status == "inherit")) {
          
            $postdate = get_post_modified_time('Y-m-d',false, $post_id); // explode( " ", $post->post_modified );

            $sitemap .= '<url>'.
                        '<loc>' . get_permalink( $post_id ) . '</loc>' .
                        '<lastmod>' . $postdate . '</lastmod>' .
                        '<id>' . $post->ID . '</id>' .
                        '<changefreq>monthly</changefreq>' .
                        '</url>';
       }

      }

    $sitemap .= '</urlset>';

    $fp = fopen( ABSPATH . 'sitemap.xml', 'w' );

    fwrite( $fp, $sitemap );
    fclose( $fp );

   }
   wp_reset_query(); wp_reset_postdata();
   }
}