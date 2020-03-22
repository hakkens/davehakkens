<?php

/*
  Template Name: Login Page
  Description: Login page
*/

if (is_user_logged_in()) {
  wp_redirect(login_redirect_control(null, $_SERVER['REQUEST_URI'], wp_get_current_user()));
}

get_header();
get_template_part( 'navbar' );

?>



<div id="content">
  <div class="post login">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


      <div class="post-content">
         <div class="loginamigo"><h1>hello amigo</h1></div>


        <?php if( $_GET['action'] == 'reset_success' ): ?>
          <p> Hooray, password is reset. It should takes just a few moments before you get a new password in your mail</p>
        <?php endif; ?>

        <?php if( $_GET['action'] == 'no_valid_key' ): ?>
          <p>Oh gosh. We're unable to reset your password. The key is invalid, probably because it's been used before.</p>
        <?php endif; ?>

        <?php the_content(); ?>

        <?php if( $_GET['login'] == 'failed' ): ?>
          <p class="centered">Ohoh. wrong username or password buddy 🙊</p>
        <?php endif; ?>

        <?php wp_login_form(); ?>
        <?php do_action( 'anr_captcha_form_field' ); ?>

        <div class="forgot_password">
          <a href="/community/forgot-password/">Forgot your password?</a>
        </div>

        <div class="forgot_password">
          <a href="/community/register">R̶e̶g̶i̶s̶t̶e̶r̶ (we're closed, click for info)</a>
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
