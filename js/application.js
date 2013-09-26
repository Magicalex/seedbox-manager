( function () {

    //code activation tooltip
    $("[id='tooltip']").tooltip({
        placement: 'top',
        trigger: 'hover'
    }).tooltip('hide');

}) ();

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

}) ();

( function() {
    
    // ajustement des blocks
    var blockInfo = document.getElementById("blockInfo");
    var blockFtp = document.getElementById("blockFtp");
    var blockRtorrent = document.getElementById("blockRtorrent");
    var blockSupport = document.getElementById("blockSupport");

    if(blockInfo.offsetHeight > blockFtp.offsetHeight)
        blockFtp.style.minHeight = blockInfo.offsetHeight+"px";
    else
        blockInfo.style.minHeight = blockFtp.offsetHeight+"px";

    if(blockRtorrent.offsetHeight > blockSupport.offsetHeight)
        blockSupport.style.minHeight = blockRtorrent.offsetHeight+"px";
    else
        blockRtorrent.style.minHeight = blockSupport.offsetHeight+"px";

}) ();
