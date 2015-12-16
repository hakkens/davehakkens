<?php

/*
  Template Name: Redirect Page
  Description: Page that redirects towards community
*/

header( 'HTTP/1.1 301 Moved Permanently' );
header( 'Location: http://davehakkens.nl/community/forums' );
exit;

?>
