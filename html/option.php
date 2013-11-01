    <!-- CONTENEUR -->
    <div class="container marg">
        <h1 class="page-header dashboard"><i class="glyphicon glyphicon-wrench"></i> Paramètres</h1>
        
        <?php if ( isset($_POST['simple_conf_user']) && empty($update_ini_file_log) ) { ?>

            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                Vos préférences ont été mises à jour avec succès.
            </div>

        <?php } elseif ( isset($_POST['simple_conf_user']) ) { ?>

        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>Impossible de mettre à jour votre configuration !</h4>
            <ul class="text-danger">
            <?php foreach ($update_ini_file_log as $key => $data_error_update)
            {
                echo '<li>'.$data_error_update.'</li>';
            } ?>
            </ul>
        </div>

        <?php } if ( isset($_POST['delete-userName']) ) { ?>

        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Les fichiers de configuration de l'utilisateur <?php echo $_POST['delete-userName']; ?> ont été supprimé avec succès.
            <ul class="text-success">
            <?php foreach ($log_delete_user as $key => $file_delete)
            {
                echo '<li>'.$file_delete.'</li>';
            } ?>
            <ul>
        </div>

        <?php } if ( !isset($_GET['user']) ) { ?>

        <section class="row">
            <article class="col-md-6" id="conf-simple-user">
                <div class="well well-sm">
                    <h4 class="titre-head">Configuration de l'interface  de <?php echo $userName; ?></h4>
                    <div class="trait"></div>

                    <form method="post" action="?option" role="form">
                        <div class="checkbox">
                            <input type="checkbox" name="active_bloc_info" value="true" id="active_bloc_info" <?php if ($user->blocInfo() === true) echo 'checked'; ?> ><label for="active_bloc_info"><span class="ui"></span> Activer le bloc information utilisateur</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="active_ftp" value="true" id="active_ftp" <?php if ($user->blocFtp() === true) echo 'checked'; ?> ><label for="active_ftp"><span class="ui"></span> Activer le bloc accès ftp / sftp / transdroid</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="active_reboot" value="true" id="active_reboot" <?php if ($user->blocRtorrent() === true) echo 'checked'; ?> ><label for="active_reboot"><span class="ui"></span> Activer le bloc gestion de rtorrent</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="active_support" value="true" id="active_support" <?php if ($user->blocSupport() === true) echo 'checked'; ?> ><label for="active_support"><span class="ui"></span> Activer le bloc support</label>
                        </div>
                        <div class="form-group">
                            <label for="url-deconnexion">Url de redirection à la déconnexion</label>
                            <div class="row">
                                <div class="col-lg-7">
                                    <input type="text" name="url_redirect" class="form-control" id="url-deconnexion" value="<?php echo $user->url_redirect(); ?>">
                                </div>
                            </div>
                        </div>
                        <p class="text-right fix-marg-input">
                            <input type="hidden" name="simple_conf_user">
                            <input type="submit" name="submit" value="Enregistrer" class="btn btn-info">
                        </p>
                    </form>
                </div>
            </article>

        <?php } if ( $user->is_owner() === true ) { ?>

        <article class="col-md-6">
            <div class="well well-sm">
                <h4 class="titre-head"><i class="glyphicon glyphicon-th-list"></i> Administration : gestion utilisateurs</h4>
                <div class="trait"></div>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td><strong>#</strong></td>
                        <td><strong>Utilisateur</strong></td>
                        <td><strong>Modifier</strong></td>
                        <td><strong>Supprimer</strong></td>
                    </tr>
                    <?php $i = 0; $num_user = 1; foreach (Users::get_all_users() as $i => $user_name) { ?>
                    <tr>
                        <td><?php echo $num_user; ?></td>
                        <td><?php echo $user_name; ?></td>
                        <td>
                            <a href="?option&user=<?php echo $user_name; ?>" class="btn btn-default btn-xs edit-btn-user">
                                <i class="glyphicon glyphicon-edit"></i> éditer
                            </a>
                        </td>
                        <td>
                        <?php if ( $userName != $user_name) { ?>
                        <a data-toggle="modal" class="popup-delete-user btn btn-danger btn-xs" data-user="<?php echo $user_name ?>" href="#delete-user">
                            <i class="glyphicon glyphicon-trash"></i> supprimer
                        </a>
                        <?php } else { ?>
                        <a class="popup-delete-user btn btn-danger btn-xs" disabled>
                            <i class="glyphicon glyphicon-trash"></i> supprimer
                        </a>
                        <?php } ?>
                        </td>
                    </tr>
                    <?php $num_user++; } ?>
                </table>
            </div>
        </article>

        <?php } if ( isset($_GET['user']) )
        { 
            $update_owner = new Users('./conf/users/'.$_GET['user'].'/config.ini', $_GET['user'] );
        ?>

        <article class="col-md-6">
            <div class="well well-sm">
                <h4 class="titre-head"><i class="glyphicon glyphicon-th-list"></i> Configuration de l'utilisateur : <strong class="text-info"><?php echo $_GET['user']; ?></strong></h4>
                <div class="trait"></div>
                
                <form method="post" action="?option" role="form">
                    <fieldset>
                        <legend>Généralité utilisateur</legend>
                        <div class="form-group">
                            <label for="user_directory">Dossier /home de l'utilisateur</label>
                            <input type="text" class="form-control" name="user_directory" id="user_directory" value="<?php echo $update_owner->user_directory(); ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Barre de navigation</legend>
                        <div class="form-group">
                            <label for="url_rutorrent">L'url de rutorrent</label>
                            <input type="url" class="form-control" name="url_rutorrent" id="url_rutorrent" value="<?php echo $update_owner->rutorrentUrl(); ?>">
                        </div>

                        <div class="checkbox">
                            <input type="checkbox" name="active_cakebox" id="active_cakebox" <?php if ($user->cakeboxActiveUrl() === true) echo 'checked'; ?> ><label for="active_cakebox"><span class="ui"></span> Afficher le lien cakebox</label>
                        </div>

                        <div class="form-group">
                            <label for="url_cakebox">L'url de cakebox</label>
                            <input type="url" class="form-control" name="url_cakebox" id="url_cakebox" value="<?php echo $update_owner->cakeboxUrl(); ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Paramètre des serveurs FTP/sFTP</legend>
                        <div class="form-group">
                            <label for="port_ftp">Port ftp</label>
                            <input type="number" class="form-control" name="port_ftp" id="port_ftp" value="<?php echo $update_owner->portFtp(); ?>">
                        </div>

                        <div class="form-group">
                            <label for="port_sftp">Port sftp</label>
                            <input type="number" class="form-control" name="port_sftp" id="port_sftp" value="<?php echo $update_owner->portSftp(); ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Support</legend>
                        <div class="form-group">
                            <label for="adresse_mail">Adresse du support</label>
                            <input type="email" class="form-control" name="adresse_mail" id="adresse_mail" value="<?php echo $update_owner->supportMail(); ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>Paramètre de déconnexion</legend>
                        <div class="form-group">
                            <label for="realm">realm de l'authentification du serveur web</label>
                            <input type="text" class="form-control" name="realm" id="realm" value="<?php echo $update_owner->realmWebServer(); ?>">
                        </div>
                    </fieldset>

                    <p class="text-right fix-marg-input">
                        <input type="hidden" name="user" value="<?php echo $_GET['user']; ?>">
                        <input type="hidden" name="owner_change_config">
                        <input type="submit" name="submit" value="Enregistrer" class="btn btn-info">
                    </p>
                </form>

            </div>
        </article>
        <?php } ?>

        </section>

    </div>