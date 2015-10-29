<?php

use \MoxieMovies\Modules\Movies as Movies;


class Test_Movies extends WP_UnitTestCase
{
    /**
     * register_post_types()
     */
    public function test_register_post_types()
    {
        Movies::register_post_types();

        $this->assertContains( 'movie', get_post_types() );
    }


    /**
     * add_meta_boxes()
     */
    public function test_add_meta_boxes()
    {
        global $wp_meta_boxes;

        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $post = get_post( $post_id );

        Movies::add_meta_boxes( $post->post_type, $post );

        $this->assertArrayHasKey( 'movie-meta', $wp_meta_boxes['movie']['side']['high'] );
    }


    /**
     * render_meta_box()
     */
    public function test_render_meta_box()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $post = get_post( $post_id );

        ob_start();

        $this->assertTrue( Movies::render_meta_box($post) );

        ob_clean();
    }


    /**
     * set_poster_url()
     */
    public function test_set_poster_url_valid()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertGreaterThan( 0, Movies::set_poster_url( $post_id, 'https://example.com' ) );

        $this->assertEquals( 'https://example.com', get_post_meta($post_id, Movies::POSTER_URL_SLUG, true) );
    }

    public function test_set_poster_url_invalid()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_poster_url( $post_id, 'htt://example.com' ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::POSTER_URL_SLUG, true) );
    }

    public function test_set_poster_url_blank()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertGreaterThan( 0, Movies::set_poster_url( $post_id, '' ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::POSTER_URL_SLUG, true) );
    }


    /**
     * get_poster_url()
     */
    public function test_get_poster_url()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        update_post_meta( $post_id, Movies::POSTER_URL_SLUG, 'https://example.com' );

        $this->assertEquals( 'https://example.com', Movies::get_poster_url($post_id) );
    }


    /**
     * set_rating()
     */
    public function test_set_rating_valid()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertGreaterThan( 0, Movies::set_rating( $post_id, 3 ) );

        $this->assertEquals( 3, get_post_meta($post_id, Movies::RATING_SLUG, true) );
    }

    public function test_set_rating_too_high()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_rating( $post_id, 6 ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::RATING_SLUG, true) );
    }

    public function test_set_rating_too_low()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_rating( $post_id, 0 ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::RATING_SLUG, true) );
    }

    public function test_set_rating_not_number()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_rating( $post_id, 'III' ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::RATING_SLUG, true) );
    }

    public function test_set_rating_not_integer()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_rating( $post_id, 3.4 ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::RATING_SLUG, true) );
    }

    public function test_set_rating_blank()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertGreaterThan( 0, Movies::set_rating( $post_id, '' ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::RATING_SLUG, true) );
    }


    /**
     * get_rating()
     */
    public function test_get_rating()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        update_post_meta( $post_id, Movies::RATING_SLUG, 3 );

        $this->assertEquals( 3, Movies::get_rating($post_id) );
    }


    /**
     * set_year()
     */
    public function test_set_year_valid()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertGreaterThan( 0, Movies::set_year( $post_id, 1999 ) );

        $this->assertEquals( 1999, get_post_meta($post_id, Movies::YEAR_SLUG, true) );
    }

    public function test_set_year_too_high()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_year( $post_id, 2026 ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::YEAR_SLUG, true) );
    }

    public function test_set_year_too_low()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_year( $post_id, 1949 ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::YEAR_SLUG, true) );
    }

    public function test_set_year_not_number()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_year( $post_id, 'MCXI' ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::YEAR_SLUG, true) );
    }

    public function test_set_year_not_integer()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertFalse( Movies::set_year( $post_id, 2020.45 ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::YEAR_SLUG, true) );
    }

    public function test_set_year_blank()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        $this->assertGreaterThan( 0, Movies::set_year( $post_id, '' ) );

        $this->assertEquals( '', get_post_meta($post_id, Movies::YEAR_SLUG, true) );
    }


    /**
     * get_year()
     */
    public function test_get_year()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'movie',
            'post_title'    => 'Test',
            'post_content'  => 'content...'
        ));

        update_post_meta( $post_id, Movies::YEAR_SLUG, 2020 );

        $this->assertEquals( 2020, Movies::get_year($post_id) );
    }
}