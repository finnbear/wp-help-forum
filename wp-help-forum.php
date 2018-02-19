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
       status INTEGER NOT NULL DEFAULT 0, /* 0=pending, 1=approved, 2=pledged, 3=rejected, 4=fulfilled */
       need VARCHAR(50) NOT NULL,
       beneficiary VARCHAR(100) NOT NULL,
       circumstance VARCHAR(500) NOT NULL,
       contactTitle VARCHAR(10) NOT NULL,
       contactFirstName VARCHAR(50) NOT NULL,
       contactLastName VARCHAR(50) NOT NULL,
       contactEmail VARCHAR(100),
       contactPhone VARCHAR(20),
       contactAddress VARCHAR(100),
       contactCity VARCHAR(50),
       contactState VARCHAR(2),
       contactZip INTEGER,
       donorComment VARCHAR(500),
       donorTitle VARCHAR(10),
       donorFirstName VARCHAR(50),
       donorLastName VARCHAR(50),
       donorEmail VARCHAR(100),
       donorPhone VARCHAR(20),
       donorAddress VARCHAR(100),
       donorCity VARCHAR(50),
       donorState VARCHAR(2),
       donorZip INTEGER,
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

   function help_forum_general_form_body($person, $personProper, $title = '', $firstName = '', $lastName = '', $email = '', $phone = '', $address = '', $city = '', $state = '', $zip = '') {
     echo '<div>';
     echo '<label for="' . $person . 'Name"><strong>' . $personProper . ' name *</strong></label>';
     echo '<div name="' . $person . 'Name">';
     echo '<input style="width: 22%; margin-right: 1%;" type="text" name="' . $person . 'Title" maxlength="10" placeholder="Title" value="' . $title . '" list="contactTitles" required>';
     echo '<datalist id="contactTitles">';
     echo '<option value="Mr.">';
     echo '<option value="Ms.">';
     echo '<option value="Dr.">';
     echo '</datalist>';
     echo '<input style="width: 38%; margin-right: 1%;" type="text" name="' . $person . 'FirstName" maxlength="50" placeholder="First" value="' . $firstName . '" required>';
     echo '<input style="width: 38%;" type="text" name="' . $person . 'LastName" maxlength="50" placeholder="Last" value="' . $lastName . '" required>';
     echo '</div>';
     echo '</div>';
     echo '<div>';
     echo '<label for="' . $person . 'Email"><strong>' . $personProper . ' email</strong></label>';
     echo '<input type="email" name="' . $person . 'Email" maxlength="100" value="' . $email . '">';
     echo '</div>';
     echo '<div>';
     echo '<label for="' . $person . 'Phone"><strong>' . $personProper . ' phone</strong></label>';
     echo '<input type="tel" name="' . $person . 'Phone" maxlength="20" value="' . $phone . '">';
     echo '</div>';
     echo '<div>';
     echo '<label for="' . $person . 'Address"><strong>' . $personProper . ' address</strong></label>';
     echo '<input style="margin-bottom: 5px;" type="text" name="' . $person . 'Address" maxlength="100" placeholder="Address" value="' . $address . '">';
     echo '<div>';
     echo '<input style="width: 40%; margin-right: 1%;" type="text" name="' . $person . 'City" maxlength="50" placeholder="City" value="' . $city . '">';
     echo '<input style="width: 29%; margin-right: 1%;" type="text" name="' . $person . 'State" maxlength="2" list="contactStates" placeholder="State" value="' . $state .'">';
     echo '<datalist id="contactStates">';
     echo '<option value="AL">';
     echo '<option value="AK">';
     echo '<option value="AZ">';
     echo '<option value="AR">';
     echo '<option value="CA">';
     echo '<option value="CO">';
     echo '<option value="CT">';
     echo '<option value="DE">';
     echo '<option value="FL">';
     echo '<option value="GA">';
     echo '<option value="HI">';
     echo '<option value="ID">';
     echo '<option value="IL">';
     echo '<option value="IN">';
     echo '<option value="IA">';
     echo '<option value="KS">';
     echo '<option value="KY">';
     echo '<option value="LA">';
     echo '<option value="ME">';
     echo '<option value="MD">';
     echo '<option value="MA">';
     echo '<option value="MI">';
     echo '<option value="MN">';
     echo '<option value="MO">';
     echo '<option value="MT">';
     echo '<option value="NE">';
     echo '<option value="NV">';
     echo '<option value="NH">';
     echo '<option value="NJ">';
     echo '<option value="NM">';
     echo '<option value="NY">';
     echo '<option value="NC">';
     echo '<option value="ND">';
     echo '<option value="OH">';
     echo '<option value="OK">';
     echo '<option value="OR">';
     echo '<option value="PA">';
     echo '<option value="RI">';
     echo '<option value="SC">';
     echo '<option value="SD">';
     echo '<option value="TN">';
     echo '<option value="TX">';
     echo '<option value="UT">';
     echo '<option value="VT">';
     echo '<option value="VA">';
     echo '<option value="WA">';
     echo '<option value="WV">';
     echo '<option value="WI">';
     echo '<option value="WY">';
     echo '</datalist>';
     echo '<style>input[type=number] { -moz-appearance: textfield; } input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }</style>';
     echo '<input style="width: 29%;" type="number" name="' . $person . 'Zip" maxlength="10" placeholder="Zip" value="' . $zip . '">';
     echo '</div>';
     echo '</div>';
   }

   function help_forum_form() {
     echo '<form action"' . $_SERVER['REQUEST_URI'] . '" method="post">';
     echo '<input type="hidden" name="action" value="new">';
     echo '<div>';
     echo '<label for="need"><strong>What is needed? *</strong></label>';
     echo '<input type="text" name="need" maxlength="50" required>';
     echo '</div>';
     echo '<div>';
     echo '<label for="beneficiary"><strong>Who is the beneficiary? *</strong></label>';
     echo '<input type="text" name="beneficiary" maxlength="100" required>';
     echo '</div>';
     echo '<div>';
     echo '<label for="circumstance"><strong>What are the circumstances? *</strong></label>';
     echo '<textarea name="circumstance" maxlength="500" required></textarea>';
     echo '</div>';

     help_forum_general_form_body('contact', 'Contact');

     echo '<br>';
     echo '<input type="submit" name="submit" value="Submit">';
     echo '</form>';
   }

   function help_forum_form_handler() {
     global $wpdb;

     echo '<h2>Submit a Need</h2>';

     if ( isset( $_POST['submit'] ) and $_POST['action'] == "new" ) {
       $need = sanitize_text_field( $_POST['need'] );
       $beneficiary = sanitize_text_field( $_POST['beneficiary'] );
       $circumstance = sanitize_textarea_field( $_POST['circumstance'] );
       $contactTitle = sanitize_text_field( $_POST['contactTitle'] );
       $contactFirstName = sanitize_text_field( $_POST['contactFirstName'] );
       $contactLastName = sanitize_text_field( $_POST['contactLastName'] );
       $contactEmail = sanitize_email( $_POST['contactEmail'] );
       $contactPhone = sanitize_text_field( $_POST['contactPhone'] );
       $contactAddress = sanitize_text_field( $_POST['contactAddress'] );
       $contactZip = sanitize_text_field( $_POST['contactZip'] );
       $contactCity = sanitize_text_field( $_POST['contactCity'] );
       $contactState = sanitize_text_field( strtoupper( $_POST['contactState'] ) );

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
           'contactZip' => $contactZip,
           'contactCity' => $contactCity,
           'contactState' => $contactState,
         )
       );

       echo '<p><strong>Thank you for submitting your need. We will contact you if a donor is found.</strong></p>';
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

     $admin = current_user_can( 'edit_posts' );

     $table_name = $wpdb->prefix . 'helpforum';

     $sql = '';

     if ($admin) {
       $sql = 'SELECT id, dateCreated, status, need, beneficiary, circumstance, contactTitle, contactFirstName, contactLastName, contactEmail, contactPhone, contactAddress, contactZip, contactCity, contactState, donorTitle, donorFirstName, donorLastName, donorEmail, donorPhone, donorAddress, donorZip, donorCity, donorState, donorComment FROM ' . $table_name . ' ORDER BY id DESC;';
     } else {
       $sql = 'SELECT id, dateCreated, status, need, circumstance FROM ' . $table_name . ' WHERE status = 1 ORDER BY id DESC;';
     }

     $rows = $wpdb->get_results( $sql );

     echo '<h2>View Needs</h2>';

     if ( sizeof($rows) > 0 ) {
       echo '<div>';

       foreach ( $rows as $row ) {
         echo '<div style="display: flex; flex-wrap: wrap; border: 1px solid #ccc; border-radius: 8px; margin-top: 5px;">';
         echo '<div style="width: 60%;">';
         if ($row->status == 3) {
           echo '<strike>';
         }
         echo '<h3 style="margin: 5px;">' . str_replace( '\\', '', $row->need ) . '</h3>';
         if ($row->status == 3) {
           echo '</strike>';
         }
         echo '<p style="margin: 5px;">' . str_replace( '\\', '', $row->circumstance ) . '</p>';

         $phpdate = strtotime( $row->dateCreated );
         $displaydate = date( 'm-d-Y H:i:s', $phpdate );
         echo '<small style="margin: 5px;">Posted on ' . $displaydate . '.</small>';

         echo '</div>';

         if ($admin) {
           if ($row->status == 0 or $row->status == 3) {
             echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
             echo '<form style="margin: 10px;" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
             echo '<input type="hidden" name="id" value="' . $row->id . '">';
             echo '<input type="hidden" name="action" value="accept">';

             if ($row->status == 3) {
               echo '<button style="height: 42px;" name="submit">Restore</button>';
             } else {
               echo '<button style="height: 42px;" name="submit">Accept</button>';
             }

             echo '</form>';
             echo '</div>';
           }

           if ($row->status == 0 or $row->status == 1) {
             echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
             echo '<form style="margin: 10px;" action="' . $_SERVER['REQUEST_URI'] . '" method="post" id="donorRejectForm' . $row->id . '">';
             echo '<input type="hidden" name="id" value="' . $row->id . '">';
             echo '<input type="hidden" name="action" value="reject">';
             echo '<button style="height: 42px;" name="submit">Reject</button>';
             echo '</form>';
             echo '</div>';
           }

           if ($row->status == 2) {
             echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
             echo '<form style="margin: 10px;" action="' . $_SERVER['REQUEST_URI'] . '" method="post" id="donorRejectForm' . $row->id . '">';
             echo '<input type="hidden" name="id" value="' . $row->id . '">';
             echo '<input type="hidden" name="action" value="complete">';
             echo '<button style="height: 42px;" name="submit">Close</button>';
             echo '</form>';
             echo '</div>';
           }

           if ($row->status == 4) {
             echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
             echo '<form style="margin: 10px;" action="' . $_SERVER['REQUEST_URI'] . '" method="post" id="donorRejectForm' . $row->id . '">';
             echo '<input type="hidden" name="id" value="' . $row->id . '">';
             echo '<input type="hidden" name="action" value="reopen">';
             echo '<button style="height: 42px;" name="submit">Reopen</button>';
             echo '</form>';
             echo '</div>';
           }
         }

         if ($row->status == 1) {
           echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
           echo '<button style="height: 42px; margin: 10px;" type="button" onclick="this.style.display=\'none\'; document.getElementById(\'helpDonorForm' . $row->id . '\').style.display=\'block\'; document.getElementById(\'donorRejectForm' . $row->id . '\').style.display=\'none\';" id="donorHelpButton' . $row->id . '">Pledge</button>';
           echo '</div>';

           echo '<form style="display: none; margin: 10px;" id="helpDonorForm' . $row->id . '" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';
           echo '<h3>Enter Donor Information</h3>';
           echo '<input type="hidden" name="id" value="' . $row->id . '">';
           echo '<input type="hidden" name="action" value="help">';

           help_forum_general_form_body('donor', 'Donor');

           echo '<div>';
           echo '<label for="donorComment"><strong>Any comments?</strong></label>';
           echo '<textarea name="donorComment" maxlength="500"></textarea>';
           echo '</div>';

           echo '<div style="margin: 10px; width: 100%;">';
           echo '<input style="margin-right: 5px;" type="submit" name="submit" value="Submit">';
           echo '<button type="button" onclick="document.getElementById(\'donorHelpButton' . $row->id . '\').style.display=\'block\'; document.getElementById(\'helpDonorForm' . $row->id . '\').style.display=\'none\'; document.getElementById(\'donorRejectForm' . $row->id . '\').style.display=\'block\';">Cancel</button>';
           echo '</div>';
           echo '</form>';
         } else if ($row->status == 2 and $admin) {
           echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
           echo '<form style="margin: 10px;" action="' . $_SERVER['REQUEST_URI'] . '" method="post" id="donorRejectForm' . $row->id . '">';
           echo '<input type="hidden" name="id" value="' . $row->id . '">';
           echo '<input type="hidden" name="action" value="rejectdonor">';
           echo '<button style="height: 42px;" name="submit">Reject Donor</button>';
           echo '</form>';
           echo '</div>';

           echo '<div style="display: flex; flex-grow: 1; justify-content: center;" align="right">';
           echo '<button style="height: 42px; margin: 10px; width: 100%;" type="button" onclick="this.style.display=\'none\'; document.getElementById(\'transactionDetails' . $row->id . '\').style.display=\'block\';" id="transactionDetailsButton' . $row-> id . '">View / Edit</button>';
           echo '</div>';

           echo '<form style="display: none; margin: 10px;" id="transactionDetails' . $row->id . '" action="' . $_SERVER['REQUEST_URI'] . '" method="post">';

           echo '<input type="hidden" name="id" value="' . $row->id . '">';
           echo '<input type="hidden" name="action" value="update">';

           echo '<h3>Contact Details</h3>';
           help_forum_general_form_body("contact", "Contact", $row->contactTitle, $row->contactFirstName, $row->contactLastName, $row->contactEmail, $row->contactPhone, $row->contactAddress, $row->contactCity, $row->contactState, $row->contactZip);

           echo '<h3>Donor Details</h3>';
           help_forum_general_form_body("donor", "Donor", $row->donorTitle, $row->donorFirstName, $row->donorLastName, $row->donorEmail, $row->donorPhone, $row->donorAddress, $row->donorCity, $row->donorState, $row->donorZip);

           echo '<h3>Donor Comment</h3>';
           echo '<p>' . $row->donorComment . '</p>';

           echo '<div style="margin: 10px; width: 100%;">';
           echo '<input style="margin-right: 5px;" type="submit" name="submit" value="Save">';
           echo '<button style="height: 42px;" type="button" onclick="document.getElementById(\'transactionDetailsButton' . $row->id . '\').style.display = \'block\'; document.getElementById(\'transactionDetails' . $row->id . '\').style.display=\'none\';">Cancel</button>';
           echo '</div>';
           echo '</form>';
         }


         echo '</div>';
       }

       echo '</div>';
     } else {
       echo 'No circumstances found.';
     }
   }

   function help_forum_list_handler() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'helpforum';

        if ( isset( $_POST['submit'] ) ) {
          $id = sanitize_text_field( $_POST['id'] );
          $action = sanitize_text_field( $_POST['action'] );

          $status = 0;

          if ($action == "help") {
            $donorTitle = sanitize_text_field( $_POST['donorTitle'] );
            $donorFirstName = sanitize_text_field( $_POST['donorFirstName'] );
            $donorLastName = sanitize_text_field( $_POST['donorLastName'] );
            $donorEmail = sanitize_email( $_POST['donorEmail'] );
            $donorPhone = sanitize_text_field( $_POST['donorPhone'] );
            $donorAddress = sanitize_text_field( $_POST['donorAddress'] );
            $donorZip = sanitize_text_field( $_POST['donorZip'] );
            $donorCity = sanitize_text_field( $_POST['donorCity'] );
            $donorState = sanitize_text_field( strtoupper( $_POST['donorState'] ) );
            $donorComment = sanitize_textarea_field( $_POST['donorComment'] );

            $sql = 'UPDATE ' . $table_name . ' SET donorTitle = "' . $donorTitle . '",
                                                   donorFirstName = "' . $donorFirstName . '",
                                                   donorLastName = "' . $donorLastName . '",
                                                   donorEmail = "' . $donorEmail . '",
                                                   donorPhone = "' . $donorPhone . '",
                                                   donorAddress = "' . $donorAddress . '",
                                                   donorZip = "' . $donorZip . '",
                                                   donorCity = "' . $donorCity . '",
                                                   donorState = "' . $donorState . '",
                                                   donorComment = "' . $donorComment . '"
                                                WHERE id = ' . $id . ';';
            $wpdb->query( $sql );

            $status = 2;
          } else if (current_user_can( 'edit_posts' ) ) {
            if ( $action == "accept" ) {
              $status = 1;
            } else if ( $action == "reject" ) {
              $status = 3;
            } else if ( $action == "rejectdonor" ) {
              $status = 1;

              $sql = 'UPDATE ' . $table_name . ' SET donorTitle = "",
                                                     donorFirstName = "",
                                                     donorLastName = "",
                                                     donorEmail = "",
                                                     donorPhone = "",
                                                     donorAddress = "",
                                                     donorZip = "",
                                                     donorCity = "",
                                                     donorState = "",
                                                     donorComment = ""
                                                  WHERE id = ' . $id . ';';
              $wpdb->query( $sql );
            } else if ($action == "update") {
              $contactTitle = sanitize_text_field( $_POST['contactTitle'] );
              $contactFirstName = sanitize_text_field( $_POST['contactFirstName'] );
              $contactLastName = sanitize_text_field( $_POST['contactLastName'] );
              $contactEmail = sanitize_email( $_POST['contactEmail'] );
              $contactPhone = sanitize_text_field( $_POST['contactPhone'] );
              $contactAddress = sanitize_text_field( $_POST['contactAddress'] );
              $contactZip = sanitize_text_field( $_POST['contactZip'] );
              $contactCity = sanitize_text_field( $_POST['contactCity'] );
              $contactState = sanitize_text_field( strtoupper( $_POST['contactState'] ) );
              $contactComment = sanitize_textarea_field( $_POST['contactComment'] );

              $donorFirstName = sanitize_text_field( $_POST['donorFirstName'] );
              $donorLastName = sanitize_text_field( $_POST['donorLastName'] );
              $donorEmail = sanitize_email( $_POST['donorEmail'] );
              $donorPhone = sanitize_text_field( $_POST['donorPhone'] );
              $donorAddress = sanitize_text_field( $_POST['donorAddress'] );
              $donorZip = sanitize_text_field( $_POST['donorZip'] );
              $donorCity = sanitize_text_field( $_POST['donorCity'] );
              $donorState = sanitize_text_field( strtoupper( $_POST['donorState'] ) );
              $donorComment = sanitize_textarea_field( $_POST['donorComment'] );

              $sql = 'UPDATE ' . $table_name . ' SET contactTitle = "' . $contactTitle . '",
                                                     contactFirstName = "' . $contactFirstName . '",
                                                     contactLastName = "' . $contactLastName . '",
                                                     contactEmail = "' . $contactEmail . '",
                                                     contactPhone = "' . $contactPhone . '",
                                                     contactAddress = "' . $contactAddress . '",
                                                     contactZip = "' . $contactZip . '",
                                                     contactCity = "' . $contactCity . '",
                                                     contactState = "' . $contactState . '",
                                                     donorTitle = "' . $donorTitle . '",
                                                     donorFirstName = "' . $donorFirstName . '",
                                                     donorLastName = "' . $donorLastName . '",
                                                     donorEmail = "' . $donorEmail . '",
                                                     donorPhone = "' . $donorPhone . '",
                                                     donorAddress = "' . $donorAddress . '",
                                                     donorZip = "' . $donorZip . '",
                                                     donorCity = "' . $donorCity . '",
                                                     donorState = "' . $donorState . '",
                                                     donorComment = "' . $donorComment . '"
                                                  WHERE id = ' . $id . ';';
              $wpdb->query( $sql );
            } else if ( $action == "complete" ) {
              $status = 4;
            } else if ( $action == "reopen" ) {
              $status = 2;
            }
          }

          if ($status != 0) {
            $sql = 'UPDATE ' . $table_name . ' SET status = ' . $status . ' WHERE id = ' . $id . ';';
            $wpdb->query( $sql );
          }
        }

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
