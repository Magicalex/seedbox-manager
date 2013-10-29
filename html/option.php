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

        <?php } ?>

        <section class="row">
            <article class="col-md-6" id="conf-simple-user">
                <div class="well well-sm">
                    <h4 class="titre-head">Configuration de l'interface  de <?php echo $userName; ?></h4>
                    <div class="trait"></div>

                    <form method="post" action="?option" role="form">
                        <div class="checkbox">
                            <input type="checkbox" name="active_bloc_info" value="true" id="active_bloc_info" <?php if ($user->blocInfo() === true) echo 'checked'; ?>><label for="active_bloc_info"><span class="ui"></span> Activer le bloc information utilisateur</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="active_ftp" value="true" id="active_ftp" <?php if ($user->blocFtp() === true) echo 'checked'; ?>><label for="active_ftp"><span class="ui"></span> Activer le bloc accès ftp / sftp / transdroid</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="active_reboot" value="true" id="active_reboot" <?php if ($user->blocRtorrent() === true) echo 'checked'; ?>><label for="active_reboot"><span class="ui"></span> Activer le bloc gestion de rtorrent</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="active_support" value="true" id="active_support" <?php if ($user->blocSupport() === true) echo 'checked'; ?>><label for="active_support"><span class="ui"></span> Activer le bloc support</label>
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

        <?php if ( $user->is_owner() === true ) { ?>

        <article class="col-md-6">
            <div class="well well-sm">
                <h4 class="titre-head"><i class="glyphicon glyphicon-th-list"></i> Administration : gestion utilisateurs <button class="btn btn-info btn-xs text-right" id="back-owner">retour</button></h4>
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
                            <button class="btn btn-default btn-xs edit-btn-user" title="éditer" data-user="<?php echo $user_name ?>">
                                <i class="glyphicon glyphicon-edit"></i>
                            </button>
                        </td>
                        <td>
                        <?php if ( $userName != $user_name) { ?>
                        <a data-toggle="modal" class="popup-delete-user btn btn-danger btn-xs" data-user="<?php echo $user_name ?>" href="#delete-user">
                            <i class="glyphicon glyphicon-trash"></i>
                        </a>
                        <?php } ?>
                        </td>
                    </tr>
                    <?php $num_user++; } ?>
                </table>
            </div>
        </article>

        <article class="col-md-6" id="config-owner" style="display:none">
            <div class="well well-sm">
                <h4 class="titre-head" id="titre-edit-owner"></h4>
                <div class="trait"></div>
                
                <form method="post" action="?option" role="form">
                    <h5 style="text-decoration:underline">Généralité utilisateur</h5>
                    <h5>Paramètre de la barre de navigation</h5>
                    <h5>Paramètre de votre serveur FTP/sFTP</h5>
                    <h5>Paramètre de votre serveur http</h5>
                    <h5>Support</h5>

                    <p class="text-right fix-marg-input">
                        <input type="hidden" name="user" id="user-change-config">
                        <input type="hidden" name="owner_change_config">
                        <input type="submit" name="submit" value="Enregistrer" class="btn btn-info">
                    </p>
                </form>
            </div>
        </article>
        <?php } ?>

        </section>

    </div>