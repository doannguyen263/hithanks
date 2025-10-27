(function ($) {
  $(".nav__mobile .el-language").click(function (e) {
    var dnselect__current = $(this).find(".dnselect__current");
    // e.preventDefault();
    dnselect__current.toggleClass("has_under_open");
    dnselect__current.next().toggleClass("show");
  });

  new Readmore(".js-readmore-content", {
    collapsedHeight: 100,
    speed: 75,
  });

  setTimeout(function () {
    var js_plus_get_height = $(".js-plus-get-height").height();
    $(".js-plus-get-height").css("height", js_plus_get_height + "px");
  }, 500);

  $(".js-plus .button-plus__wrap").click(function (e) {
    e.preventDefault();
    thiz_parent = $(this).closest(".home-nav__item");
    if (thiz_parent.hasClass("active")) {
      thiz_parent.removeClass("active");
      thiz_parent.find(".js-slidetoggle-content").slideUp("slow");
      thiz_parent.find(".card-meta").slideUp("slow").addClass("d-nonez");
    } else {
      thiz_parent.addClass("active");
      thiz_parent.find(".js-slidetoggle-content").slideDown("slow");
      thiz_parent.find(".card-meta").slideDown("slow").removeClass("d-nonez");
    }

    // Update slider height
    $(".main-carousel, .product-made-carousel").each(function () {
        const $this = $(this);
        if ($this.data("flickity")) {
        $this.flickity("resize");
        }
    });
  });

  function isEmpty(el) {
    return !$.trim(el.html());
  }

  // Sticky navbar
  // =========================

  // Custom function which toggles between sticky class (is-sticky)
  var stickyToggle = function (
    sticky,
    stickyWrapper,
    scrollElement,
    stickyHeight
  ) {
    var stickyTop = stickyWrapper.offset().top;
    if (
      scrollElement.scrollTop() >= stickyTop &&
      scrollElement.scrollTop() > 0
    ) {
      stickyWrapper.height(stickyHeight);
      sticky.addClass("is-sticky");
    } else {
      sticky.removeClass("is-sticky");
      stickyWrapper.height("auto");
    }
  };
  $('[data-toggle="sticky-onscroll"]').each(function () {
    var sticky = $(this);
    var stickyWrapper = $("<div>").addClass("sticky-wrapper"); // insert hidden element to maintain actual top offset on page
    sticky.before(stickyWrapper);
    sticky.addClass("sticky");
    var stickyHeight = sticky.outerHeight();
    // Scroll & resize events
    $(window).on("scroll.sticky-onscroll resize.sticky-onscroll", function () {
      stickyToggle(sticky, stickyWrapper, $(this), stickyHeight);
    });

    // On page load
    stickyToggle(sticky, stickyWrapper, $(window), stickyHeight);
    // Check scroll top
    var winSt_t = 0;
    $(window).scroll(function () {
      var winSt = $(window).scrollTop();
      if (winSt >= winSt_t) {
        sticky.removeClass("top_show");
      } else {
        sticky.addClass("top_show");
      }
      winSt_t = winSt;
    });
  });

  new WOW().init();

  //-------------------------------------------------
  // Header Search
  //-------------------------------------------------
  var $headerSearchToggle = $(".header__search--toggle");
  var $headerSearchForm = $(".header__search__form");

  $headerSearchToggle.on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    var $this = $(this);
    var $header_parent = $(this).closest(".header");
    var $header = $(this).closest(".header__search");

    $("#header-search-form .search-field").focus();
    if (!$header.hasClass("open-search")) {
      $header_parent.addClass("open-search");
      $header.addClass("open-search");
    } else {
      $("#header-search-form").submit();
    }
  });

  $("body").on("click", function (e) {
    // console.log(e.target)
    if (!$(e.target).hasClass("search-field")) {
      $(".header").removeClass("open-search");
      $(".header__search").removeClass("open-search");
    }
  });

  /*----Back to top---*/
  var back_to_top = $(".back-to-top"),
    offset = 220,
    duration = 500;
  $(window).scroll(function () {
    $(this).scrollTop() > offset
      ? back_to_top.addClass("active")
      : back_to_top.removeClass("active");
  }),
    $(document).on("click", ".back-to-top", function (o) {
      return (
        o.preventDefault(),
        $("html, body").animate({ scrollTop: 0 }, duration),
        !1
      );
    });

  //-------------------------------------------------
  // Menu
  //-------------------------------------------------
  $.fn.dnmenu = function (options) {
    console.log("Click menu");
    let thiz = this;
    let menu = $(this).attr("id");
    let menu_id = "#" + menu;
    var button = $('a[href="#' + menu + '"]');

    // Default options
    var settings = $.extend(
      {
        name: "John Doe",
      },
      options
    );

    // get ScrollBar Width
    function getScrollBarWidth() {
      var $outer = $("<div>")
          .css({ visibility: "hidden", width: 100, overflow: "scroll" })
          .appendTo("body"),
        widthWithScroll = $("<div>")
          .css({ width: "100%" })
          .appendTo($outer)
          .outerWidth();
      $outer.remove();
      return 100 - widthWithScroll;
    }
    let ScrollBarWidth = getScrollBarWidth() + "px";

    // Create wrap
    // Button click
    button.click(function (e) {
      e.preventDefault();
      console.log(button);
      if (button.hasClass("active")) {
        $(".dnmenu-backdrop").remove();
        button.removeClass("active");
        $(menu_id).removeClass("active");
      } else {
        $('<div class="dnmenu-backdrop">').appendTo("body");
        button.addClass("active");
        $(menu_id).addClass("active");
      }
    });
    // Menu
    var el = $(thiz).find(".nav__mobile--ul");
    el
      .find(".menu-item-has-children>a")
      .after('<button class="nav__mobile__btn"><i></i></button>'),
      // Close menu
      $("body").on("click", ".dnmenu-backdrop", function () {
        $(".dnmenu-backdrop").remove();
        button.removeClass("active");
        $(menu_id).removeClass("active");
      });

    el.find(".nav__mobile__btn").on("click", function (e) {
      e.stopPropagation(),
        $(this).parent().find(".sub-menu").first().is(":visible")
          ? $(this).parent().removeClass("sub-active")
          : $(this).parent().addClass("sub-active"),
        $(this).parent().find(".sub-menu").first().slideToggle();
    });

    // Apply options
    return;
  };
  $("#menu__mobile").dnmenu();

  /*************************/
  function isInt(n) {
    return parseInt(n) === n;
  }
  function number__toFixed(value) {
    return isInt(value) ? value : value.toFixed(1);
  }

  $(document).on("click", '.quantity.s1 input[type="button"]', quantity_input);

  function quantity_input(argument) {
    var input = $(this);
    var input_type = "plus";
    if ($(this).hasClass("minus")) input_type = "minus";

    var input_number = $(this).parent(".quantity").find('input[type="number"]');
    var input_number_val = input_number.val()
      ? parseFloat(input_number.val())
      : 0;
    // Get Setting
    var step = input_number.attr("step")
      ? parseFloat(input_number.attr("step"))
      : 1;
    var min = input_number.attr("min")
      ? parseFloat(input_number.attr("min"))
      : 0;
    var max = input_number.attr("max")
      ? parseFloat(input_number.attr("max"))
      : 999;

    if (input_type == "plus") result = number__toFixed(input_number_val + step);
    else result = number__toFixed(input_number_val - step);
    if (result >= min && result <= max) input_number.val(result).change();
  }

  function open_under(elem) {
    $(elem).click(function (e) {
      e.preventDefault();
      $(this).toggleClass("has_under_open");
      $(this).next().toggleClass("show");
    });
  }
  open_under(".widget_product_categories .widget-title");

  // Fix gallery product
  var r = jQuery(".product-thumbnails .first img").attr("data-src")
      ? jQuery(".product-thumbnails .first img").attr("data-src")
      : jQuery(".product-thumbnails .first img").attr("src"),
    s = jQuery("form.variations_form"),
    c = {
      selectSliderFirstImage: function () {
        jQuery(".product-gallery-slider").data("flickity") &&
          jQuery(".product-gallery-slider").flickity("select", 0);
      },
    };
  s.on("show_variation", function (t, e) {
    e.hasOwnProperty("image") && e.image.thumb_src
      ? (jQuery(
          ".product-gallery-slider-old .slide.first img, .product-thumbnails .first img, .product-gallery-slider .slide.first .zoomImg"
        )
          .attr("src", e.image.thumb_src)
          .attr("srcset", ""),
        c.selectSliderFirstImage())
      : jQuery(".product-thumbnails .first img").attr("src", r);
  }),
    s.on("click", ".reset_variations", function () {
      jQuery(".product-thumbnails .first img").attr("src", r),
        c.selectSliderFirstImage();
    });

  // $(document).ready(function(){
  //     Inputmask({"mask": "9999 999 999", "placeholder":" ", "clearMaskOnLostFocus": true }).mask('.js-tel-format');
  // });

  const triggerTabList = document.querySelectorAll("#myTab button");
  triggerTabList.forEach((triggerEl) => {
    const tabTrigger = new bootstrap.Tab(triggerEl);

    triggerEl.addEventListener("click", (event) => {
      event.preventDefault();
      tabTrigger.show();

      var id = $(event.target).attr("id");
      console.log(id);
      location.hash = id;
    });
  });

  $(".nav-tabs").find("li a").last().click();
  var url = document.URL;
  var hash = url.substring(url.indexOf("#"));
  $(".nav-tabs-list")
    .find("li button")
    .each(function (key, val) {
      if (hash == "#" + $(val).attr("id")) {
        $(val).click();
      }
    });

  // Page Scroll
  $(document).ready(function () {
    //smoothscroll
    $('.nav-dieuhuong a[href^="#"]').on("click", function (e) {
      e.preventDefault();
      $(document).off("scroll");

      $("a").each(function () {
        $(this).removeClass("active");
      });
      $(this).addClass("active");

      var target = this.hash,
        menu = target;
      $target = $(target);
      $("html, body")
        .stop()
        .animate({
          scrollTop: $target.offset().top - 80,
        });

      var id = target;
      console.log(id);
      var myScrollPos =
        $("[href='" + id + "']").offset().left +
        $("[href='" + id + "']").outerWidth(true) / 2 +
        $(".js-scrollbar .nav-list").scrollLeft() -
        $(".js-scrollbar .nav-list").width() / 2;
      $(".js-scrollbar .nav-list")
        .stop()
        .animate({ scrollLeft: myScrollPos }, 500);
    });
  });

  // Cache selectors
  var topMenu = $(".nav-dieuhuong.js-scrollbar"),
    topMenuHeight = topMenu.outerHeight() + 25,
    // All list items
    menuItems = topMenu.find("a"),
    // Anchors corresponding to menu items
    scrollItems = menuItems.map(function () {
      var item = $(this).attr("href");
      if (item.length) {
        return item;
      }
    });

  if (scrollItems.length > 0) {
    console.log(1);
    // Bind to scroll
    $(window).scroll(function () {
      console.log(1);
      // Get container scroll position
      var fromTop = $(this).scrollTop() + topMenuHeight;
      // Get id of current scroll item
      var cur = scrollItems.map(function (index, value) {
        if ($(value).offset().top < fromTop) return value;
      });

      // Get the id of the current element
      cur = cur[cur.length - 1];
      // var id = cur && cur.length ? cur[0] : "";
      // Set/remove active class
      menuItems
        .removeClass("active")
        .filter("[href='" + cur + "']")
        .addClass("active");
      if (cur === undefined) {
        topMenu = $(".nav-dieuhuong")
          .find("li:first-child a")
          .addClass("active");
      } else {
        var id = cur;
        var myScrollPos =
          $("[href='" + id + "']").offset().left +
          $("[href='" + id + "']").outerWidth(true) / 2 +
          $(".js-scrollbar .nav-list").scrollLeft() -
          $(".js-scrollbar .nav-list").width() / 2;
        $(".js-scrollbar .nav-list")
          .stop()
          .animate({ scrollLeft: myScrollPos }, 500);
      }
    });
  }

  /* widget_nav_menu */
  $(document).ready(function () {
    var e = $(".widget-area .widget_nav_menu ul.menu");
    e
      .find(".menu-item-has-children>a")
      .after(
        '<button class="togglez"><i class="fa fa-angle-down" aria-hidden="true"></i></button>'
      ),
      e.find(".togglez").on("click", function (e) {
        e.stopPropagation(),
          $(this).parent().find("ul.sub-menu").is(":visible")
            ? $(this).parent().removeClass("activez")
            : $(this).parent().addClass("activez"),
          $(this).parent().find("ul.sub-menu").first().slideToggle();
      });
  });
  // Active menu
  $(
    ".widget-area .widget_nav_menu ul.menu>li.current-menu-item, .widget_nav_menu .current-menu-parent"
  ).addClass("activez");
  $(
    ".widget-area .widget_nav_menu ul.menu>li.current-menu-item, .widget_nav_menu .current-menu-parent"
  )
    .find("ul.sub-menu")
    .first()
    .slideToggle();

  /* end widget_nav_menu */

  $(".variations .value select").on("change", function (event) {
    console.log($(this).val());
    $(this).parent().find(".value__custom-meta>dd").removeClass("active");
    $(this)
      .parent()
      .find(".value__custom-meta ." + $(this).val())
      .addClass("active");
  });

  $(".main-carousel").each(function (index) {
    const $this = $(this);
    const baseDelay = 10000; // thời gian autoplay mỗi slider
    const delayOffset = 200 * index;

    setTimeout(() => {
      $this.flickity({
        cellAlign: "left",
        autoPlay: baseDelay,
        pageDots: false,
        pauseAutoPlayOnHover: false,
        prevNextButtons: false,
      });
    }, delayOffset);
  });

  $(".product-made-carousel").each(function (index) {
    const $this = $(this);
    const baseDelay = 10000; // thời gian autoplay mỗi slider
    const delayOffset = 200 * index;

    setTimeout(() => {
      $this.flickity({
        cellAlign: "left",
        autoPlay: baseDelay,
        contain: true,
        wrapAround: true,
        pageDots: false,
        pauseAutoPlayOnHover: false,
      });
    }, delayOffset);
  });
})(jQuery);
