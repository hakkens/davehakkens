<div id="message" class="info">

  <?php if ( bp_is_current_action( 'unread' ) ) : ?>

    <?php if ( bp_is_my_profile() ) : ?>

      <p>No notifications here, all clean! 👌</p>

    <?php else : ?>

      <p><?php _e( 'This member has no notifications.', 'buddypress' ); ?></p>

    <?php endif; ?>

  <?php else : ?>

    <?php if ( bp_is_my_profile() ) : ?>

      <p>No notifications here, all clean! 👌</p>

    <?php else : ?>

      <p><?php _e( 'This member has no notifications.', 'buddypress' ); ?></p>

    <?php endif; ?>

  <?php endif; ?>

</div>
