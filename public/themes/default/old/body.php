


<!-- NOTIFICATION -->
<?php } if ( isset($_POST['support']) && $supportInfo['file_exist'] === true) {?>
<div class="alert alert-success">Votre ticket a était envoyé.</div>
<?php } if ( isset($_POST['cloture']) && $cloture === true) {?>
<div class="alert alert-success">Ticket a était fermé.</div>
<?php } if ( isset($_POST['cloture']) && $cloture === false ) {?>
<div class="alert alert-info">Erreur de fermeture</div>
<?php }?>

<section class="row">

<!-- BLOC SUPPORT -->
    <?php } if ( $user->blocSupport() === true ) { ?>
        <article class="col-md-6">
            <div class="well well-sm">
                <h4 class="icone-seed-managersupport titre-head">Contacter le Support</h4>
                <div class="trait"></div>

                <?php if ( $user->is_owner() === true) {  ?>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Utilisateur</th>
                                        <th>Status</th>
                                        <th>Créé le</th>
                                        <th>Màj</th>
                                        <th>Fermer/Supprimer</th>
                                    </tr>
                                </thead>
                                <tbody>
                <?php
                            $list = $support->ticketList();
                            
                            if (!empty($list)) //Vérification de l'exitence d'un ticket. 
                            {
                                foreach ($list as $i => $ticket)
                                {   
                                    $etat = $support->EtatTicket($ticket); //Ticket ouvert ou fermé
                                    $json = $support->decodeJson($ticket); // Decode du json
                                    $nbTicket = count($json);
                                    $userSupport = $json[0]['datas']['user']; // Nom de la personne qui a ouvert du ticket
                                    $ouvertureDate = $json[0]['datas']['date']; // Date d'ouverture
                                    $dateModif = $json[(count($json)-1)]['datas']['date']; // //Date de derniere modification (Dernier message envoier)
                                    $supp = array('./conf/users/','/support.json'); //chaine a supprimer
                ?> 
                                    <tr> 
                                        <td>
                                            <button class="btn <?php if ($etat === false) {echo 'btn-danger';} else  {echo 'btn-success';}?>" data-toggle="collapse" data-target="#bouton_<?php echo $i ?>"><?php echo $i ?></button>
                                        </td> 
                <?php                                                                                      
                                        echo "<td>".$userSupport."</td>";
                                        if ( $etat === false )
                                        {
                                            echo "<td>Ouvert</td>";
                                        } else echo "<td>Fermer</td>";

                                        echo "<td>".$ouvertureDate."</td>"; 
                                        echo "<td>".$dateModif."</td>"; 
                                        if ( $etat === false )
                                        {
                                            echo '<form method="post" action="">';
                                            echo '<input type="hidden" name="user" value="'.str_replace($supp, "", $ticket).'" class="btn btn-info">';
                                            echo '<td><input type="submit" name="cloture" value="Fermer le Ticket" class="btn btn-info">';
                                            echo '</form>';                                       
                                        }
                                        echo '</tr>';
                                    echo '<div id="bouton_'.$i.'" class="collapse">';

                                    // affichage des messages
            
                                    echo '<div class="panel panel-default">';
                                    
                                    $j=0;
                                    while ($j != $nbTicket)
                                    {
                                        if ($userName != $userSupport)                                             
                                            echo '<div class="panel-heading">Message de '.$json[$j]['datas']['user']; 

                                        else  
                                            echo '<div class="panel-heading">Votre message';

                                        echo ' - le '.$json[$j]['datas']['date'].'</div>';
                                        echo '<div class="panel-body">' . nl2br($json[$j]['datas']['message']) . '</div>';
                                        $j++;
                                    }
                                    echo '</div>';
                                    if ($etat===false) {
                ?>
                                        <form method="post" action="index.php">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <label for="support">Répondre</label>
                                                        <textarea class="form-control" name="message" rows="3"></textarea>
                                                    </div>
                                                </fieldset>
                                                <p class="text-right fix-marg-input">
                                                    <input type="hidden" name="user" value="<?php echo $userSupport; ?>" class="btn btn-info">
                                                    <input type="submit" name="support" value="Repondre au Ticket" class="btn btn-info">                            
                                                </p>
                                            </form>
                <?php
                                    }
                                }
                            }                                                                        
                            else { ?>
                                <div class="panel panel-default"><div class="panel-body">Il n'y a pas de message.</div></div>
                            <?php } ?>
                            </tbody></table>
                <?php   } 
                        else 
                        { 
                            $list = $support->ticketList();
                            

                            if (!empty($list)) {
                                foreach ($list as $i => $ticket)
                                {   
                                    $etat = $support->EtatTicket($ticket);
                                    if ($etat == false){
                                        
                                        $json = $support->decodeJson($ticket); // Decode du json
                                        $nbTicket = count($json);
                                        $userSupport = $json[0]['datas']['user'];
                                        echo '<div class="panel panel-default">';
                                    
                                    
                                        $j=0;
                                        while ($j != $nbTicket)
                                        {
                                            if ($userName != $userSupport)                                             
                                                echo '<div class="panel-heading">Message de '.$json[$j]['datas']['user']; 

                                            else  
                                                echo '<div class="panel-heading">Votre message';

                                            echo ' - le '.$json[$j]['datas']['date'].'</div>';
                                            echo '<div class="panel-body">'.nl2br($json[$j]['datas']['message']).'</div>';
                                            $j++;
                                        }
                                        echo '</div>';
                                    }
                                }
                            }
                ?>
                            <form method="post" action="index.php">
                                <fieldset>
                                    <div class="form-group">
                                        <label for="support">Message du ticket</label>
                                        <textarea class="form-control" name="message" rows="3"></textarea>                                    
                                    </div>
                                </fieldset>
                            
                                <p class="text-right fix-marg-input"> 
                                    <input type="hidden" name="user" value="<?php echo $userName; ?>" class="btn btn-info">
                                    <input type="submit" name="support" value="Envoyer un Ticket" class="btn btn-info">                            
                                </p>                        
                            </form>
                <?php   } ?>
                <address>
                    <strong>Adresse mail :</strong>
                    <?php echo '<a href="mailto:'.$user->supportMail().'" target="_blank">'.$user->supportMail().'</a>'; ?>
                </address>
            </div>
        </article>

        <?php } ?>

    </section>
    </div><!-- FIN DIV CONTENEUR -->