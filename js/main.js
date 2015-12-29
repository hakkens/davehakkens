var DaveHakkens = window.DaveHakkens || {};

DaveHakkens.Main = function(){

  var $;
  var $mobileMenu;
  var $menuToggle;
  var $postGrid;
  var $postFilter;
  var $usermenu;
  var $usermenuContent;
  var $projects;
  var $window;
  var $loader;
  var loading;
  var page;
  var skipPosts;
  var userMenuTimeout;

  var init = function(){

    $ = jQuery.noConflict();
    $mobileMenu = $('#mobile-menu');
    $menuToggle = $('#menu-toggle');
    $postGrid = $('#post-grid');
    $postFilter = $('#post-filter');
    $usermenu = $('#user-menu');
    $usermenuContent = $('#user-menu .content');
    $projects = $('.projects-page');
    $loader = $('#overlay');
    $window = $(window);
    loading = true;
    page = 1;
    skipPosts = '';

    initBreadcrumbs();
    initMobileMenu();
    initIsotopeGrid();
    initFancybox();

    initProjects();
    initLogin();
    initWidgets();

    lazyLoadYt();
    lazyLoadVimeo();
    lazyLoadVine();

    initAjaxLoader();

    if (!$('body').hasClass('no-touch')){
      $('.buddypress #content .post, .bbpress #content .post').bind('mousewheel', function(event) {
        event.preventDefault();
        var scrollTop = this.scrollTop;
        this.scrollTop = (scrollTop + ((event.deltaY * event.deltaFactor) * -1));
        //console.log(event.deltaY, event.deltaFactor, event.originalEvent.deltaMode, event.originalEvent.wheelDelta);
      });
    }

    $('.bbpress #content a').each(function(){

      var $this = $(this);
      var href = $this.attr('href');

      if( !href ) {
        return;
      }

      if (href.indexOf('davehakkens') < 0 && href.indexOf('community') < 0 && (href.indexOf('#') < 0 || href.indexOf('#') > 0)){
        $this.attr('target', '_blank');
      }

    });

  };

  var initAjaxLoader = function(){

    $(document).ajaxStop(function(){
      $loader.hide();
    });

  };

  var initLogin = function(){
    $('#loginform').submit(function(){
      if ($('#user_login').val() == '' && $('#user_pass').val() == '') {
        return false;
      }
    });
  };

  var initWidgets = function(){
    $('.widget img.avatar').each(function(){
      var $this = $(this);
      var src = $this.attr('src').replace('s=14', 's=128');
      $this.attr('src', src);
    });
  };

  var initBreadcrumbs = function(){
    $('#breadcrumbs .breadcrumb_last').parent().find('a').addClass('one-but-last');

    if ($('#breadcrumbs a').length == 1){
      $('#breadcrumbs a').addClass('breadcrumb_last').css({ paddingLeft: '20px' });
    }
  };

  var initProjects = function(){

    if( $projects.length > 0 ) {
      $projects.fullpage({
        navigation: true
      });
    }

    $('#project-nav').css({ marginTop: ($('#project-nav').height() / 2 * -1) });

    $('#project-nav a').click(function(e){
      e.preventDefault();
      $('#project-nav a.current').removeClass('current');
      $(this).addClass('current');
      var elm_id = $(this).attr('href');
      $('html, body').animate({ scrollTop: $(elm_id).offset().top }, 1000);
    });
  };

  var lazyLoadVine = function(){

    $(document).on('click', '.vine-container a', function(e){

      e.preventDefault();

      var $this = $(this);
      var videocode = $this.attr('href');
      var $container = $this.parent();
      var $content = $("#post-grid");
      var width = $container.width();
      var height = width;

      var video_iframe = '<iframe src="https://vine.co/v/' + videocode + '/embed/simple" width="' + width + '" height="' + height + '" frameborder="0"></iframe><script src="https://platform.vine.co/static/scripts/embed.js"></script>';

      $container.html(video_iframe);
    });
  };

  var lazyLoadVimeo = function(){

    $(document).on('click', '.vimeo-container a', function(e){

      e.preventDefault();

      var $this = $(this);
      var videocode = $this.attr('href');
      var $container = $this.parent();
      var $content = $("#post-grid");

      var video_iframe = '<iframe src="https://player.vimeo.com/video/' + videocode + '?autoplay=1&color=ffffff" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

      $container.html(video_iframe);

    });
  };

  var lazyLoadYt = function(){

     $(document).on('click', '.youtube-container a', function(e){

       e.preventDefault();

       var $this = $(this);
       var videocode = $this.attr('href');
       var $container = $this.parent();
       var $content = $("#post-grid");

       var video_iframe = '<iframe class="yt" width="100%" height="350" src="https://www.youtube.com/embed/' + videocode + '?autoplay=1" frameborder="0" allowfullscreen></iframe>';

       $container.html(video_iframe);

     });
  };

  var initUserMenu = function(){
    $usermenuToggle.click(function(e){
      e.preventDefault();
      if ($usermenuContent.is(':visible')){
        $usermenuContent.hide();
      }else{
        $usermenuContent.show();
      }
    });
    $usermenu.hover(function(){
      clearTimeout(userMenuTimeout);
    }, function(){
      userMenuTimeout = setTimeout(function(){
        $usermenuContent.hide();
      }, 1000);
    });
  };

  var initFancybox = function(){
    $('a.fancybox, a[rel="lightbox"]').fancybox({
      padding: 0,
      margin: 0
    });
  };

  var initIsotopeGrid = function(){
    $postGrid.isotope({
      percentPosition: true,
      masonry: { columnWidth: '.item' },
      animationEngine: 'jquery'
    });

    $(document).on('click', '#post-filter a, #post-grid .item .post_meta a', function(){
      var category = '.' + $(this).attr('href').replace('#', '');
      $postFilter.find('.active').removeClass('active');
      $(this).parent().addClass('active');

      if (category == '.'){
        category = '*';
      }
      $postGrid.isotope({ filter: category });

      if($('#post-grid .item' + category).length < 10){
        setTimeout(function(){
          skipPosts = '';
          $('#post-grid .item' + category).each(function(){
            var id = $(this).attr('id').replace('post-', '');
            skipPosts += id + '|';
          });
          skipPosts = skipPosts.substr(0, (skipPosts.length-1));
          page = 1;
          loadPosts(10 - $('#post-grid .item' + category).length);
        }, 500);

      }
    });
    if ($postGrid.length > 0) {
      $window.scroll(function () {
        if (!loading && ($(window).scrollTop() + $(window).height() == $(document).height())) {
          loading = true;
          page++;
          loadPosts(10);
        }
      });
      loadPosts(10);
    }
  };

  var loadPosts = function(numPosts){

    var hash = window.location.hash.replace('#', '');

    $('#overlay').show();

    $.ajax({
      type       : "GET",
      data       : {numPosts : numPosts, pageNumber: page, tag: hash, skipPosts: skipPosts},
      dataType   : "html",
      url        : homeURL + "/wp-content/themes/davehakkens/loopHandler.php",
      beforeSend : function(){
      },
      success    : function(data){
        $data = $(data);

        $postGrid.append($data).isotope('appended', $data);

        setTimeout(function(){
          $postGrid.isotope();
        }, 750);

        loading = false;
      },
      error     : function(jqXHR, textStatus, errorThrown) {
        alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
      }
    });
  };

  var initMobileMenu = function(){
    $menuToggle.click(function(e){
      e.preventDefault();
      if (parseInt($mobileMenu.css('top')) < 0){
        $mobileMenu.animate({ top: 50 }, 'fast');
      }else{
        $mobileMenu.animate({ top: -400 }, 'fast');
      }
    });
  };

  return {
    init: init,
    initIsotopeGrid: initIsotopeGrid
  };

}();

jQuery(document).ready(function(){

  var $ = jQuery;
  DaveHakkens.Main.init();

  $( '.list-replies li' ).each( function( reply ) {

    var replies = $( this ).find( 'ul.bbp-threaded-replies' );

    if( replies.length == 0 ) {
      return;
    }

    var button = $( this ).find( '> .topic-reply [href="#toggle-replies"]' ),
        oldContent = button.html(),
        howMany = replies.find( '> li' ).length;

    button.addClass( 'activated' );
    button.html( oldContent + ' (' + howMany + ')' );

  });

  $( '.list-replies li [href="#toggle-replies"]' ).click( function(event) {
    $( this ).toggleClass( 'on' );
    $( this ).closest( '.topic-reply' ).next( '.bbp-threaded-replies' ).slideToggle();
    event.preventDefault();
  });

});
