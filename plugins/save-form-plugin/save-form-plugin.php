<?php

/*
Plugin Name: Save Form Plugin
*/

// plugin activation hook
register_activation_hook(__FILE__, 'form_submission_activation');

// callback function to create table
function form_submission_activation()
{
    global $wpdb;

    if ($wpdb->get_var("show tables like '" . form_submission_table() . "'") != form_submission_table()) {

        $mytable = 'CREATE TABLE `' . form_submission_table() . '` (
                            `id` int(11) NOT NULL AUTO_INCREMENT,
                            `first_name` varchar(100) NOT NULL,
                            `last_name` varchar(100) NOT NULL,
                            `email` varchar(50) NOT NULL,
                            `phone` varchar(50) NOT NULL,
                            `country` varchar(50) NOT NULL,
                            `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                            PRIMARY KEY (`id`)
                          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;';

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($mytable);
    }
}


// returns table name
function form_submission_table()
{
    $table="form_submission";
    $table=get_option('sfp_form_submission_table',"form_submission");
    return $table;
}


function save_form_plugin() {
    if (isset($_POST['insert'])){

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $country = $_POST['country'];
        $date = $_POST['date'];

        global $wpdb;

        $table=form_submission_table();
        $sql = $wpdb->insert($table, array(
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "phone" => $phone,
            "country" => $country,
            "date" => $date,
        ));

        if ($sql)
        {
             wp_redirect( site_url( "/thanks" ) );
        exit();
        }
        else
        {
            echo '<script>alert("Form Submission Failed")</script>';
        }
    } 
}
   
add_action('init', 'save_form_plugin');

include "list-table.php";
// include "settings.php";

