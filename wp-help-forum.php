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
       status INTEGER NOT NULL DEFAULT 0, /* 0=pending, 1=approved, 2=fulfilled, 3=rejected */
       need VARCHAR(50) NOT NULL,
       beneficiary VARCHAR(100) NOT NULL,
       circumstance VARCHAR(500) NOT NULL,
       contactTitle VARCHAR(10) NOT NULL,
       contactFirstName VARCHAR(50) NOT NULL,
       contactLastName VARCHAR(50) NOT NULL,
       contactEmail VARCHAR(100),
       contactPhone VARCHAR(20),
       contactCity VARCHAR(50),
       contactState VARCHAR(2),
       contactZip INTEGER,
       contactAddress VARCHAR(100),
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

     echo '<form action"' . $_SERVER['REQUEST_URI'] . '" method="post">';
     echo '<div>';
     echo '<label for="need"><strong>What is needed?</strong></label>';
     echo '<input type="text" name="need" maxlength="50" required>';
     echo '</div>';
     echo '<div>';
     echo '<label for="beneficiary"><strong>Who is the beneficiary?</strong></label>';
     echo '<input type="text" name="beneficiary" maxlength="100" required>';
     echo '</div>';
     echo '<div>';
     echo '<label for="circumstance"><strong>What are the circumstances (and other details)?</strong></label>';
     echo '<textarea name="circumstance" maxlength="500" required></textarea>';
     echo '</div>';
     echo '<div>';
     echo '<label for="contactName"><strong>Contact name</strong></label>';
     echo '<div name="contactName">';
     echo '<input style="width: 32%; margin-right: 1%;" type="text" name="contactTitle" maxlength="10" placeholder="Title" list="contactTitles" required>';
     echo '<datalist id="contactTitles">';
     echo '<option value="Mr">';
     echo '<option value="Mrs">';
     echo '<option value="Ms">';
     echo '<option value="Miss">';
     echo '<option value="Mx">';
     echo '</datalist>';
     echo '<input style="width: 33%; margin-right: 1%;" type="text" name="contactFirstName" maxlength="50" placeholder="First" required>';
     echo '<input style="width: 33%;" type="text" name="contactLastName" maxlength="50" placeholder="Last" required>';
     echo '</div>';
     echo '</div>';
     echo '<div>';
     echo '<label for="contactEmail"><strong>Contact email</strong></label>';
     echo '<input type="email" name="contactEmail" maxlength="100" placeholder="Optional">';
     echo '</div>';
     echo '<div>';
     echo '<label for="contactPhone"><strong>Contact phone</strong></label>';
     echo '<input type="tel" name="contactPhone" maxlength="20" placeholder="Optional">';
     echo '</div>';
     echo '<div>';
     echo '<label for="contactAddress"><strong>Contact address</strong></label>';
     echo '<input type="text" name="contactAddress" maxlength="100" placeholder="Optional">';
     echo '</div>';
     echo '<br>';
     echo '<input type="submit" name="submit" value="Submit">';
     echo '</form>';
   }

   function help_forum_form_handler() {
     global $wpdb;

     echo '<h2>Submit a Need</h2>';

     if ( isset( $_POST['submit'] ) ) {
       $need = sanitize_text_field( $_POST['need'] );
       $beneficiary = sanitize_text_field( $_POST['beneficiary'] );
       $circumstance = sanitize_textarea_field( $_POST['circumstance'] );
       $contactTitle = sanitize_text_field( $_POST['contactTitle'] );
       $contactFirstName = sanitize_text_field( $_POST['contactFirstName'] );
       $contactLastName = sanitize_text_field( $_POST['contactLastName'] );
       $contactEmail = sanitize_email( $_POST['contactEmail'] );
       $contactPhone = sanitize_text_field( $_POST['contactPhone'] );
       $contactAddress = sanitize_text_field( $_POST['contactAddress'] );

       $table_name = $wpdb->prefix . 'helpforum';

       $wpdb->insert(
         $table_name,
         array(
           'dateCreated' => current_time('mysql'),
           'need' => $need,
           'beneficiary' => $beneficiary,
           'circumstance' => $circumstance,
           'contactTitle' => $contactTitle,
           'contactFirstName' => $contactFirstName,
           'contactLastName' => $contactLastName,
           'contactEmail' => $contactEmail,
           'contactPhone' => $contactPhone,
           'contactAddress' => $contactAddress,
         )
       );

       echo '<p><strong>Need posted, pending approval.</strong></p>';
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
       echo '<div>';

       foreach ( $rows as $row ) {
         echo '<div style="display: flex; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">';
         echo '<div style="width: 85%;">';
         echo '<h3 style="margin: 5px;">' . $row->need . '</h3>';
         echo '<p style="margin: 5px;">' . $row->circumstance . '</p>';
         echo '</div>';
         echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
         echo '<button style="margin: 10px; width: 100%;">Help</button>';
         echo '</div>';
         echo '</div>';
       }

       echo '</div>';
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
