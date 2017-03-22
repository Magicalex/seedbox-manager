(function ($) {
  $('.load-average').tooltip({
    placement: 'top',
    trigger: 'hover'
  }).tooltip('hide');

  /* Envoie les donnees de supp au modal */
  $('.popup-delete-user').click(function () {
    var userName = $(this).data('user');
    $('#deleteUserName').val(userName); // id input modal delete user add value="userName"
    $('#target-delete-user').html(userName);
  });

  /* function LOGOUT */
  $('#logout').click(function () {
    var UrlRedirect = $(this).data('urlredirect');
    var protocol = window.location.protocol;
    var host = window.location.host;
    var uri = window.location.pathname;

    $.get(protocol + '//logout@' + host + uri);
    $.loader({
      id: 'jquery-loader',
      className: '',
      content: '<span>Déconnexion...</span>',
      height: 60,
      width: 200,
      background: {id:'jquery-loader-background'}
    });
    setTimeout(function () {
      window.location.href = UrlRedirect;
    }, 1000);
  });
})(jQuery);
