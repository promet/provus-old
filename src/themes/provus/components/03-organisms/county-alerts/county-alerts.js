Drupal.behaviors.countyAlerts = {
  attach() {
    const alertWrapper = document.querySelector('.county-blocks-alert-block-wrapper');
    const alertTemplate = document.querySelector('.county-blocks-alert-block-wrapper .alerts--template');
    const url = 'https://jsonplaceholder.typicode.com/posts/1';

    if ('fetch' in window) {
      fetch(url)
        .then(response => {
          return response.json();
        })
        .then(data => {
          for (let i = 0; i < data.length; i += 1) {
            const addedAlerts = alertWrapper.getElementsByClassName('alerts--' + data[i]['nid']);
            if (addedAlerts.length == 0) {
              const cln = alertTemplate.cloneNode(true);
              cln.classList.remove('alerts--template');
              cln.classList.add('alerts--' + data[i]['nid']);
              cln.getElementsByClassName('title')[0].innerHTML = data[i]['title'];
              cln.getElementsByClassName('description')[0].innerHTML = data[i]['body'];
              alertWrapper.appendChild(cln);
            }
          }
          // Run preheader script from site-header.js.
          const preHeader = document.querySelector('.pre-header');
          const header = document.querySelector('.header > .header__pre-inner');
          if (header && preHeader) {
            header.style.top = `${preHeader.offsetHeight}px`;
          }
        })
        .catch(err => {
          console.log('There is an issue getting county alerts.');
        });
    }
  },
};
