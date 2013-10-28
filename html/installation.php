<?php

$user_name_php = Install::get_user_php();

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Installation - Seedbox Manager</title>        
        <link href="./css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link type="text/css" rel="stylesheet" href="./css/style.css">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--[if lt IE 9]>
            <script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script type="text/javascript" src="./js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div  class="container marg" style="margin-top:50px">
            <h1 class="page-header dashboard"><i class="glyphicon glyphicon-wrench"></i> Guide d'installation</h1>
            <section class="row">
                <article class="col-md-12">
                    <div class="well well-sm">
                        <h4 class="titre-head">Démarrage de l'application</h4>
                        <div class="trait"></div>
                        <p>Indiquez le bon propriétaire des fichiers de l'application, copiez cette commande et l'éxécuter en ROOT (super utilisateur).</p>
                        <code style="border:1px solid #CE534D;">chown -R <?php echo $user_name_php['name'].':'.$user_name_php['name'].' '.getcwd(); ?></code>
                        <p></p>
                        <p>Exécutez le script install.sh pour compiler le programme de reboot en ROOT.</p>
                        <code style="border:1px solid #CE534D;">cd <?php echo getcwd(); ?>/source-reboot/ </code><br>
                        <code style="border:1px solid #CE534D;">chmod +x install.sh && ./install.sh</code><br>
                    </div>
                </article>
                <article class="col-md-12">
                    <div class="well well-sm">
                        <h4 class="titre-head">Configuration des utilisateurs</h4>
                        <div class="trait"></div>
                        <p></p>
                    </div>
                </article>
            </section>
        </div>
    </body>
</html>