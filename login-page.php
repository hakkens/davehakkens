<?php
/*
 * Template Name: Login Page
 * Description: Login page
 */


get_header();

get_template_part('navbar'); ?>

  <div class="forum-sidebar">
    <?php dynamic_sidebar('forum-sidebar'); ?>
  </div>

  <div id="content">

    <div class="post login">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <h1><?php the_title(); ?></h1>

        <div class="post-content">
          <?php if ($_GET['action'] == 'reset_success'): ?>
            <p>Password successfully reset. In a few moments you will receive a new password by email.</p>
          <?php endif; ?>

          <?php if ($_GET['action'] == 'no_valid_key'): ?>
            <p>Unable to reset your password. The key provided is invalid, probably because it's been used before.</p>
          <?php endif; ?>

          <?php the_content(); ?>
          <?php if ($_GET['login'] == 'failed'): ?>
            <p class="centered">Wrong username / password!</p>
          <?php endif; ?>
          <?php wp_login_form(array('redirect' => 'http://davehakkens.nl/community/forums/')); ?>
          <div class="forgot_password">
            <a href="/community/forgot-password/">Forgot your password?</a>
          </div>
          <div class="forgot_password">
            <a href="/community/register">Register</a>
          </div>

        </div>

      <?php endwhile; endif; ?>
    </div>

    <div class="alt-forum-sidebar">
      <?php dynamic_sidebar('forum-sidebar'); ?>
    </div>

  </div>

<?php get_footer(); ?>