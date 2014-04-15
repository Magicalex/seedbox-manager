<?php

Class Support extends Users {

    /*
        Cette méthode crée les tickets pour les utilisateurs.
        Elle permet au admin de répondre dans les tickets

        Crée un fichier support.json dans le dossier conf/ du user qui crée le topic
    */

    public function sendTicket( $message, $destinataire)
    {
        if ($this->is_owner === true)
            $name = $destinataire;
        else
            $name = $this->userName;

        $date = date("d/m/Y \à H:i:s");

        if (file_exists('./../conf/users/'.$name.'/support.json'))
        {
            $open_ticket = './../conf/users/'.$name.'/support.json';
            $ticket = json_decode(file_get_contents($open_ticket));
            $ticket[] = array( 'data' => array( 'user' => $this->userName, 'date' => $date, 'message' => $message));
            $encoded_ticket = json_encode($ticket);
            file_put_contents('./../conf/users/'.$name.'/support.json', $encoded_ticket.PHP_EOL);

            return array( 'file_exist' => true);
        }
        else
        {
            $ticket = array( array('data' => array( 'user' => $this->userName, 'date' => $date, 'message' => $message)));
            $encoded_ticket = json_encode($ticket);
            file_put_contents('./../conf/users/'.$this->userName.'/support.json', $encoded_ticket.PHP_EOL);

            return array( 'file_exist' => false);
        }
    }

    /*
        Cette méthode renvoie deux listes des fichiers tickets.
            Une liste pour l'admin.
            OU une liste pour le user.

        La liste des fichiers est sous forme d'array.
    */

    public function ticketList()
    {
        if ($this->is_owner === true)
        {
            $all_users = $this->get_all_users();
            foreach ( $all_users as $user )
            {
                if ( $user != $this->userName)
                    $files_ticket[] = glob('./../conf/users/'.$user.'/support*.json');
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
            $files_tickets = glob('./../conf/users/'.$this->userName.'/support*.json');
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
        $scan_ticket = glob('./../conf/users/'.$user.'/support*.json');
        $nb_ticket = count($scan_ticket);

        return rename('./../conf/users/'.$user.'/support.json', './../conf/users/'.$user.'/support_'.$nb_ticket.'.json' );
    }

    /* retourne si un ticket est fermé ou non */

    public function EtatTicket($ticket)
    {
        $etat = stripos($ticket, 'support_');
        return $etat;
    }

    /* Méthode qui décode le ticket */

    public function decodeJson($ticket)
    {
        $json = json_decode(file_get_contents($ticket), true);
        return $json;
    }
}