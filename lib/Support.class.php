<?php 

Class Support extends Users {

	public function sendTicket($message,$destinataire) 
    {
        if ($this->is_owner === true)
            $name = $destinataire;
        else
            $name = $this->userName;

        $message = htmlspecialchars($message);            
        $date = date("d/m/Y \à H:i:s");       
        
        if (file_exists('./conf/users/'.$name.'/support.json'))
        {
            $supjson = './conf/users/'.$name.'/support.json';
            $json = json_decode(file_get_contents($supjson));

            $json[] = array( 'datas' => array('user' => $this->userName, 'date' => $date, 'message' => $message));
            $jsonencod = json_encode($json);
            file_put_contents('./conf/users/'.$name.'/support.json', $jsonencod.PHP_EOL); 
            
            return array( 'file_exist' => true);
        }
        else
        {
            $json = array ( array ('datas' => array( 'user' => $this->userName, 'date' => $date, 'message' => $message)));
            $jsonencod = json_encode($json);
            file_put_contents('./conf/users/'.$this->userName.'/support.json', $jsonencod.PHP_EOL, FILE_APPEND);
            
            return array( 'file_exist' => false);
        }
    }
   
	/*
        Cette méthode renvoie deux listes des fichiers tickets.
            Une liste pour l'admin.
            OU une liste pour le user.

        La liste est sous forme d'array.
    */
    
    public function ticketList()
    {
        if ($this->is_owner === true)
        {
            $all_users = $this->get_all_users();
            foreach ( $all_users as $user )
            {
                if ( $user != $this->userName)
                    $files_ticket[] = glob('./conf/users/'.$user.'/support*.json');
            }

            //converti un tableau multidimensionnel en un tableau unidimensionnel.
            array_walk_recursive( $files_ticket, function( $a, $b) use (&$all_files_tickets) { $all_files_tickets[] = $a; } );
            return $all_files_tickets;
        }
        else
        {
            $files_tickets = glob('./conf/users/'.$this->userName.'/support*.json');
            return $files_tickets;
        }
    }


    /*
        Methode cloture :
        Cherche tous les fichiers avec l'extension avec le pattern support*.json puis les comptes
        Renomme le fichier support.json (dernier ticket) en support_X.json
    */

	public function cloture($user)
    {
        $scan_ticket = glob('./conf/users/'.$user.'/support*.json');
        $nb_ticket = count($scan_ticket);

        return rename('./conf/users/'.$user.'/support.json', './conf/users/'.$user.'/support_'.$nb_ticket.'.json' );
    }

    public function afficheList($list,$userName,$user) 
    {
        $i = 0;
        $num_ticket = 1;
       
        foreach ($list as $i => $ticket)
        {   
            if ($user->is_owner() === true) 
            {
                echo '<tr>'; 
                echo '<td><button class="btn ';
                if (stripos($ticket, 'support_') === false) {echo 'btn-danger" data-toggle="collapse"';} else echo 'btn-success"';
                echo ' data-target="#bouton_'.$i.'">'.$i.'</button></td>';
            }
            $json = json_decode(file_get_contents($ticket), true);
            $j=0;
            if ($user->is_owner()) 
            {
                $userSupport = $json[0]['datas']['user'];

                echo "<td>".$userSupport."</td>"; //affichage du user qui a ouver le support
                
                if ( stripos($ticket, 'support_') === false )
                {
                    echo "<td>Ouvert</td>";
                } else echo "<td>Fermer</td>";
                
                echo "<td>".$json[0]['datas']['date']."</td>"; //Date d'ouverture
                echo "<td>".$json[(count($json)-1)]['datas']['date']."</td>"; //Date de derniere modification (Dernier message envoier)
                
                // Formulaire pour fermer le topic si ouvert.
                
                if ( stripos($ticket, 'support_') === false )
                {
                    $supp= array('./conf/users/','/support.json'); //chaine a supprimer
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="user" value="'.str_replace($supp, "", $ticket).'" class="btn btn-info">';
                    echo '<td><input type="submit" name="cloture" value="Fermer le Ticket" class="btn btn-info">';
                    echo '</form>';
                    echo '</tr>';
                }
                echo '<div id="bouton_'.$i.'" class="collapse">';
            }
            echo '<div class="panel panel-default">';
            while ($j != count($json))
            {
                if ($userName != $json[$j]['datas']['user']) 
                { 
                    echo '<div class="panel-heading">Message de '.$json[$j]['datas']['user']; 

                } 
                else  echo '<div class="panel-heading">Votre message';

                echo ' - le '.$json[$j]['datas']['date'].'</div><br />';
                echo '<div class="panel-body">'.nl2br($json[$j]['datas']['message']).'</div>';
                echo '<br />';
                $j++;
            }
            $num_ticket++;
            echo '</div>';
            if ( stripos($ticket, 'support_') === false && $user->is_owner()) {
                echo '<form method="post" action="index.php">
                    <fieldset>
                        <div class="form-group">
                            <label for="support">Réponse</label>
                            <textarea class="form-control" name="message" rows="3"></textarea>
                        </div>
                    </fieldset>
                    <p class="text-right fix-marg-input">
                        <input type="hidden" name="user" value="'.$userSupport.'" class="btn btn-info">
                        <input type="submit" name="support" value="Repondre au Ticket" class="btn btn-info">                            
                    </p>
                </form>';                                   
            }  
        }
        

    }
}