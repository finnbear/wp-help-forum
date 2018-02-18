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

   function help_forum_test() {
     $args = array();

     echo 'Hello world';
   }

   register_activation_hook(__FILE__, 'help_forum_activate');
   register_deactivation_hook(__FILE__, 'help_forum_deactivate');
   register_uninstall_hook(__FILE__, 'help_forum_uninstall');
?>
