Drupal.behaviors.siteHeader = {
  attach() {
    const { body } = document;
    const scrollUp = 'scroll-up';
    const scrollDown = 'scroll-down';
    let lastScroll = 0;
    const preHeader = document.querySelector('.pre-header');
    const header = document.querySelector('.header > .header__pre-inner');
    if (header && preHeader) {
      header.style.top = `${preHeader.offsetHeight}px`;
    }
    window.addEventListener('scroll', () => {
      const currentScroll = window.pageYOffset;
      if (currentScroll < 0) {
        return;
      }
      if (header && preHeader) {
        const headerTop = Math.max(0, preHeader.offsetHeight - window.pageYOffset);
        header.style.top = `${headerTop}px`;
      }
      if (currentScroll === 0) {
        body.classList.remove(scrollUp);
        return;
      }
      if (lastScroll > 150 && currentScroll > lastScroll && !body.classList.contains(scrollDown)) {
        // down
        body.classList.remove(scrollUp);
        body.classList.add(scrollDown);
      } else if (currentScroll < lastScroll && body.classList.contains(scrollDown)) {
        // up
        body.classList.remove(scrollDown);
        body.classList.add(scrollUp);
      }
      lastScroll = currentScroll;
    });
  },
};
