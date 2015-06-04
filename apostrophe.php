<?php
/*
  Plugin Name: Apostrophe
  Plugin URI: https://github.com/atabary/wp-apostrophe
  Description: WordPress plugin that rewrites apostrophe (single quote) as hyphen when slugifying titles.
  Author: Alexis Tabary
  Version: 1.0
  Author URI: http://atabary.github.io
 */

function single_quote_to_hyphen($title) {
    return str_replace('\'', '-', $title);
}
add_filter('sanitize_title', 'single_quote_to_hyphen', 1);

?>
