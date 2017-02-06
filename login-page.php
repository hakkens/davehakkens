<?php

/*
  Template Name: Login Page
  Description: Login page
*/

get_header();
get_template_part( 'navbar' );

?>

<div id="content">
  <div class="post login">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


      <div class="post-content">
         <div class="loginamigo"><h1>hello amigo<h1></div>

        <?php if( $_GET['action'] == 'reset_success' ): ?>
          <p> Hooray, password is reset. It should takes just a few moments before you get a new password in your mail</p>
        <?php endif; ?>

        <?php if( $_GET['action'] == 'no_valid_key' ): ?>
          <p>Oh gosh. We're unable to reset your password. The key is invalid, probably because it's been used before.</p>
        <?php endif; ?>

        <?php the_content(); ?>

        <?php if( $_GET['login'] == 'failed' ): ?>
          <p class="centered">Wrong username / password!</p>
        <?php endif; ?>

        <?php wp_login_form( [ 'redirect' => 'https://davehakkens.nl/community/forums/' ] ); ?>

        <div class="forgot_password">
          <a href="/community/forgot-password/">Forgot your password?</a>
        </div>

        <div class="forgot_password">
          <a href="/community/register">Register</a>
        </div>

      </div>

    <?php endwhile; endif; ?>

  </div>
</div>
</br>
</br>
</br>
</br>

<?php get_footer(); ?>
