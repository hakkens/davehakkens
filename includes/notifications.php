<?php

add_action('dh_before_notifications_loop', 'do_dh_before_notifications_loop');
function do_dh_before_notifications_loop() {
  ?>
    <form method="POST" action="<?php echo bp_loggedin_user_domain() . 'notifications/'; ?>">
      <input type="submit" name="clear_messages" value="clear notifications" class="button-delete-notifications"/>
    </form>
  <?php
}

add_action('dh_before_notifications', 'do_dh_before_notifications');
function do_dh_before_notifications() {
  if (isset($_POST['clear_messages'])) {
    BP_Notifications_Notification::mark_all_for_user(get_current_user_id());
    echo "<script type='text/javascript'>location.href='" . bp_loggedin_user_domain() . "notifications/';</script>";
  }
}

?>
