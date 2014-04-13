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
            array_walk_recursive( $files_ticket,
                function( $a, $b) use (&$all_files_tickets)
                { 
                    $all_files_tickets[] = $a;
                });

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

    public function etatTicket($ticket)
    {
        $etat = stripos($ticket, 'support_');
        return $etat;
    }

    public function decodeJson($ticket)
    {
        $json = json_decode(file_get_contents($ticket), true);

        return $json;
    }
}