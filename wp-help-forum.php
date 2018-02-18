<?php
   /*
   Plugin Name: Help Forum
   Plugin URI: https://github.com/finnbear/wp-help-forum
   Version: 1.0
   Author: Finn Bear
   Author URI: https://www.finnbear.com
   */

   defined( 'ABSPATH' ) or die( 'HelpForum not loaded by WordPress.' );

   function help_forum_activate() {
     global $wpdb;

     $table_name = $wpdb->prefix . "helpforum";

     $sql = "CREATE TABLE $table_name (
       id INTEGER NOT NULL AUTO_INCREMENT,
       dateCreated DATETIME NOT NULL,
       title VARCHAR(64) NOT NULL,
       description VARCHAR(512) NOT NULL,
       views INTEGER NOT NULL DEFAULT 0,
       PRIMARY KEY  (id)
     ) $charset_collate;";

     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $sql );
   }

   function help_forum_deactivate() {

   }

   function help_forum_uninstall() {

   }

   function help_forum_form() {
     $args = array();

     echo '<form action"' . $_SERVER['REQUEST_URI'] . '" method="post">'
?>
	<div>
		<label for="title"><strong>Title</strong></label>
		<input type="text" name="title">
	</div>
	<div>
		<label for="description"><strong>Description</strong></label>
		<input type="text" name="description">
	</div>
	<br>
	<input type="submit" name="submit" value="Submit">
</form>
<?php
   }

   function help_forum_form_handler() {
     global $wpdb;

     if ( isset( $_POST['submit'] ) ) {
       $title = sanitize_text_field( $_POST['title'] );
       $description = sanitize_text_field( $_POST['description'] );

       $table_name = $wpdb->prefix . 'helpforum';

       $wpdb->insert(
         $table_name,
         array(
           'dateCreated' => current_time('mysql'),
           'title' => $title,
           'description' => $description,
         )
       );

       echo '<p><strong>Posted.</strong></p>';
       echo '<p> Title: ' . $title . '</p>';
       echo '<p> Description: ' . $description . '</p>';
     } else {
       help_forum_form();
     }
   }

   function help_forum_form_shortcode() {
     ob_start();
     help_forum_form_handler();
     return ob_get_clean();
   }

   function help_forum_list() {
     global $wpdb;

     $table_name = $wpdb->prefix . 'helpforum';

     $sql = 'SELECT id, title, description FROM ' . $table_name . ';';
     $rows = $wpdb->get_results( $sql );

     echo '<table><thead><tr><th>#</th><th>Title</th><th>Description</th></tr></thead><tbody>';

     foreach ( $rows as $row ) {
       echo '<tr><td>' . $row->id . '</td><td>' . $row->title . '</td><td>' . $row->description . '</td></tr>';
     }

     echo '</tbody></table>';
   }

   function help_forum_list_handler() {
	help_forum_list();
   }

   function help_forum_list_shortcode() {
     ob_start();
     help_forum_list_handler();
     return ob_get_clean();
   }

   register_activation_hook(__FILE__, 'help_forum_activate');
   register_deactivation_hook(__FILE__, 'help_forum_deactivate');
   register_uninstall_hook(__FILE__, 'help_forum_uninstall');

   add_shortcode('help_forum_form', 'help_forum_form_shortcode');
   add_shortcode('help_forum_list', 'help_forum_list_shortcode');
?>
