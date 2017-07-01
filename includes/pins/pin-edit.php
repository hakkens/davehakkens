<?php

class ProcessPin {

  function __construct($request) {
    $this->request = $request;
    $this->recordId = $request['id'];
    $this->isCreate = empty($this->recordId) ? true : false;
  }

  function get_columns() {
    return array(
      'name' => '%s',
      'address' => '%s'
    );
  }

  function user_is_admin() {
    $user = wp_get_current_user();
    return in_array('administrator', (array) $user->roles);
  }

  function get_current_record($recordId) {
    global $wpdb;
    return $wpdb->get_results('select ID, user_ID from pp_pins where ID = ' . $recordId);
  }

  function validate_request() {
    if (!is_user_logged_in()) die('no soup for you');
    $recordId = $this->recordId;

    $wpNonce = $this->request['_wpnonce'];
    if (empty($wpNonce) || !wp_verify_nonce($wpNonce, 'action_' . $recordId)) die('no soup for you');

    $userIsAdmin = $this->user_is_admin();
    if (!$userIsAdmin && !$this->isCreate) {
      $currentRecord = $this->get_current_record($recordId);
      if ($curretRecord->ID != $recordId) die('no soup for you');
    }

    $record = array();
    $formats = array();
    $request = $this->request;

    $columns = $this->get_columns();

    foreach ($columns as $key => $value) {
      if (!empty($request[$key])) {
        $record[$key] = $request[$key];
        array_push($formats, $value);
      }
    }

    $record['show_on_map'] = $this->user_is_admin() && $request['show_on_map'] == '1';
    array_push($formats, '%d');

    $this->record = $record;
    $this->formats = $formats;
  }

  function run_process() {
    global $wpdb;
    if ($this->request['submit'] != 'Save') return;
    $wpdb->show_errors();
    if ($this->isCreate) {
      $wpdb->insert(
        'pp_pins',
        $this->record,
        $this->formats
      );
    } else {
      $wpdb->update(
        'pp_pins',
        $this->record,
        array('ID' => $this->recordId),
        $this->formats,
        array('%d')
      );
    }
  }
}

$processor = new ProcessPin($_POST);
$processor->validate_request();
$processor->run_process();

?>
