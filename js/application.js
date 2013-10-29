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


(function() {
    // remplacer document par le bon id
    $(document).on('click', '.popup-delete-user', function ()
    {
        var userName = $(this).data('user');
         // remplace value par le bon pseudo
        $("#delete-userName").val(userName);
         // ajout du html dans le div
        $("#user-titre-modal").html('<i class="glyphicon glyphicon-trash"></i> Suppression de l\'utilisateur : <strong>'+userName+'</strong>');

    });

}) ();


$( "#back-owner" ).hide();

$( ".edit-btn-user" ).click( function()
{
    $( "#conf-simple-user" ).hide( "slow" );
    $( "#config-owner" ).delay(600).show( "slow", "linear" );
    $( "#back-owner" ).show();

    var userName = $(this).data('user');
    
    // remplace value par le bon pseudo
    $("#titre-edit-owner").html('<i class="glyphicon glyphicon-th-list"></i> Configuration de l\'utilisateur : <strong class="text-info">'+userName+'</strong>');
});



$( "#back-owner" ).click( function()
{
    
    $( "#config-owner" ).hide( "slow" );
    $( "#conf-simple-user" ).delay(1000).show( "slow" );
    $( "#back-owner" ).hide();
});
