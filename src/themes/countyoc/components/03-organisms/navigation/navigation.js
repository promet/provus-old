Drupal.behaviors.navigation = {
  attach(context) {
    const icon = context.querySelectorAll('.header__search a')[0];
    const header = context.querySelectorAll('.header')[0];
    const search = context.querySelectorAll('.header__search input[type=search]')[0];
    const reset = context.querySelectorAll('.header__search input[type=reset]')[0];

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
  },
};
