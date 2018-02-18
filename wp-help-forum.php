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
     $characters = '123456789ABCDE';
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
       status INTEGER NOT NULL DEFAULT 0, /* 0=pending, 1=approved, 2=fulfilled, 3=rejected */
       need VARCHAR(50) NOT NULL,
       beneficiary VARCHAR(100) NOT NULL,
       circumstance VARCHAR(500) NOT NULL,
       contactName VARCHAR(100) NOT NULL,
       contactEmail VARCHAR(100),
       contactPhone VARCHAR(20),
       contactCity VARCHAR(50),
       contactState VARCHAR(2),
       contactZip INTEGER,
       contactAddress VARCHAR(100),
       code VARCHAR(6) UNIQUE NOT NULL,
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

     echo '<h2>Submit a Need</h2>';
     echo '<form action"' . $_SERVER['REQUEST_URI'] . '" method="post">'
?>
	<div>
		<label for="need"><strong>What is needed?</strong></label>
		<input type="text" name="need" maxlength="50">
	</div>
	<div>
		<label for="beneficiary"><strong>Who is the beneficiary?</strong></label>
		<input type="text" name="beneficiary" maxlength="100">
	</div>
	<div>
		<label for="circumstance"><strong>What are the circumstances?</strong></label>
		<textarea name="circumstance" maxlength="500"></textarea>
	</div>
	<div>
		<label for="contact"><strong>Contact information</strong></label>
		<input type="text" name="contactName" maxlength="100">
	</div>
	<br>
	<input type="submit" name="submit" value="Submit">
</form>
<?php
   }

   function help_forum_form_handler() {
     global $wpdb;

     if ( isset( $_POST['submit'] ) ) {
       $need = sanitize_text_field( $_POST['need'] );
       $beneficiary = sanitize_text_field( $_POST['beneficiary'] );
       $circumstance = sanitize_text_field( $_POST['circumstance'] );
       $contactName = sanitize_text_field( $_POST['contactName'] );

       $table_name = $wpdb->prefix . 'helpforum';

       $code = generateRandomString();
       echo $code;

       $wpdb->insert(
         $table_name,
         array(
           'dateCreated' => current_time('mysql'),
           'need' => $need,
           'beneficiary' => $beneficiary,
           'circumstance' => $circumstance,
           'contactName' => $contactName,
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

     $sql = 'SELECT id, dateCreated, need, beneficiary, circumstance FROM ' . $table_name . ';';
     $rows = $wpdb->get_results( $sql );

     echo '<h2>View Needs</h2>';

     if ( sizeof($rows) > 0 ) {
       echo '<table><thead><tr><th>Date</th><th>Need</th><th>Beneficiary</th><th>Circumstance</th></tr></thead><tbody>';

       foreach ( $rows as $row ) {
         echo '<tr><td>' . $row->dateCreated . '</td><td>' . $row->need . '</td><td>' . $row->beneficiary . '</td><td>' . $row->circumstance . '</td></tr>';
       }

       echo '</tbody></table>';
     } else {
       echo 'No circumstances found.';
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
