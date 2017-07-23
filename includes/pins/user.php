<?php
global $wpdb;

include_once dirname( __FILE__ ) . '/pin-edit.php';

class UserPinTable {

  function __construct() {
    $this->isEditing = false;
  }

  function get_request_from_post($post) {
    $fieldsToArray = array('filters');
    $request = $post;
    //TODO handle input elements with multiple elements
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
        if ($_POST['submit'] != 'Save Pin') return;
        $request = $this->get_request_from_post($_POST);
        $processor = new ProcessPin($request, $_FILES, false);
        $validation = $processor->validate();
        if ($validation !== true) die($validation);
        $processor->upsert_pin();
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
    return $value == 'APPROVED' ? 'Approved' : 'Waiting Approval';
  }

  function getItems() {
    if (!empty($_REQUEST['action'])) $this->doAction();

    global $wpdb;
    $userId = bp_displayed_user_id();
    print_r($userId);

    $query = "SELECT ID, name, description, approval_status FROM pp_pins where user_ID = $userId";

    $this->items = $wpdb->get_results($query);
  }

  function displayItems() {
    $newUrlFragment = $this->getPinUrlFragment('');
    echo "<a href='?action=edit&$newUrlFragment' class='pin-add__button'>Add New Pin</a>";

    if ($this->isEditing) include dirname(__FILE__) . '/user-edit.php';
    $records = $this->items;
    echo "<pre>";
    print_r($records);
    echo "</pre>";

    echo "<ul class='pin-list'>";

    foreach ($records as $record) {
      $published = $this->getStatusFromSOM($record->approval_status);
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

    echo "</ul>";

  }
}

$pinTable = new UserPinTable();
$pinTable->getItems();
?>

<?php $pinTable->displayItems(); ?>
