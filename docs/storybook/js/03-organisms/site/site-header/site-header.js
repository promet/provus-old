"use strict";Drupal.behaviors.siteHeader={attach:function attach(){var a=document,b=a.body,c="scroll-up",d="scroll-down",e=0,f=document.querySelector(".pre-header"),g=document.querySelector(".header > .header__pre-inner");g&&f&&(g.style.top="".concat(f.offsetHeight,"px")),window.addEventListener("scroll",function(){var a=window.pageYOffset;if(!(0>a)){if(g&&f){var h=Math.max(0,f.offsetHeight-window.pageYOffset);g.style.top="".concat(h,"px")}return 0===a?void b.classList.remove(c):void(150<e&&a>e&&!b.classList.contains(d)?(b.classList.remove(c),b.classList.add(d)):a<e&&b.classList.contains(d)&&(b.classList.remove(d),b.classList.add(c)),e=a)}})}};