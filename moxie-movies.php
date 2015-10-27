<?php
/*
Plugin Name: Moxie Movies
Description: Store movie data in WordPress and display it on your homepage.
Version: 0.1.0
Author: Adam Fenton
Author URI: https://solnamic.com
License: GPLv2 or later
Text Domain: moxie-movies
*/


define( 'MM_TEXT_DOMAIN', 'moxie-movies' );

define( 'MM_ROOT_URI', plugin_dir_url(__FILE__) );

include_once( 'modules/Main.php' );

\MoxieMovies\Modules\Main::init();