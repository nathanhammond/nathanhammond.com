<?php
/*
Plugin Name: Disable URL Autocorrect Guessing
Description: Disables Wordpress' URL autocorrection guessing feature. If you for example enter the URL http://www.myblog.com/proj you won't be redirected to http://www.myblog.com/project-2013 anymore.
Author: Hauke Pribnow (based on comment of nacin)
Version: 1.0
*/

// This code is based on a comment of nacin here: https://core.trac.wordpress.org/ticket/16557

function remove_redirect_guess_404_permalink( $redirect_url ) {
	if ( is_404() )
		return false;
	return $redirect_url;
}

add_filter( 'redirect_canonical', 'remove_redirect_guess_404_permalink' );
