<?php
global $wpdb;

include_once dirname( __FILE__ ) . '/pin-edit.php';
include_once dirname( __FILE__ ) . '/user-pin-list.php';

class UserPins {

  function __construct() {
    $this->isEditing = false;
  }

  function handleAction() {
    $recordId = $_REQUEST['id'];
    $wpNonce = $_REQUEST['_wpnonce'];
    if (empty($wpNonce) || !wp_verify_nonce($wpNonce, 'user_' . $recordId)) die('You do not have sufficient permissions to access this page.');

    switch ($_REQUEST['action']) {
      case 'edit':
        $this->isEditing = true;
        break;

      case 'edit_pin':
        if ($_POST['submit'] != 'Save Pin') return;
        echo "Saving... Please wait.";
        $processor = new ProcessPin(stripslashes_deep($_POST), $_FILES, false);
        $validation = $processor->validate();
        if ($validation !== true) die($validation);
        $processor->upsert_pin();
        $this->reloadPage();
        break;

      case 'del':
        global $wpdb;
        $table_name = $wpdb->prefix . 'pp_pins';

        $wpdb->delete(
          $table_name,
          array('ID' => $recordId),
          array('%d')
        );
        break;
    }
  }

  function reloadPage() {
    $username = $this->getUserName();
    $pinsPage = "/community/members/$username/pins";
    echo "
    <script type='text/javascript'>
      location.href = '$pinsPage';
    </script>";
  }

  function getPinUrlFragment($value) {
    $wpNonce = wp_create_nonce('user_' . $value);
    return 'id=' . $value . '&_wpnonce=' . $wpNonce;
  }

  function getUserName() {
    $user = wp_get_current_user();
    return $user->user_nicename;
  }

  function renderPage() {
    if (!empty($_REQUEST['action'])) $this->handleAction();

    if ($this->isEditing) {
      include dirname(__FILE__) . '/user-pin-edit.php';
    } elseif (bp_displayed_user_id() == get_current_user_id()) {
      $newUrlFragment = $this->getPinUrlFragment('');
      $username = $this->getUserName();

      echo "<a href='/community/members/$username/pins/?action=edit&$newUrlFragment' class='pin-add__button'>Add map pin</a>";
    }

    $userPinList = new UserPinList(bp_displayed_user_id());
    $userPinList->displayItems();
  }
}

$pinView = new UserPins();
$pinView->renderPage();
?>
