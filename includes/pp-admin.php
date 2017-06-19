<?php

if (! defined('WPINC') ) {
  die;
}

if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$pin_table = new Pin_Table();
$pin_table->prepare_items();

class Pin_Table extends WP_List_Table {

  function __construct() {
    parent::__construct( array(
      'singular'=> 'pin',
      'plural' => 'pins',
      'ajax'   => false
    ) );
  }

  function extra_tablenav( $which ) {
    if ( $which == "top" ){
      echo"Hello, I'm before the table";
    }
    if ( $which == "bottom" ){
      echo"Hi, I'm after the table";
    }
  }

  function get_columns() {
    return $columns = array(
      'name' => 'Name',
      'lat' => 'Lat',
      'lng' => 'Lng'
    );
  }

  function get_sortable_columns() {
    return $sortable_columns = array(
      'name' => 'Name',
    );
  }

  function prepare_items() {
    global $wpdb;

    $query = "SELECT * FROM pp_pins";

    if (!empty($_GET["orderby"])) {
      $query .= $_GET["orderby"] . ($_GET["order"] ? $_GET["order"] : ' ASC');
    }

    $totalitems = $wpdb->query($query);
    $perpage = 30;
    $totalpages = ceil($totalitems / $perpage);

    //fix bounds on paged
    $paged = !empty($_GET["paged"]) ? $_GET["paged"] : 1;
    if (empty($paged) || !is_numeric($paged) || $paged <= 0) $paged=1;
    if ($paged > $totalpages) $paged = $totalpages;

    $query .= ' LIMIT ' . $perpage;
    $query .= ' OFFSET ' . (($paged - 1) * $perpage);

    /* -- Register the pagination -- */
    $this->set_pagination_args(array(
      "total_items" => $totalitems,
      "total_pages" => $totalpages,
      "per_page" => $perpage,
    ));

    $this->_column_headers = array(
      $this->get_columns(),
      array(),
      $this->get_sortable_columns()
    );

    $this->items = $wpdb->get_results($query);
  }

  function display_rows() {
    $records = $this->items;

    //Get the columns registered in the get_columns and get_sortable_columns methods
    list( $columns ) = $this->_column_headers;

    if(!empty($records)){
      foreach($records as $rec){

        echo '<tr id="record_'.$rec->id.'">';
        foreach ( $columns as $column_name => $column_display_name ) {
          echo '<td>'.$rec->$column_name.'</td>';
        }
        echo'</tr>';
      }
    }
  }
}
?>

<div class="wrap">
  <?php $pin_table->display(); ?>
</div>
