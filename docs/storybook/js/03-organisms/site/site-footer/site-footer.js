"use strict";(function(a){Drupal.behaviors.siteFooter={attach:function attach(b){a(".footer__inner h2",b).once().click(function(b){a(b.target).next().toggleClass("open"),a(b.target).toggleClass("open")}).focus(function(b){a(b.target).next().toggleClass("open"),a(b.target).toggleClass("open")})}}})(jQuery);