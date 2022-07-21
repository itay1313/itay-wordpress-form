<?php 
/**
 * Plugin Name: Save Form Plugin
 */

function save_form_plugin() {
 if (isset($_POST['insert']))
        {
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $country = $_POST['country'];
            $date = $_POST['date'];

            global $wpdb;

            $sql = $wpdb->insert('form_submissions', array(
                "first_name" => $first_name,
                "last_name" => $last_name,
                "email" => $email,
                "phone" => $phone,
                "country" => $country,
                "date" => $date,
            ));

            if ($sql)
            {
                echo '<script>

      window.location.href = "http://localhost:8888/wordpress-itay/thanks";
     </script>';
            }
            else
            {
                echo '<script>alert("Form Submission Failed")</script>';
            }
        } ?>
<?php if (comments_open() && !post_password_required())
        {
            comments_template('', true);
        } 

 add_action('wp_head', 'save_form_plugin');

?>  