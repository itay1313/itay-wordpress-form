<?php 


    function save_form_plugin() {
      $content = '';
      $content .= '<form method="post" action="http://localhost:8888/wordpress-itay/thanks/" method="post">';
      $content .= '<div class="input-rows">';
      // full name
      $content .= '<input type="text" name="first_name" placeholder="*First Name" required />';
      $content .= '<input type="text" name="last_name" placeholder="*Last Name" required />';

      // email and phone
      $content .= '<input type="email" name="email" placeholder="*Email" required/>';
      $content .= '<input type="tel" name="phone" placeholder="Phone Number" />';
      $content .= '</div>';

      $content .= '<div>';
      $content .= '<hr>';
      // checkbox
      $content .= '<div class="checkbox">';
      $content .= '<input type="checkbox" checked>';
      $content .= '<span>I have read and agree to the <a href="#">Terms and Conditions</a> and the <a href="#">Privacy Policy</a></span>';
      $content .= '</div>';
      // submit
      $content .= '<input type="submit" name="submitbtn" placeholder="SUBMIT >" />';
      $content .= '</div>';
      $content .= '</form>';

      return $content;
    }
    add_shortcode('save_form', 'save_form_plugin');

function form_capture() {
  if (isset($_POST['submit'])) {
    $first_name =  $_POST['first_name'];
    $last_name =  $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    global $wpdb;

    $sql=$wpdb->insert('wp_form_submissions', array(
      "first_name" => $first_name,
      "last_name" => $last_name,
      "email" => $email,
      "phone" => $phone,
    ));
    if($sql){
      echo '<script>alert("Form Submitted Successfully")</script>';
    } else {
      echo '<script>alert("Form Submission Failed")</script>';
    }
}
add_action('wp_head', 'form_capture');

?>