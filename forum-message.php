<?php
$user_id = get_current_user_id();
$user = get_userdata($user_id);

if (isset($_REQUEST['remove-pin-message'])) {
  update_user_meta($user_id, 'hide_add_pin_message', true);
}

if (!get_user_meta($user_id, 'hide_add_pin_message', true)) : ?>
<div class="page-message page-message__addpin">
  <p>We've just launched our map. Add yourself by <a href="/community/members/<?php echo $user->user_nicename; ?>/pins/">clicking here!</a></p>
  <a href="/community/forums/?remove-pin-message=true" target="_self" class="page-message--close">
    <img src="<?php bloginfo('template_url'); ?>/img/icon/logout.png" class="page-message--close_image" alt="close" />
  </a>
</div>
<?php endif; ?>
