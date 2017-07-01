<?php

class Admin_Edit_Form {

  function __construct() {
  }

  function getColumns() {
    return array(
      'ID' => 'Record ID',
      'user_ID' => 'User ID',
      'name' => 'Name',
      'lat' => 'Latitude',
      'lng' => 'Longitude',
      'address' => 'Address',
      'description' => 'Description',
      'show_on_map' => 'Approved for Display'
    );
  }

  function prepare_items() {
    $recordId = $_REQUEST['id'];

    if (empty($recordId)) {
      $this->record = null;
      return;
    }

    global $wpdb;
    $rows = $this->getColumns();

    $query = "SELECT " . join(', ',array_keys($rows)) . " FROM pp_pins WHERE ID = " . $recordId;

    $this->record = $wpdb->get_results($query)[0];
  }

  function display() {
    $record = $this->record;
    $rows = $this->getColumns();

    echo '<table class="form-table">';

    foreach ($rows as $key => $row) {
      echo '<tr scope="row">';
      echo "<th><label for=\"$key\">$row</label></th>";
      echo $this->getValueRow($key, $row, $record);
      echo '</tr>';
    }

    echo '</table>';
  }

  function getValueRow($key, $row, $record) {
    $value = $record->$key != null ? $record->$key : '';

    echo '<td>';
    echo '<input name="' . $key . '" class="regular-text" value="' . $value . '"/>';
    echo '</td>';
  }
}

$table = new Admin_Edit_Form();
$table->prepare_items();

$table->display();
?>
