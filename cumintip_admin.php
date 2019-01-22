<?php
//$page = get_option('pg_id_cumint');
//foreach ($page as $cumint) {
//    echo $cumint;
//}
if ($_POST['ciptracker_hidden'] == 'Y') {
    //Form data sent

    $dbhost = $_POST['ciptracker_dbhost'];
    update_option('ciptracker_dbhost', $dbhost);

    $dbname = $_POST['ciptracker_dbname'];
    update_option('ciptracker_dbname', $dbname);

    $dbuser = $_POST['ciptracker_dbuser'];
    update_option('ciptracker_dbuser', $dbuser);

    $dbpwd = $_POST['ciptracker_dbpwd'];
    update_option('ciptracker_dbpwd', $dbpwd);

    $page = $_POST['pageArray'];


    ?>
    <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
    <?php

} else {
    //Normal page display

    $dbhost = get_option('ciptracker_dbhost');
    $dbname = get_option('ciptracker_dbname');
    $dbuser = get_option('ciptracker_dbuser');
    $dbpwd = get_option('ciptracker_dbpwd');


}

// update_option('page_id', 2);
if (isset($_POST['pageArray'])) {

    // Create an empty array. This is the one we'll eventually store.
    $arr_store_me = array();

    // Create a "whitelist" of posted values (field names) you'd like in your array.
    // The $_POST array may contain all kinds of junk you don't want to store in
    // your option, so this helps sort that out.
    // Note that these should be the names of the fields in your form.

    $page_inputs = $_POST['pageArray'];
    // Loop through the $_POST array, and look for items that match our whitelist
    foreach ($page_inputs as $key => $value) {

        if (in_array($value, $page_inputs)) {

            if ($value != 0) {
                $arr_store_me[$key] = $value;
            }
        }

    }

    // Now we have a final array we can store (or use however you want)!
    // Update option accepts arrays--no need
    // to do any other formatting here. The values will be automatically serialized.

    update_option('pg_id_cumint', $arr_store_me);

}

?>
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<div class="wrap">
    <?php echo "<h2>" . __('Cumintiptracker Display Options', 'hotel') . "</h2>"; ?>

    <form name="ciptracker_form" id="ciptracker_form" method="post"
          action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="ciptracker_hidden" value="Y">
        <?php echo "<h4>" . __('Cumintiptracker Database Settings', 'hotel') . "</h4>"; ?>
        <p><?php _e("Database host: "); ?><input type="text" name="ciptracker_dbhost"
                                                 value="<?php echo $dbhost; ?>"><?php _e(" ex: localhost"); ?></p>
        <p><?php _e("Database name: "); ?><input type="text" name="ciptracker_dbname" value="<?php echo $dbname; ?>"
                                                 size="20"><?php _e(" ex: oscommerce_shop"); ?></p>
        <p><?php _e("Database user: "); ?><input type="text" name="ciptracker_dbuser" value="<?php echo $dbuser; ?>"
                                                 size="20"><?php _e(" ex: root"); ?></p>
        <p>Database password: <input type="text" name="ciptracker_dbpwd" value="<?php echo $dbpwd; ?>"
                                     size="20"><?php _e(" ex: secretpassword"); ?></p>
        <hr/>
        <?php echo "<h4>" . __('Cumintiptracker page Settings', 'hotel') . "</h4>"; ?>

        <div class="table-responsive">
            <table class="table table-bordered" id="dynamic_field">
                <tr>
                    <td><p><?php _e("Pages for which the tracker needed: "); ?></p>

                        <?php
                        global $wpdb;

                        $result = $wpdb->get_results("SELECT ID, post_title FROM wp_posts WHERE post_type = 'page'");

                        $optionResult = get_option('pg_id_cumint');

//                        foreach ($optionResult as $cumint) {
//                            echo $cumint;
//                        }
                        foreach ($result as $i=>$page) {
                            // [2,3,13] +List
                            // [3,13] - Selected
//                            echo $i;
                            echo "<label style='padding:10px; font-style:normal; font-weight: lighter;'>
                                     <input type='checkbox'  style='padding:2px; margin-right:10px; margin-top:0px;'
                                      name='pageArray[]' value='" . $page->ID . "' id='".$page->ID."'>" . $page->post_title . "</label>";

                        }

                        ?>
                        <script>
                            var selectedArray = <?php echo json_encode($optionResult);?>;
                            for ( var i = 0; i<=selectedArray.length-1;i++ ) {
                                document.getElementById(selectedArray[i]).checked = true;
                            }

                        </script>

                    </td>
                </tr>
            </table>

        </div>
        <p class="submit">
            <input type="submit" name="Submit" id="submit" value="<?php _e('Update Options', 'hotel') ?>"/>
        </p>
    </form>
</div>
