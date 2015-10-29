<?php

use \MoxieMovies\Modules\FrontPage as FrontPage;


class Test_FrontPage extends WP_UnitTestCase
{
    /**
     * show_movies()
     */
    public function test_show_movies_is_frontpage()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'page',
            'post_title'    => 'Test',
            'post_content'  => 'content...',
            'post_status'   => 'publish'
        ));

        update_option( 'show_on_front', 'page' );

        update_option( 'page_on_front', $post_id );

        $this->go_to( get_permalink($post_id) );

        $this->assertEquals(
            '<div ng-app="moxiemovies"><show-movies></show-movies></div>',
            FrontPage::show_movies( 'content...' )
        );
    }

    public function test_show_movies_not_frontpage()
    {
        $post_id = wp_insert_post( array(
            'post_type'     => 'page',
            'post_title'    => 'Test',
            'post_content'  => 'content...',
            'post_status'   => 'publish'
        ));

        $this->go_to( get_permalink($post_id) );

        $this->assertEquals(
            'content...',
            FrontPage::show_movies( 'content...' )
        );
    }
}