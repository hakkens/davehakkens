<?php
global $wpdb;

include_once dirname( __FILE__ ) . '/pin-edit.php';

class UserPinTable {

  function __construct() {
    $this->isEditing = false;
  }

  function get_request_from_post($post) {
    $fieldsToArray = array('filters', 'tags', 'imgs');
    $request = $post;
    foreach ($fieldsToArray as $field) {
      $request[$field] = explode(',', $request[$field]);
    }
    return $request;
  }

  function doAction() {
    $recordId = $_REQUEST['id'];
    $wpNonce = $_REQUEST['_wpnonce'];
    if (empty($wpNonce) || !wp_verify_nonce($wpNonce, 'user_' . $recordId)) die('nice try big guy');

    switch ($_REQUEST['action']) {
      case 'edit':
        $this->isEditing = true;
        break;

      case 'edit_pin':
        //TODO do pin upload?
        if ($_POST['submit'] != 'Save') return;
        $request = $this->get_request_from_post($_POST);
        $processor = new ProcessPin($request, false);
        if (!$processor->validate()) die('not a valid request');
        $processor->generate_request();
        $processor->run();
        break;

      case 'del':
        global $wpdb;
        $wpdb->delete(
          'pp_pins',
          array('ID' => $recordId),
          array('%d')
        );
        break;
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
    $wpNonce = wp_create_nonce('user_' . $value);

    $idAndNonce = 'id=' . $value . '&_wpnonce=' . $wpNonce;

    return '
      <a href="?action=edit&' . $idAndNonce . '">Edit</a>
      &nbsp;|&nbsp;
      <a href="?action=del&' . $idAndNonce . '">Del</a>';
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
    if ($this->isEditing) include dirname(__FILE__) . '/user-edit.php';
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
