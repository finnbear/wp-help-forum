<?php
   /*
   Plugin Name: Help Forum
   Plugin URI: https://github.com/finnbear/wp-help-forum
   Version: 1.0
   Author: Finn Bear
   Author URI: https://www.finnbear.com
   */

  defined( 'ABSPATH' ) or die( 'HelpForum not loaded by WordPress.' );

  function generateRandomString($length = 6) {
     $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $charactersLength = strlen($characters);
     $randomString = '';
     for ($i = 0; $i < $length; $i++) {
       $randomString .= $characters[rand(0, $charactersLength - 1)];
     }
     return $randomString;
   }

   function help_forum_activate() {
     global $wpdb;

     $table_name = $wpdb->prefix . "helpforum";

     $sql = "CREATE TABLE $table_name (
       id INTEGER NOT NULL AUTO_INCREMENT,
       dateCreated DATETIME NOT NULL,
       title VARCHAR(51) NOT NULL,
       beneficiary VARCHAR(101) NOT NULL,
       need VARCHAR(501) NOT NULL,
       contact VARCHAR(101) NOT NULL,
       code VARCHAR(7) UNIQUE NOT NULL,
       views INTEGER NOT NULL DEFAULT 0,
       PRIMARY KEY  (id)
     ) $charset_collate;";

     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     dbDelta( $sql );
   }

   function help_forum_deactivate() {
     global $wpdb;

     $table_name = $wpdb->prefix . 'helpforum';

     $sql = 'DROP TABLE IF EXISTS ' . $table_name . ';';
     $wpdb->query($sql);
   }

   function help_forum_uninstall() {

   }

   function help_forum_form() {
     $args = array();

     echo '<h2>Create Need</h2>';
     echo '<form action"' . $_SERVER['REQUEST_URI'] . '" method="post">'
?>
	<div>
		<label for="title"><strong>Title</strong></label>
		<input type="text" name="title" maxlength="50">
	</div>
	<div>
		<label for="beneficiary"><strong>Beneficiary</strong></label>
		<input type="text" name="beneficiary" maxlength="100">
	</div>
	<div>
		<label for="need"><strong>Need</strong></label>
		<input type="text" name="need" maxlength="500">
	</div>
	<div>
		<label for="contact"><strong>Contact</strong></label>
		<input type="text" name="contact" maxlength="100">
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
       $beneficiary = sanitize_text_field( $_POST['beneficiary'] );
       $need = sanitize_text_field( $_POST['need'] );
       $contact = sanitize_text_field( $_POST['contact'] );

       $table_name = $wpdb->prefix . 'helpforum';

       $code = generateRandomString();
       echo $code;

       $wpdb->insert(
         $table_name,
         array(
           'dateCreated' => current_time('mysql'),
           'title' => $title,
           'beneficiary' => $beneficiary,
           'need' => $need,
           'contact' => $contact,
           'code' => $code,
         )
       );

       echo '<p><strong>Posted.</strong></p>';
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

     $sql = 'SELECT id, dateCreated, title, beneficiary, need FROM ' . $table_name . ';';
     $rows = $wpdb->get_results( $sql );

     echo '<h2>View Needs</h2>';

     if ( sizeof($rows) > 0 ) {
       echo '<table><thead><tr><th>Date</th><th>Title</th><th>Beneficiary</th><th>Need</th></tr></thead><tbody>';

       foreach ( $rows as $row ) {
         echo '<tr><td>' . $row->dateCreated . '</td><td>' . $row->title . '</td><td>' . $row->beneficiary . '</td><td>' . $row->need . '</td></tr>';
       }

       echo '</tbody></table>';
     } else {
       echo 'No needs found.';
     }
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
