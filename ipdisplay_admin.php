<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 1/17/2019
 * Time: 3:17 PM
 */

?>
    <script>
        function go(id){
            var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
                console.log(ajaxurl)
			console.log(id.value);
            jQuery.ajax({
                type: 'post',
				url : ajaxurl,
                data : {
					 
                    action: 'cumint_getPageip',
					page_id: id.value,
                },
                success: function (response) {
                    jQuery('#txthint').html(response);
                }
            });
        }
    </script>

<div class="wrap">
    <h1>View Visitors Details</h1>

        <form name="myform" method="POST">

            <select name="users" id="users" onchange="go(this);">
                <option value="select">--Select--</option>
                <?php
                global $wpdb;
                $result = $wpdb->get_results("SELECT ID, post_title FROM wp_posts WHERE post_type = 'page'");
                foreach ($result as $page) {
                    ?>
                    <option value="<?php echo $page->ID ?>" ><?php echo $page->post_title ?></option>
                <?php } ?>
            </select>
        </form>

    <br><br>

</div>
<div id="txthint">
    <b>Person info will be listed here...</b>
</div>
