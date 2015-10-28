<?php

namespace MoxieMovies\Modules;


class Main
{
    public static function init()
    {
        //Init modules
        foreach( glob(dirname(__FILE__)."/*.php") as $filename )
        {
            include_once( $filename );
        }

        //Hooks
        add_action( 'wp_enqueue_scripts', array(__CLASS__, 'enqueue_scripts') );
    }

    public static function enqueue_scripts()
    {
        //Styles
        wp_enqueue_style(
            'moxie-movies-style',
            MM_ROOT_URI . 'styles/moxie-movies.css'
        );

        //Lib
        wp_enqueue_script(
            'angularjs',
            '//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js',
            array( 'jquery' ),
            null,
            true
        );

        //App
        wp_enqueue_script(
            'moxie-movies-app',
            MM_ROOT_URI . 'app/bundle.js',
            array( 'angularjs' ),
            null,
            true
        );
    }
}