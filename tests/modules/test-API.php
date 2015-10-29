<?php

use \MoxieMovies\Modules\API as API;

use \MoxieMovies\Modules\Movies as Movies;


class Test_API extends WP_Ajax_UnitTestCase
{
    /**
     * get_movies()
     */
    public function test_get_movies_invalid_nonce()
    {
        $_GET['nonce'] = 'invalid';

        $this->setExpectedException( 'WPAjaxDieStopException', 'error' );

        $this->_handleAjax( 'get_movies' );
    }

    public function test_get_movies_no_nonce()
    {
        $this->setExpectedException( 'WPAjaxDieStopException', 'error' );

        $this->_handleAjax( 'get_movies' );
    }

    public function test_get_movies_no_movies()
    {
        $_GET['nonce'] = wp_create_nonce( 'moxie_movies' );

        $expected = array();

        $this->setExpectedException( 'WPAjaxDieStopException', json_encode($expected) );

        $this->_handleAjax( 'get_movies' );
    }

    public function test_get_movies_some_movies()
    {
        $expected = array(
            array(
                'id'                => null,
                'title'             => 'Terminator',
                'poster_url'        => 'http://posturl.com',
                'rating'            => '5',
                'year'              => '1984',
                'short_description' => 'The Terminator series is an American science fiction franchise created by James Cameron and Gale Anne Hurd.'
            ),
            array(
                'id'                => null,
                'title'             => 'Spectre',
                'poster_url'        => '',
                'rating'            => '4',
                'year'              => '2015',
                'short_description' => 'The latest Bond movie.'
            )
        );

        foreach( $expected as $key => $movie )
        {
            $post_id = wp_insert_post( array(
                'post_type'     => Movies::MOVIE_SLUG,
                'post_title'    => $movie['title'],
                'post_content'  => $movie['short_description'],
                'post_status'   => 'publish'
            ));

            update_post_meta( $post_id, Movies::POSTER_URL_SLUG, $movie['poster_url'] );
            update_post_meta( $post_id, Movies::RATING_SLUG, $movie['rating'] );
            update_post_meta( $post_id, Movies::YEAR_SLUG, $movie['year'] );

            $expected[$key]['id'] = $post_id;
        }

        usort( $expected, function($a, $b)
        {
            return ($a['id'] > $b['id']) ? -1 : 1;
        });

        $_GET['nonce'] = wp_create_nonce( 'moxie_movies' );

//        try {
//            $this->_handleAjax( 'get_movies' );
//        } catch ( WPAjaxDieStopException $e ) {
//            // We expected this, do nothing.
//        }
//
//        $response = json_decode( $this->_last_response );
//
//        $this->assertEquals( $expected, $response );

        $this->setExpectedException( 'WPAjaxDieStopException', json_encode($expected) );

        $this->_handleAjax( 'get_movies' );
    }


    /**
     * clear_cache()
     */
    public function test_clear_cache()
    {
        set_transient( API::TRANSIENT_MOVIE_LIST, 'sample data', 1 * WEEK_IN_SECONDS );

        $this->assertEquals( 'sample data', get_transient(API::TRANSIENT_MOVIE_LIST) );

        API::clear_cache();

        $this->assertFalse( get_transient(API::TRANSIENT_MOVIE_LIST) );
    }
}