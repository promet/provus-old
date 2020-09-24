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
          const menuItem = $('.main-menu--sub-1.main-menu--sub-open .main-menu__item--sub-1.open .expand-sub--open')[0];
          const subMenu = menuItem.nextElementSibling;

          menuItem.classList.remove('expand-sub--open');
          subMenu.classList.remove('main-menu--sub-open');
          menu.classList.remove('sub-2-open');
          menu.classList.add('sub-1-open');
          subMenu.parentNode.classList.remove('open');
        }
      });
      const fixedMenu = () => {
      };

      $(window).resize(fixedMenu);
      $(window).scroll(fixedMenu);
      $('.main-nav').mouseenter(fixedMenu);

      let hoverTimeout;
      $('.main-menu__item', context)
        .mouseleave((e) => {
          e.currentTarget.classList.remove('hover-menu');
          clearTimeout(hoverTimeout);
        })
        .mouseenter((e) => {
          hoverTimeout = setTimeout(() => e.currentTarget.classList.add('hover-menu'), 400);
        });
      $('.main-menu > .main-menu__item > .main-menu__link', context)
        .focus((e) => {
          e.currentTarget.parentNode.classList.add('hover-menu');
        });
      // The elements before and after the main-menu nav items.
      $('.logo-link, .header__search a', context)
        .focus(() => {
          const hoveredItems = document.querySelectorAll('.hover-menu');
          for (let i = 0; i < hoveredItems.length; i += 1) {
            hoveredItems[i].classList.remove('hover-menu');
          }
        });
    },
  };
})(jQuery);
