<?php

class User_Edit_Form {

  function __construct() {
  }

  function get_edit_nonce() {
    return wp_create_nonce('user_' . $this->recordId);
  }

  function get_record_id() {
    return $this->recordId;
  }

  function from_JSON($value) {
    return join(',', json_decode($value));
  }

  function get_columns() {
    return array(
      'name' => array('Name', true),
      'lat' => array('Latitude', true),
      'lng' => array('Longitude', true),
      'address' => array('Address', true),
      'description' => array('Description', true),
      'show_on_map' => array('Approved for Display', false),
      'filters' => array('Filters', true, 'from_JSON')
    );
  }

  function prepare_items() {
    $recordId = $_REQUEST['id'];
    $this->recordId = $recordId;

    if (empty($recordId)) {
      $this->record = null;
      return;
    }

    global $wpdb;
    $rows = $this->get_columns();

    $query = "SELECT " . join(',', array_keys($rows)) . " FROM pp_pins WHERE ID = " . $recordId . ' AND user_ID = ' . get_current_user_id();

    $this->record = $wpdb->get_results($query)[0];
  }

  function display() {
    $record = $this->record;
    $rows = $this->get_columns();

    foreach ($rows as $key => $value) {
      echo '<div>';
      echo '<div class="form-label"><label for="' . $key . '">' . $value[0] . '</label></div>';
      echo $this->getValueRow($key, $value, $record);
      echo '</div>';
    }

    echo '</table>';
  }

  function getValueRow($key, $value, $record) {
    $recordValue = $record->$key != null ? $record->$key : null;
    $recordValue = htmlspecialchars(empty($value[2]) ? $recordValue : $this->{$value[2]}($recordValue));
    echo '<div class="form-value">';
    if ($value[1]) {
      echo '<input name="' . $key . '" class="regular-text" value="' . $recordValue . '"/>';
    } else {
      echo $recordValue;
    }
    echo '</div>';
  }
}

$table = new User_Edit_Form();
$table->prepare_items();
?>

  <form method="POST" action="<?php echo $url=strtok($_SERVER["REQUEST_URI"],'?'); ?>">
  <input type="hidden" name="action" value="edit_pin" />
  <input type="hidden" name="_wpnonce" value="<?php echo $table->get_edit_nonce(); ?>" />
  <input type="hidden" name="id" value="<?php echo $table->get_record_id(); ?>" />

  <?php $table->display(); ?>

  <input type="submit" name="submit" value="Save" class="button button-primary"/>
</form>
