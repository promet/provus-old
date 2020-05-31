Drupal.behaviors.tabs = {
  attach() {
    let isClicked = false;
    const translateElement = document.getElementById('google_translate_element');
    if (!translateElement) {
      return;
    }
    translateElement.onclick = function () { // eslint-disable-line
      if (isClicked === false) {
        const elem = document.getElementById('google_translate_element');
        elem.innerHTML = '';
        isClicked = true;
        new google.translate.TranslateElement({ pageLanguage: 'en', includedLanguages: 'en,es,vi,ko,zh-TW' }, 'google_translate_element'); // eslint-disable-line
      }
    };
  },
};
