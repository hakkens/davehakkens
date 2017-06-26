<?php
global $wpdb;

class UserPinTable {

  function __construct() {}

  function doAction() {
    global $wpdb;
    $recordId = $_REQUEST['id'];
    $wpNonce = $_REQUEST['_wpnonce'];

    if ($_REQUEST['action'] == 'del') {
      if (empty($_REQUEST['_wpnonce']) ||
        !wp_verify_nonce($wpNonce, 'del_' . $recordId)) die('no soup for you');

      $wpdb->delete(
        'pp_pins',
        array('ID' => $recordId),
        array('%d')
      );
    }
  }

  function getColumns() {
    return array(
      'Action' => array('ID', 'getPinActions'),
      'Name' => 'name',
      'Status' => array('show_on_map', 'getStatusFromSOM')
    );
  }

  function getPinActions($value) {
    $delNonce = wp_create_nonce('del_' . $value);

    return '
      <a href="?action=edit&id=' . $value . '">Edit</a>
      &nbsp;|&nbsp;
      <a href="?action=del&id=' . $value . '&_wpnonce=' . $delNonce . '">Del</a>';
  }

  function getStatusFromSOM($value) {
    return $value ? 'Approved' : 'Waiting Approval';
  }


  function getItems() {
    if (!empty($_REQUEST['action'])) $this->doAction();

    global $wpdb;
    $user = wp_get_current_user();

    $query = "SELECT ID, name, show_on_map FROM pp_pins where user_ID = $user->ID";

    $this->columns = $this->getColumns();
    $this->items = $wpdb->get_results($query);
  }

  function displayItems() {
    $records = $this->items;
    $columns = $this->columns;

    $columnHeaders = array_keys($columns);
    echo "<table><tr><th>" . join('</th><th>', $columnHeaders) . "</th></tr>";

    foreach ($records as $record) {
      echo "<tr>";
      foreach ($columns as $column) {
        $value = $this->getValueFromColumn($record, $column);
        echo "<td>$value</td>";
      }
      echo "</tr>";
    }

    echo "</table>";
  }

  function getValueFromColumn($record, $column) {
    if (is_array($column)) {
      return $this->{$column[1]}($record->{$column[0]});
    }
    return $record->{$column};
  }
}

$pinTable = new UserPinTable();
$pinTable->getItems();

$pinTable->displayItems();
?>
