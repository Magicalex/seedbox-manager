(function($){
  $('.load-average').tooltip({
    placement: 'top',
    trigger: 'hover'
  }).tooltip('hide');

  $('#popupfilezilla').popover({
    html: true,
    trigger: 'hover',
    content: ' &bull; Cliquer sur ce bouton.<br> &bull; Ouvrir filezilla.<br> &bull; Fichier -> Importer les paramètres.<br> &bull; Sélectionner le fichier filezilla.xml.<br> &bull; C\'est terminé !',
    title: 'Configurer filezilla rapidement !',
    placement: function () {
      var width = $(window).width();
      return width >= 979 ? 'right' : 'top';
    }
  });

  $('#popuptransdroid').popover({
    html: true,
    trigger: 'hover',
    content: "Génère un fichier de configuration pour l'application transdroid.",
    title: 'Configurer transdroid rapidement !',
    placement: function () {
      var width = $(window).width();
      return width >= 979 ? 'right' : 'top';
    }
  });

  /* Envoie les donnees de supp au modal */
  $('.popup-delete-user').click(function () {
    var userName = $(this).data('user');
    $('#deleteUserName').val(userName); // id input modal delete user add value="userName"
    $('#target-delete-user').html('<i class="glyphicon glyphicon-trash"></i> Suppression de l\'utilisateur : <strong>'+userName+'</strong>');
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
