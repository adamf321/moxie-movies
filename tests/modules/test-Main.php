<?php

use \MoxieMovies\Modules\Main as Main;


class Test_Main extends WP_UnitTestCase
{
    /**
     * enqueue_scripts()
     */
    public function test_enqueue_scripts_style()
    {
        Main::enqueue_scripts();

        $this->assertTrue( wp_style_is( 'moxie-movies-style' ) );
    }

    public function test_enqueue_scripts_angularjs()
    {
        Main::enqueue_scripts();

        $this->assertTrue( wp_script_is( 'angularjs' ) );
    }

    public function test_enqueue_scripts_app()
    {
        Main::enqueue_scripts();

        $this->assertTrue( wp_script_is( 'moxie-movies-app' ) );
    }
}