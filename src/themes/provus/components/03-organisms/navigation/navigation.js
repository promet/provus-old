Drupal.behaviors.navigation = {
  attach(context, drupalSettings) {
    const icon = context.querySelectorAll('.header__search a')[0];
    const header = context.querySelectorAll('.header')[0];
    const search = context.querySelectorAll('.header__search input[type=search]')[0];
    const reset = context.querySelectorAll('.header__search input[type=reset]')[0];
    const types = document.querySelectorAll('.header__search input[name="search-bar-type"]');
    const frm = context.querySelector('.header__search form');

    if (!(icon && header && reset && search)) {
      return;
    }

    icon.addEventListener('click', (e) => {
      e.preventDefault();
      header.classList.toggle('search-bar-open');
      header.classList.remove('search-bar-closed');
      search.focus();
    });

    reset.addEventListener('click', () => {
      header.classList.remove('search-bar-open');
      header.classList.add('search-bar-closed');
    });

    for (let i = 0; i < types.length; i += 1) {
      const item = types[i];
      item.addEventListener('change', () => {
        switch (item.value) {
          case 'catalog':
            search.setAttribute('name', 'q');
            search.setAttribute('placeholder', 'Find books and more...');
            frm.action = 'https://catalog.ocpl.org/client/en_US/default/search/results';
            break;
          case 'county':
            search.setAttribute('name', 'q');
            search.setAttribute('placeholder', 'Search...');
            frm.action = 'https://www.ocgov.com/search';
            break;
          case 'agency':
          default:
            search.setAttribute('name', 'keys');
            search.setAttribute('placeholder', 'Search...');
            frm.action = `${drupalSettings.path.baseUrl}search`;
            break;
        }
      });
    }
  },
};
