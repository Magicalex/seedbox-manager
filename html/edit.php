    <!-- CONTENEUR -->
    <div class="container marg">
        <h1 class="page-header dashboard"><i class="glyphicon glyphicon-wrench"></i> Paramètres de <?php echo $userName; ?></h1>
        
        <?php // PROBLEME AVEC EMPTY REVOIR CA if ( empty($uplog['function_write_ini_file']) && empty($uplog['bad_chmod_user_folder'])  &&  empty($uplog['not_acces_file_ini']) ) { ?>

        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Vos préférences ont été mises à jour avec succès.
        </div>

        <?php // } else { ?>

        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Une erreur est survenue, impossible de mettre à jour votre configuration.
            <?php // AFFICHER LE BON MESSAGE D'ERREUR ?>
        </div>

        <?php //} ?>

        <section class="row">
            <article class="col-md-6">
                <div class="well well-sm">
                    <h4 class="titre-head">Configuration de l'interface</h4>
                    <div class="trait"></div>

                    <form method="post" action="?edit" role="form">
                        <div class="checkbox">
                            <input type="checkbox" name="blocinfo" value="true" id="blocinfo" <?php if ($user->blocInfo() === true) echo 'checked'; ?>><label for="blocinfo"><span class="ui"></span> Activer le bloc information utilisateur</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="blocftp" value="true" id="blocftp" <?php if ($user->blocFtp() === true) echo 'checked'; ?>><label for="blocftp"><span class="ui"></span> Activer le bloc accès ftp / sftp / transdroid</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="blocrtorrent" value="true" id="blocrtorrent" <?php if ($user->blocRtorrent() === true) echo 'checked'; ?>><label for="blocrtorrent"><span class="ui"></span> Activer le bloc gestion de rtorrent</label>
                        </div>
                        <div class="checkbox">
                            <input type="checkbox" name="blocsupport" value="true" id="blocsupport" <?php if ($user->blocSupport() === true) echo 'checked'; ?>><label for="blocsupport"><span class="ui"></span> Activer le bloc support</label>
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
                            <input type="submit" name="submit" value="Enregistrer" class="btn btn-info">
                        </p>
                    </form>
                </div>
            </article>
        </section>
    </div>