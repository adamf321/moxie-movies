<?php
/**
 * User: adam
 * Date: 27/10/15 18:27
 * Description: Functions for the JSON API
 */

namespace MoxieMovies\Modules;


class API
{
    public static function init()
    {
        //Hooks
        add_action( 'wp_ajax_get_movies',        array(__CLASS__, 'get_movies') );
        add_action( 'wp_ajax_nopriv_get_movies', array(__CLASS__, 'get_movies') );
    }

    /**
     * Return all the movies as JSON (we don't worry about pagination for this example)
     */
    public static function get_movies()
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

        echo json_encode( $return );

        wp_die();
    }
}

API::init();