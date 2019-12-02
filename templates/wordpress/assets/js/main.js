"use strict";

jQuery(document).ready(function () {
  jQuery("#nav-toggle").click(function (e) {
    e.preventDefault();
    jQuery(this).toggleClass('open');
    jQuery("#nav-menu").slideToggle("medium");
  });
  jQuery(window).resize(function () {
    if (jQuery(window).width() >= "720") {
      jQuery("#nav-menu").css("display", "block");
    } else {
      jQuery("#nav-menu").css("display", "none");
    }
  });
});