<?php
/**
 * User: adam
 * Date: 27/10/15 18:27
 * Description: Functions for the JSON API
 */

namespace MoxieMovies\Modules;


class API
{
    const TRANSIENT_MOVIE_LIST = 'moxie_movie_list';

    public static function init()
    {
        //Hooks
        add_action( 'wp_ajax_get_movies',        array(__CLASS__, 'get_movies') );
        add_action( 'wp_ajax_nopriv_get_movies', array(__CLASS__, 'get_movies') );
        add_action( 'publish_movie',             array(__CLASS__, 'clear_cache') );
    }

    /**
     * Return all the movies as JSON (we don't worry about pagination for this example)
     */
    public static function get_movies()
    {
        $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';

        if ( !wp_verify_nonce( $nonce, 'moxie_movies' ) )
            wp_die( 'error' );

        $return = get_transient( self::TRANSIENT_MOVIE_LIST );

        if( $return === false )
        {
            $return = array();

            $movies = get_posts( array(
                'post_type'         => Movies::MOVIE_SLUG,
                'posts_per_page'    => -1
            ));

            foreach( $movies as $movie )
            {
                $return[] = array(
                    'id'                => $movie->ID,
                    'title'             => $movie->post_title,
                    'poster_url'        => Movies::get_poster_url($movie->ID),
                    'rating'            => Movies::get_rating($movie->ID),
                    'year'              => Movies::get_year($movie->ID),
                    'short_description' => $movie->post_content
                );
            }

            //cache as a transient for 1 week
            set_transient( self::TRANSIENT_MOVIE_LIST, $return, 1 * WEEK_IN_SECONDS );
        }

        wp_die( json_encode( $return ) );
    }

    public static function clear_cache()
    {
        delete_transient( self::TRANSIENT_MOVIE_LIST );
    }
}

API::init();