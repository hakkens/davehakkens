<?php

if (! defined('WPINC') ) {
  die;
}

if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

include_once dirname( __FILE__ ) . '/pin-edit.php';

class Pin_Table extends WP_List_Table {

  function user_is_admin() {
    $user = wp_get_current_user();
    return in_array('administrator', (array) $user->roles);
  }

  function __construct() {
    parent::__construct( array(
      'singular'=> 'pin',
      'plural' => 'pins',
      'ajax'   => false
    ) );
  }

  function extra_tablenav( $which ) {
    if ($which == "top") {
      echo $this->getPageHeader();
    }
    if ($which == "bottom") {}
  }

  function getPageHeader() {
    if ($_REQUEST['action'] == 'edit') {
      include_once dirname( __FILE__ ) . '/admin-edit.php';
      return;
    }
    return null;
  }

  function get_columns() {
    return $columns = array(
      'action' => 'Action',
      'display_name' => 'User',
      'name' => 'Name',
      'lat' => 'Lat',
      'lng' => 'Lng',
      'filters' => 'Filters'
    );
  }

  function get_sortable_columns() {
    return $sortable_columns = array(
      'display_name' => array('display_name', false),
      'name' => array('Name', true)
    );
  }

  function get_request_from_post($post) {
    $fieldsToArray = array('filters', 'tags', 'imgs');
    $request = $post;
    foreach ($fieldsToArray as $field) {
      $request[$field] = explode(',', $request[$field]);
    }
    return $request;
  }

  function handle_actions($action) {
    $recordId = $_REQUEST['id'];
    $wpNonce = $_REQUEST['_wpnonce'];
    if (empty($wpNonce) || !wp_verify_nonce($wpNonce, 'action_' . $recordId)) die('no soup for you');

    global $wpdb;

    switch ($action) {
      case 'edit_pin':
        if ($_POST['submit'] != 'Save') return;
        $request = $this->get_request_from_post($_POST);
        $processor = new ProcessPin($request, $this->user_is_admin());
        if (!$processor->validate()) die('not a valid request');
        $processor->generate_request();
        $processor->run();
        break;
      case 'toggle':
        $value = $_REQUEST['value'];
        $wpdb->update(
          'pp_pins',
          array(
            'show_on_map' => $value
          ),
          array('ID' => $recordId),
          array('%d'),
          array('%d')
        );
        break;
    };
  }

  function prepare_items() {
    if (!empty($_REQUEST['action'])) $this->handle_actions($_REQUEST['action']);

    global $wpdb;

    $query = "SELECT p.ID as ID, p.name as name, p.lat as lat, p.lng as lng,
                     p.show_on_map as show_on_map, u.display_name as display_name
              FROM   pp_pins p INNER JOIN wp_users u
                       on p.user_ID = u.ID";

    if (!empty($_GET["orderby"])) {
      $query .= ' ORDER BY ' . $_GET["orderby"] . ' ' . ($_GET["order"] ? $_GET["order"] : ' ASC') . ', p.ID ASC';
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
      foreach($records as $record){

        echo '<tr id="record_'.$record->id.'">';
        foreach ( $columns as $column_name => $column_display_name ) {
          echo '<td>';
          switch ($column_name) {
            case 'action':
              echo $this->getColumnActions($record); break;
            default:
              echo $record->$column_name;
          }
          echo '</td>';
        }
        echo'</tr>';
      }
    }
  }

  function getColumnActions($record) {
    $recordId = $record->ID;
    $actionNonce = wp_create_nonce('action_' . $recordId);

    $pageIdNonce = 'page=pp-admin&id=' . $recordId . '&_wpnonce=' . $actionNonce;

    $functions = array();

    array_push($functions, '<a href="?' . $pageIdNonce . '&action=edit">Edit</a>');

    $toggleText = $record->show_on_map == 0 ? 'Activate' : 'Deactivate';
    $toggleValue = $record->show_on_map == 0 ? 1 : 0;
    array_push($functions, '<a href="?' . $pageIdNonce . '&action=toggle&value=' . $toggleValue . '">' . $toggleText . '</a>');

    array_push($functions, '<a href="?' . $pageIdNonce . '&action=geocode">Geocode</a>');

    return join('&nbsp;|&nbsp;', $functions);
  }
}

$pin_table = new Pin_Table();
$pin_table->prepare_items();
?>

<div class="wrap">
  <?php $pin_table->display(); ?>
</div>
