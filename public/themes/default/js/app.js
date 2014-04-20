/* code jquery for mod bootstrap and more */

$('.load-average').tooltip({
    placement: 'top',
    trigger: 'hover'
}).tooltip('hide');

$('#popupfilezilla').popover({
    html : true,
    trigger : 'hover',
    content : ' &bull; Cliquer sur ce bouton.<br> &bull; Ouvrir filezilla.<br> &bull; Fichier -> Importer les paramètres.<br> &bull; Sélectionner le fichier filezilla.xml.<br> &bull; C\'est terminé !',
    title : 'Configurer filezilla rapidement !',
    placement : function() {
        var width = $(window).width();
        return width >= 979 ? 'right' : 'top';
    }
});

$('#popuptransdroid').popover({
    html : true,
    trigger : 'hover',
    content : "Génère un fichier de configuration pour l'application transdroid.",
    title : 'Configurer transdroid rapidement !',
    placement : function() {
        var width = $(window).width();
        return width >= 979 ? 'right' : 'top';
    }
});

$(document).on('click', '.popup-delete-user', function ()
{
    var userName = $(this).data('user');
    $('#deleteUserName').val(userName);
    $('#target-delete-user').html('<i class="glyphicon glyphicon-trash"></i> Suppression de l\'utilisateur : <strong>'+userName+'</strong>');
});

$('.alert-success').delay(4000).hide('slow');

$('.ticket').hide();
$('.show-ticket').click( function ()
{
    $('.ticket-' + this.id).fadeIn();
});

$('.close-ticket').click( function ()
{
    $('.ticket-' + this.id).fadeOut();
});

//Génère un cookie qui expire au bout de 2j
if ( $.cookie('seedbox-manager') == undefined ) {
    $.cookie('seedbox-manager', 'check-update', { expires: 1 } );
}
