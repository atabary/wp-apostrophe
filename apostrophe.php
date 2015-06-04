<?php
/*
  Plugin Name: Apostrophe
  Plugin URI: https://github.com/atabary/wp-apostrophe
  Description: WordPress plugin that rewrites apostrophe (single quote) as hyphen when slugifying titles.
  Author: Alexis Tabary
  Version: 1.0
  Author URI: http://atabary.github.io
 */


// Filter that replace single quotes by hyphens
// --------------------------------------------

function single_quote_to_hyphen($title) {
    return str_replace('\'', '-', $title);
}
add_filter('sanitize_title', 'single_quote_to_hyphen', 1);


// Menu option to regenerate all post names
// ----------------------------------------

register_activation_hook(__FILE__, 'apostrophe_install');
register_deactivation_hook(__FILE__, 'apostrophe_uninstall');
add_action('admin_menu', 'apostrophe_create_settings_page');

function apostrophe_install() {}

function apostrophe_uninstall() {}

function apostrophe_create_settings_page() {
  add_management_page('Apostrophe', 'Apostrophe', 'manage_options', __FILE__, 'apostrophe_admin_page');
}

function apostrophe_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Insufficient permissions!');
    }
    else {
        ?>
        <div class="wrap">
            <h1>Apostrophe: regenerate post names</h1>
            <p>Using this function will simply regenerate all post names (aka slugs) of all posts in the database.</p>
            <p>
                <form action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post">
                    <input name="apostrophe-clicked" type="hidden" value="1" />
                    <input type="submit" value="Regenerate Post Names" />
                </form>
            </p>
        </div>
        <?php
        if (isset($_POST['apostrophe-clicked'])) {
            $query = new WP_Query('posts_per_page=-1');
            $update_count = 0;
            foreach ($query->posts as $post) {
                $post_to_update = array();
                $post_to_update['ID'] = $post->ID;
                $post_to_update['post_name'] = sanitize_title($post->post_title);
                wp_update_post($post_to_update);
                $update_count += 1;
            }
            echo '<p><strong>Post names successfully updated for ' . $update_count . ' posts</strong></p>';
        }
    }
}
?>
