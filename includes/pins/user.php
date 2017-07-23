<?php
global $wpdb;

include_once dirname( __FILE__ ) . '/pin-edit.php';
include_once dirname( __FILE__ ) . '/user-pin-list.php';

class UserPins {

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

  function handleAction() {
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

  function renderPage() {
    if (!empty($_REQUEST['action'])) $this->handleAction();

    if ($this->isEditing) {
      include dirname(__FILE__) . '/user-pin-edit.php';
    } else {
      $newUrlFragment = $this->getPinUrlFragment('');
      echo "<a href='?action=edit&$newUrlFragment' class='pin-add__button'>Add New Pin</a>";
    }

    $userPinList = new UserPinList(bp_displayed_user_id());
    $userPinList->displayItems();
  }
}

$pinView = new UserPins();
$pinView->renderPage();
?>
