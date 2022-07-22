<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Form_Submission_List extends WP_List_Table {

    function __construct(){
    global $status, $page;

        parent::__construct( array(
            'singular'  => __( 'submission', 'save-form-plugin' ),     //singular name of the listed records
            'plural'    => __( 'submissions', 'save-form-plugin' ),   //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
    ) );

    add_action( 'admin_head', array( &$this, 'admin_header' ) );            

    }

  function admin_header() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
    if( 'save-form-submission' != $page )
    return;
    echo '<style type="text/css">';
    echo '.wp-list-table .column-id { width: 5%; }';
    echo '.wp-list-table .column-booktitle { width: 40%; }';
    echo '.wp-list-table .column-author { width: 35%; }';
    echo '.wp-list-table .column-isbn { width: 20%;}';
    echo '</style>';
  }

  function no_items() {
    _e( 'No Submissions Found.' );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) { 
        case 'first_name':
        case 'last_name':
        case 'email':
        case 'phone':
        case 'country':
        case 'date':
            return $item[ $column_name ];
        default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }


function get_columns(){
      $columns = array(
          'cb'        => '<input type="checkbox" />',
          'first_name'      => __( 'First Name', 'save-form-plugin' ),
          'last_name'      => __( 'Last Name', 'save-form-plugin' ),
          'email'      => __( 'Email', 'save-form-plugin' ),
          'phone'      => __( 'Phone', 'save-form-plugin' ),
          'country'      => __( 'Country', 'save-form-plugin' ),
          "date" => __( 'Date', 'save-form-plugin' )
      );
        return $columns;
  }


function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="book[]" value="%s" />', $item['id']
        );    
    }

function prepare_items() {
  $columns  = $this->get_columns();
  $hidden   = array();
  $sortable = $this->get_sortable_columns();
  $this->_column_headers = array( $columns, $hidden, $sortable );

  // only ncessary because we have sample data
  global $wpdb;
  $table=form_submission_table();
  $sql = "SELECT * FROM $table";
  $results = $wpdb->get_results($sql,ARRAY_A);
  $this->items =   $results;

}

} //class



function my_add_menu_items(){
  $hook = add_menu_page( 'Form Submission', 'Form Submission', 'activate_plugins', 'save-form-submission', 'my_render_list_submissions' );
  add_action( "load-$hook", 'add_options' );
  add_submenu_page( 'save-form-submission', 'Settings', 'Settings','manage_options', 'save-form-submission-settings','save_form_table_settings');
}

function add_options() {
  global $myFormSubmission;
  $option = 'per_page';
  $args = array(
         'label' => 'Submissions',
         'default' => 10,
         'option' => 'submission_per_page'
         );
  add_screen_option( $option, $args );
  $myFormSubmission = new Form_Submission_List();
}

add_action( 'admin_menu', 'my_add_menu_items' );



function my_render_list_submissions(){
  global $myFormSubmission;
  echo '</pre><div class="wrap"><h2>Form Submission </h2>'; 
  $myFormSubmission->prepare_items(); 
?>
  <form method="post">
    <?php

  $myFormSubmission->display(); 
  echo '</form></div>'; 
}

function save_form_table_settings(){
  echo '<div class="wrap"><h2>Form Submission Settings </h2><form method="post">'; 
  $val=get_option('sfp_form_submission_table');
  ?>
  <div id="moa_settings_title-description">
    <p>This Will delete old table and create a new table that you update</p>
  </div>
  <table class="form-table">
    <tbody><tr valign="top">
      <th scope="row" class="titledesc">
        <label for="sfp_form_submission_table">Table Name <span class="woocommerce-help-tip" data-tip="Enter Submission table name"></span></label>
      </th>
      <td class="forminp forminp-text">
        <input name="sfp_form_submission_table" id="sfp_form_submission_table" type="text" required="true" style="" value="<?php echo $val; ?>" class="" placeholder=""> 							</td>
    </tr>
    </tbody>
  </table>
<?php
    submit_button( __( 'Save', 'save-form-plugin' ), 'save' );
    echo "</form>";
}



function save_form_submission_settings() {

  if(isset($_POST['sfp_form_submission_table'])){
      global $wpdb;
      $table=$_POST['sfp_form_submission_table'];
      $old_table=get_option('sfp_form_submission_table',"form_submission");
      if($old_table!=$table){
        if ($wpdb->get_var("show tables like '" . $table . "'") != $table) {
          update_option('sfp_form_submission_table',$_POST['sfp_form_submission_table']);
          $sql = "DROP TABLE IF EXISTS $old_table";
          $wpdb->query($sql);
          form_submission_activation();
          echo '<script>alert("Setting Updated")</script>';
        }else{
        echo "<script>alert('The table name is reserved try different')</script>";
        }
      }
  }
}

add_action( 'admin_init', 'save_form_submission_settings' );
