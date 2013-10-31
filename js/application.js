( function () {

    //code activation tooltip
    $("[id='tooltip']").tooltip({
        placement: 'top',
        trigger: 'hover'
    }).tooltip('hide');

} ) ();

( function () {

    //code activation popover
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
        content : 'Fonctionnalité en cour de développement.<br>La génération du fichier de configuration fonctionne pour ceux qui sont à l\'aise sous android peuvent le copier/coller via leur pc.',
        title : 'Configurer transdroid rapidement !',
        placement : function() {
            var width = $(window).width();
            return width >= 979 ? 'right' : 'top';
        }
    });

} ) ();

( function() {
    
    if ( document.getElementById("blockInfo") && document.getElementById("blockFtp") && document.getElementById("blockRtorrent") && document.getElementById("blockSupport") )
    {
        // ajustement du style css des blocs
        var blockInfo = document.getElementById("blockInfo");
        var blockFtp = document.getElementById("blockFtp");
        var blockRtorrent = document.getElementById("blockRtorrent");
        var blockSupport = document.getElementById("blockSupport");

        if(blockInfo.offsetHeight > blockFtp.offsetHeight)
            blockFtp.style.height = blockInfo.offsetHeight+"px";
        else
            blockInfo.style.height = blockFtp.offsetHeight+"px";

        if(blockRtorrent.offsetHeight > blockSupport.offsetHeight)
            blockSupport.style.height = blockRtorrent.offsetHeight+"px";
        else
            blockRtorrent.style.height = blockSupport.offsetHeight+"px";
    }

} ) ();


$(document).on('click', '.popup-delete-user', function ()
{
    var userName = $(this).data('user');
    $('#delete-userName').val(userName);
    $('#user-titre-modal').html('<i class="glyphicon glyphicon-trash"></i> Suppression de l\'utilisateur : <strong>'+userName+'</strong>');
});

$('#back-owner').hide();
$('.edit-btn-user').click( function()
{
    $('#conf-simple-user').hide('slow');
    $('#config-owner').delay(600).show('slow');
    $('#back-owner').show();

    var userName = $(this).data('user');
    $('#titre-edit-owner').html('<i class="glyphicon glyphicon-th-list"></i> Configuration de l\'utilisateur : <strong class="text-info">'+userName+'</strong>');
    $('#user-change-config').val(userName);
});

$('#back-owner').click( function()
{
    $('#config-owner').hide('slow');
    $('#conf-simple-user').delay(600).show('slow');
    $('#back-owner').hide();
});
