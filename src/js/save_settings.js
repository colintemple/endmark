(function($) {

  function postEndmarkSettings() {
      jQuery.post(window.endmarkAdminSettings.url, window.data,
          function (response) {
            console.log(response);
          });
  }

  $(document).ready(function () {

    if (typeof window.endmarkAdminSettings === 'undefined') {

      window.endmarkAdminSettings = {
        type: 'symbol',
        where: 'post',
        image: '',
        symbol: '#'
      }
    }

    window.data = {
      'action': 'save_settings',
      'endmark_type': window.endmarkAdminSettings.type,
      'endmark_symbol': window.endmarkAdminSettings.symbol,
      'endmark_image': window.endmarkAdminSettings.image,
      'endmark_where': window.endmarkAdminSettings.where,
    };

    $("endmark_settings_form").on("click", postEndmarkSettings);

  });

})(jQuery);
