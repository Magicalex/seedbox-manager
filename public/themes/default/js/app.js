(function($){

    /* code jquery for mod bootstrap and more */

    $('.load-average').tooltip(
    {
        placement: 'top',
        trigger: 'hover'
    }).tooltip('hide');

    $('#popupfilezilla').popover(
    {
        html : true,
        trigger : 'hover',
        content : ' &bull; Cliquer sur ce bouton.<br> &bull; Ouvrir filezilla.<br> &bull; Fichier -> Importer les paramètres.<br> &bull; Sélectionner le fichier filezilla.xml.<br> &bull; C\'est terminé !',
        title : 'Configurer filezilla rapidement !',
        placement : function()
        {
            var width = $(window).width();
            return width >= 979 ? 'right' : 'top';
        }
    });

    $('#popuptransdroid').popover(
    {
        html : true,
        trigger : 'hover',
        content : "Génère un fichier de configuration pour l'application transdroid.",
        title : 'Configurer transdroid rapidement !',
        placement : function()
        {
            var width = $(window).width();
            return width >= 979 ? 'right' : 'top';
        }
    });

    /* Envoie les donnees de supp au modal */
    $('.popup-delete-user').click( function()
    {
        var userName = $(this).data('user');
        $('#deleteUserName').val(userName); // id input modal delete user add value="userName"
        $('#target-delete-user').html('<i class="glyphicon glyphicon-trash"></i> Suppression de l\'utilisateur : <strong>'+userName+'</strong>');
    });

    /* Cache les messages succes au bout de 4 sec */
    $('.alert-success').delay(4000).hide('slow');

    /* Cache (au demarrage) et affiche un ticket */
    $('.ticket').hide();
    $('.show-ticket').click( function ()
    {
        $('.ticket-' + this.id).fadeIn();
    });

    /* Cache un ticket affiche */
    $('.close-ticket').click( function ()
    {
        $('.ticket-' + this.id).fadeOut();
    });

    /* function LOGOUT */
    $('#logout').click( function()
    {
        var UrlRedirect = $(this).data('urlredirect');

        var protocol = window.location.protocol;
        var hostname = window.location.hostname;
        var uri      = window.location.pathname;

        $.get( protocol + '//logout@' + hostname + uri);

        $.loader(
        {
            id: 'jquery-loader',
            className: '',
            content: '<span>Déconnexion...</span>',
            height: 60,
            width: 200,
            background: {id:'jquery-loader-background'}
        });
        setTimeout( function()
        {
            window.location.href = UrlRedirect;
        }, 1000);
    });

})(jQuery);
