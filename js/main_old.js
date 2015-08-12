var DaveHakkens = window.DaveHakkens || {};

DaveHakkens.Main = function(){

  var $;
  var $mobileMenu;
  var $menuToggle;
  var $postGrid;
  var $postFilter;
  var $usermenu;
  var $usermenuToggle;
  var $usermenuContent;
  var $projects;
  var $window;
  var $loader;
  var loading;
  var page;

  var userMenuTimeout;

  var init = function(){

    $ = jQuery.noConflict();
    $mobileMenu = $('#mobile-menu');
    $menuToggle = $('#menu-toggle');
    $postGrid = $('#post-grid');
    $postFilter = $('#post-filter');
    $usermenu = $('#user-menu');
    $usermenuToggle = $('#user-menu .user-toggle');
    $usermenuContent = $('#user-menu .content');
    $projects = $('.projects-page');
    $loader = $('#overlay');
    $window = $(window);
    loading = true;
    page = 1;

    initBreadcrumbs();
    initMobileMenu();
    initIsotopeGrid();
    initFancybox();
    initUserMenu();
    initProjects();
    initLogin();
    initWidgets();

    lazyLoadYt();
    initAjaxLoader();

    if (!$('body').hasClass('no-touch')){
      $('.buddypress #content .post, .bbpress #content .post').bind('mousewheel', function(event) {
        event.preventDefault();
        var scrollTop = this.scrollTop;
        this.scrollTop = (scrollTop + ((event.deltaY * event.deltaFactor) * -1));
        //console.log(event.deltaY, event.deltaFactor, event.originalEvent.deltaMode, event.originalEvent.wheelDelta);
      });
    }
  };

  var initAjaxLoader = function(){
    $(document).ajaxStart(function(){
      $loader.show();
    });
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
    if ($projects.length > 0) $projects.snapscroll();

    $('#project-nav').css({ marginTop: ($('#project-nav').height() / 2 * -1) });

    $('#project-nav a').click(function(e){
      e.preventDefault();
      $('#project-nav a.current').removeClass('current');
      $(this).addClass('current');
      var elm_id = $(this).attr('href');
      $('html, body').animate({ scrollTop: $(elm_id).offset().top }, 1000);
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
       $content.isotope();
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
    $('a.fancybox').fancybox();
  };

  var initIsotopeGrid = function(){
    $postGrid.isotope({
      percentPosition: true,
      masonry: { columnWidth: '.item' },
      animationEngine: 'jquery'
    });

    $(document).on('click', '#post-filter a, #post-grid .item .post_meta a', function(){
      var category = '.' + $(this).attr('href').replace('#', '');
      console.log(category);
      $postFilter.find('.active').removeClass('active');
      $(this).parent().addClass('active');

      if (category == '.'){
        category = '*';
      }
      $postGrid.isotope({ filter: category });
    });
    $postGrid.isotope( 'on', 'arrangeComplete', function(filteredItems){
      if(filteredItems.length === 0){
        loadPosts();
      }
    });
    if ($postGrid.length > 0) {
      $window.scroll(function () {
        if (!loading && ($(window).scrollTop() + $(window).height() == $(document).height())) {
          loading = true;
          page++;
          loadPosts();
        }
      });
      loadPosts();
    }
  };

  var loadPosts = function(){

    var hash = window.location.hash.replace('#', '');
    console.log(page);
    $('#overlay').show();
    $.ajax({
      type       : "GET",
      data       : {numPosts : 10, pageNumber: page, tag: hash},
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

  DaveHakkens.Main.init();

});