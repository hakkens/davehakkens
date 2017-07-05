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

  function getPinUrlFragment($value) {
    $wpNonce = wp_create_nonce('user_' . $value);
    return 'id=' . $value . '&_wpnonce=' . $wpNonce;
  }

  function getStatusFromSOM($value) {
    return $value ? 'Approved' : 'Waiting Approval';
  }

  function getItems() {
    if (!empty($_REQUEST['action'])) $this->doAction();

    global $wpdb;
    $user = wp_get_current_user();

    $query = "SELECT ID, name, description, show_on_map FROM pp_pins where user_ID = $user->ID";

    $this->items = $wpdb->get_results($query);
  }

  function displayItems() {
    if ($this->isEditing) include dirname(__FILE__) . '/user-edit.php';
    $records = $this->items;

    foreach ($records as $record) {
      $published = $this->getStatusFromSOM($record->status);
      $desc = substr($record->description, 0, 45);
      $urlFragment = $this->getPinUrlFragment($record->ID);

      echo "<li class='pin-item'>
      <div class='pin-item__actions'>
        <a href='?action=edit&$urlFragment' class='pin-item__button'>Edit</a>
        <a href='?action=del&$urlFragment' class='pin-item__button'>Delete</a>
      </div>
      <h3 class='pin-item__title'>$record->name</h3>
      <p class='pin-item__text'>$record->description</p>
      <p class='pin-item__text pin-item__status'>$published</p>
      </li>";
    }

  }
}

$pinTable = new UserPinTable();
$pinTable->getItems();
?>

  <ul class="pin-list">
    <?php $pinTable->displayItems(); ?>
  </ul>