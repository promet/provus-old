(($) => {
  Drupal.behaviors.mainMenu = {
    attach(context) {
      const header = context.getElementsByClassName('header')[0];
      const toggleExpand = document.getElementById('toggle-expand');
      const menu = document.getElementById('main-nav');
      if (menu) {
        const expandMenu = menu.getElementsByClassName('expand-sub');

        // Mobile Menu Show/Hide.
        toggleExpand.addEventListener('click', (e) => {
          toggleExpand.classList.toggle('toggle-expand--open');
          menu.classList.toggle('main-nav--open');
          header.classList.toggle('menu-mobile-open');
          e.preventDefault();
        });

        // Expose mobile sub menu on click.
        for (let i = 0; i < expandMenu.length; i += 1) {
          expandMenu[i].addEventListener('click', (e) => {
            const menuItem = e.currentTarget;
            const subMenu = menuItem.nextElementSibling;

            menuItem.classList.toggle('expand-sub--open');
            subMenu.classList.toggle('main-menu--sub-open');

            if (subMenu.classList.contains('main-menu--sub-open')) {
              menu.classList.add('sub-open');
              subMenu.parentNode.classList.add('open');
            } else {
              menu.classList.remove('sub-open');
              subMenu.parentNode.classList.remove('open');
            }
            if (subMenu.classList.contains('main-menu--sub-1')) {
              menu.classList.add('sub-1-open');
            } else {
              menu.classList.remove('sub-1-open');
            }
            if (subMenu.classList.contains('main-menu--sub-2')) {
              menu.classList.add('sub-2-open');
              menu.classList.remove('sub-1-open');
            } else {
              menu.classList.remove('sub-2-open');
            }
          });
        }
      }

      const menuListItems = context.querySelectorAll('.main-menu >li >ul');
      for (let i = 0; i < menuListItems.length; i += 1) {
        const menuListItem = menuListItems[i];
        let itemCount = menuListItem.children.length;
        itemCount += menuListItem.querySelectorAll('li>ul>li>ul>li').length;
        if (itemCount <= 8) {
          menuListItem.classList.add('columns1');
        } else if (itemCount <= 16) {
          menuListItem.classList.add('columns2');
        } else if (itemCount <= 24) {
          menuListItem.classList.add('columns3');
        } else if (itemCount <= 32) {
          menuListItem.classList.add('columns4');
        }
      }

      const back = document.getElementById('main-menu-back');
      if (back) {
        back.addEventListener('click', (e) => {
          e.preventDefault();
          const menuItem = document.getElementsByClassName('expand-sub--open')[0];
          const subMenu = menuItem.nextElementSibling;

          menuItem.classList.remove('expand-sub--open');
          subMenu.classList.remove('main-menu--sub-open');
          menu.classList.remove('sub-open');
          subMenu.parentNode.classList.remove('open');
        });
      }

      $('#main-nav > .main-menu > .main-menu__item--with-sub > .main-menu__link--with-sub', context).click((e) => {
        if ($('#main-nav').hasClass('sub-2-open')) {
          e.preventDefault();
          const menuItem = $('.main-menu--sub-1.main-menu--sub-open .main-menu__link--sub-1')[0];
          const subMenu = menuItem.nextElementSibling.nextElementSibling;

          menuItem.classList.remove('expand-sub--open');
          subMenu.classList.remove('main-menu--sub-open');
          menu.classList.remove('sub-2-open');
          menu.classList.add('sub-1-open');
          subMenu.parentNode.classList.remove('open');
        }
      });
      const fixedMenu = () => {
        const menu1stLevel = $('.main-nav > .main-menu > li.main-menu__item--with-sub > ul');
        if ($(window).width() > 1024) {
          const menuBottom = $(menu).offset().top + $(menu).outerHeight(true);
          menu1stLevel.css({ top: menuBottom - $(window).scrollTop() });
        } else {
          menu1stLevel.css({ top: '' });
        }
      };

      $(window).resize(fixedMenu);
      $(window).scroll(fixedMenu);
      $('.main-nav').mouseenter(fixedMenu);
    },
  };
})(jQuery);
