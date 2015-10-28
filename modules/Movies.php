<?php
/**
 * User: adam
 * Date: 27/10/15 16:12
 * Description: Hook callbacks for everything related to the Movie CPT
 */

namespace MoxieMovies\Modules;


class Movies
{
    const MOVIE_SLUG = 'movie';

    const POSTER_URL_SLUG = 'mm_poster_url';

    CONST RATING_SLUG = 'mm_rating';

    const YEAR_SLUG = 'mm_year';

    public static function init()
    {
        //Hooks
        add_action( 'init',             array(__CLASS__, 'register_post_types') );
        add_action( 'add_meta_boxes',   array(__CLASS__,'add_meta_boxes'), 10, 2 );
        add_action( 'save_post',        array(__CLASS__, 'save_post_data' ) );
    }

    public static function register_post_types()
    {
        register_post_type( self::MOVIE_SLUG,
            array(
                'labels' => array(
                    'name' => __( 'Movies', MM_TEXT_DOMAIN ),
                    'singular_name' => __( 'Movie', MM_TEXT_DOMAIN )
                ),
                'public' => true,
                'has_archive' => true
            )
        );
    }

    public static function add_meta_boxes( $post_type, $post )
    {
        if (!$post_type == self::MOVIE_SLUG)
            return;

        add_meta_box(
            'movie-meta',
            __('Movie Info', MM_TEXT_DOMAIN),
            array(__CLASS__, 'render_meta_box'),
            self::MOVIE_SLUG,
            'side',
            'high'
        );
    }

    public static function render_meta_box( $post )
    {
        wp_nonce_field( 'update_movie_meta', 'post_' . $post->ID );

        ?>
        <p>
            <label for="<?php echo self::POSTER_URL_SLUG ?>"><?php _e( 'Poster URL', MM_TEXT_DOMAIN ) ?></label><br />
            <input type="url"
                   name="<?php echo self::POSTER_URL_SLUG ?>"
                   id="<?php echo self::POSTER_URL_SLUG ?>"
                   value="<?php echo self::get_poster_url( $post->ID ) ?>"
                   placeholder="http://example.com" />
        </p>

        <p>
            <label for="<?php echo self::RATING_SLUG ?>"><?php _e( 'Rating', MM_TEXT_DOMAIN ) ?></label><br />
            <input type="number"
                   name="<?php echo self::RATING_SLUG ?>"
                   id="<?php echo self::RATING_SLUG ?>"
                   value="<?php echo self::get_rating( $post->ID ) ?>"
                   placeholder="1"
                   min="1"
                   max="5" />
        </p>

        <p>
            <label for="<?php echo self::YEAR_SLUG ?>"><?php _e( 'Year of Release', MM_TEXT_DOMAIN ) ?></label><br />
            <input type="number"
                   name="<?php echo self::YEAR_SLUG ?>"
                   id="<?php echo self::YEAR_SLUG ?>"
                   value="<?php echo self::get_year( $post->ID ) ?>"
                   placeholder="1950"
                   min="1950"
                   max="2025" />
        </p>
        <?php

        return true;
    }

    public static function save_post_data( $post_id )
    {
        // Check if our nonce is set.
        if ( ! isset( $_POST['post_' . $post_id] ) )
            return $post_id;

        $nonce = $_POST['post_' . $post_id];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'update_movie_meta' ) )
            return $post_id;

        // If this is an autosave, our form has not been submitted so we don't want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) )
            return $post_id;

        // Update the fields.
        self::set_poster_url( $post_id, $_POST[self::POSTER_URL_SLUG] );

        self::set_rating( $post_id, $_POST[self::RATING_SLUG] );

        self::set_year( $post_id, $_POST[self::YEAR_SLUG] );

        return $post_id;
    }

    // Getter/Setter for poster url
    public static function set_poster_url( $post_id, $url )
    {
        if( esc_url($url) == $url )
        {
            return update_post_meta( $post_id, self::POSTER_URL_SLUG, $url );
        }

        return false;
    }

    public static function get_poster_url( $post_id )
    {
        return get_post_meta( $post_id, self::POSTER_URL_SLUG, true );
    }

    // Getter/Setter for rating
    public static function set_rating( $post_id, $rating )
    {
        if( $rating === '' || ( (int)$rating == $rating && $rating >= 1 && $rating <= 5 ) )
        {
            return update_post_meta( $post_id, self::RATING_SLUG, $rating );
        }

        return false;
    }

    public static function get_rating( $post_id )
    {
        return get_post_meta( $post_id, self::RATING_SLUG, true );
    }

    // Getter/Setter for year
    public static function set_year( $post_id, $year )
    {
        if( $year === '' || ( (int)$year == $year && $year >= 1950 && $year <= 2025 ) )
        {
            return update_post_meta( $post_id, self::YEAR_SLUG, $year );
        }

        return false;
    }

    public static function get_year( $post_id )
    {
        return get_post_meta( $post_id, self::YEAR_SLUG, true );
    }
}

Movies::init();