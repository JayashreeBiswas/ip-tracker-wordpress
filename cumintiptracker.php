<?php

/**
 * Plugin Name: cumint ip tracker
 * Description: This is a custom plugin for tracking ip address of customers.
 **/

global $jal_db_version;
$jal_db_version = '1.0';
function jal_install() {
    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'tracker';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		ip_address varchar (400) NOT NULL ,
		date_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		page_id varchar (400) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );
}
register_activation_hook( __FILE__, 'jal_install' );
function cumintip_admin()
{
    include('cumintip_admin.php');

}
/**
 *
 */
function cumintip_admin_actions()
{
    add_options_page("Cumintip Product Display", "Cumintip Product Display", 1, "Cumintip Product Display", "cumintip_admin");
}

add_action('admin_menu', 'cumintip_admin_actions');

add_action('wp', 'getRealIpAddr');

/**
 * function for getting the ip address
 **/

function getRealIpAddr()
{
    global $wpdb;

    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
        $date_time = date("Y-m-d H:i:s");
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $date_time = date("Y-m-d H:i:s");
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
        $date_time = date("Y-m-d H:i:s");
    }
    //echo 'user ip address is - ' . $ip . ', visiting time : ' . $date_time;
    // return $ip;
    //echo 'First';
    $page = get_option('pg_id_cumint');
//echo $page;

    //$ab=7;
    global $post;
    $post_id = $post->ID;
    //echo $post_id;
    /**$pageId=$post->ID();
     * echo $pageId;*/
    //if('special_cpt' === $page)
		
    foreach ($page as $pg) {
        // echo $pg;
        $pid = (int)str_replace(' ', '', $pg);
        if ($pid === $post_id) {
            //echo $post_id;
            $wpdb->insert("wp_tracker", array('ip_address' => $ip, 'date_time' => $date_time, 'page_id' => $pg));
           // echo 'user ip address is - ' . $ip . ', visiting time : ' . $date_time;
        }
    }
//    echo 'user ip address is - ' . $ip . ', visiting time : ' . $date_time;

}

function ip_display_admin()
{
    include('ipdisplay_admin.php');
}

function ip_display_admin_actions()
{
    add_options_page("Visitor Detail Display", "Visitor Detail Display", 1, "Visitor Detail Display", "ip_display_admin");
}

add_action('admin_menu', 'ip_display_admin_actions');

    /**
 * ajax call
 */


   add_action( 'wp_ajax_cumint_getPageip', 'cumint_getPageip' );
   add_action( 'wp_ajax_cumint_getPageip', 'cumint_getPageip' );
   
   function cumint_getPageip(){
       
//	   print_r($_REQUEST);
	   global $wpdb;  

  
     //echo "SELECT id, ip_address, date_time FROM wp_tracker WHERE page_id ='" . $_REQUEST['page_id'] . "'";
    $get_visitor = $wpdb->get_results("SELECT id, ip_address, date_time FROM wp_tracker WHERE page_id ='" . $_REQUEST['page_id'] . "'");
    //echo $get_visitor;
    if (!empty($get_visitor)) {
        echo "<table width='80%'  border='1' border-collapse='collapse'>"; // Adding <table> and <tbody> tag outside foreach loop so that it wont create again and again
        echo "<tbody>";

            //$userip = $row->user_ip;               //putting the user_ip field value in variable to use it later in update query
            echo "<tr>";                           // Adding rows of table inside foreach loop

            echo "<th style='border: 1px solid #ddd; padding-top: 12px; padding-bottom: 12px;'>ID</th> <th style='border: 1px solid #ddd;'>IP ADDRESS</th> <th style='border: 1px solid #ddd;'>DATE & TIME</th>";
            echo "</tr>";
        foreach ($get_visitor as $row) {
//            echo "<td colspan='2'><hr size='1'></td>";

            echo "<tr style='height: 50px;'>";
            echo "<td style='text-align: center; border: 1px solid #ddd;'>" . $row->id . "</td>"
                . "<td style='text-align: center; border: 1px solid #ddd;'>" . $row->ip_address . "</td>"
                . "<td style='text-align: center; border: 1px solid #ddd;'>" . $row->date_time . "</td>";   //fetching data from user_ip field
            echo "</tr>";

        }
        echo "</tbody>";
        echo "</table>";

    }
	   
	   
	   
   }

   
?>