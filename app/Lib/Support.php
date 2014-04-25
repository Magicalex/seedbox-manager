<?php

namespace app\Lib;

Class Support extends Users
{
    public function ReadTicket()
    {
        $ticketList = $this->TicketList();
        if (!empty($ticketList))
        {
            foreach ( $this->TicketList() as $encoded_ticket )
            {
                $reply = json_decode(file_get_contents($encoded_ticket), true);
                $status = self::EtatTicket($encoded_ticket);
                $ticket['ticket'][] = array( 'reply' => $reply, 'status' => $status);
                if ($status == 'open') {
                    $all_ticket_close = false;
                }
            }

            if (!isset($all_ticket_close)) {
                $all_ticket_close = true;
            }
            $ticket['allTicketClose'] = $all_ticket_close;
        }
        else
            $ticket = false;

        return $ticket;
    }

    /*
        Cette méthode crée les tickets pour les utilisateurs.
        Elle permet au admin de répondre dans les tickets

        Crée un fichier support.json dans le dossier conf/ du user qui crée le topic
    */

    public function sendTicket( $message, $destinataire)
    {
        $date = date("d/m/y \à H\hi");

        if (file_exists('./../conf/users/'.$destinataire.'/support.json'))
        {
            $open_ticket = './../conf/users/'.$destinataire.'/support.json';
            $ticket = json_decode(file_get_contents($open_ticket), true);
            $ticket[] = array( 'data' => array( 'user' => $this->userName, 'date' => $date, 'message' => $message));  
        }
        else
            $ticket = array( array( 'data' => array( 'user' => $this->userName, 'date' => $date, 'message' => $message)));

        $encoded_ticket = json_encode($ticket);
        $log_write_ticket = @file_put_contents('./../conf/users/'.$destinataire.'/support.json', $encoded_ticket.PHP_EOL);
        $result = $log_write_ticket === false ? $log_write_ticket:true;
        return $result; 
    }

    /*
        Cette méthode renvoie deux listes des fichiers tickets.
            Une liste pour l'admin.
            OU une liste pour le user.
        La liste des fichiers est sous forme d'array.
    */

    private function TicketList()
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
            // note : le caractère & fait pointer le contenu sur la variable.
            // note : fonctions anonymes prend comme param function ($value, $key)
            @array_walk_recursive( $files_ticket, function ($value) use (&$all_files_tickets) {
                $all_files_tickets[] = $value;
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
        Methode cloture de ticket:
        Cherche tous les fichiers avec l'extension avec le pattern support*.json puis les comptes
        Renomme le fichier support.json (dernier ticket) en support_X.json
    */

    public static function ClotureTicket($user)
    {
        $scan_ticket = glob('./../conf/users/'.$user.'/support*.json');
        $nb_ticket = count($scan_ticket);
        $old_file = './../conf/users/'.$user.'/support.json';
        $new_file = './../conf/users/'.$user.'/support_'.$nb_ticket.'.json';
        $result = @rename($old_file, $new_file);
        return $result;
    }

    /* Indique si un ticket est fermé ou non */

    private static function EtatTicket($file_ticket)
    {
        $status = (bool) preg_match('#support.json#', $file_ticket);
        $result = $status === true ? 'open':'close';
        return $result;
    }
}