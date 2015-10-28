<?php
/**
 * User: adam
 * Date: 27/10/15 18:35
 * Description:
 */

namespace MoxieMovies\Modules;


class FrontPage
{
    public static function init()
    {
        //Hooks
        add_filter( 'the_content',  array(__CLASS__, 'show_movies') );
    }

    public static function show_movies( $content )
    {
        if( !is_front_page() )
            return $content;

        return '<div ng-app="moxiemovies"><show-movies></show-movies></div>';
    }
}

FrontPage::init();