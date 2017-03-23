<?php

namespace App\Controller;

use \App\Seedbox\Install;
use \App\Seedbox\Utils;
use \Slim\Views\Twig;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

class InstallController
{
    protected $view;
    protected $username;

    public function __construct(Twig $view)
    {
        $this->view = $view;
        $this->username = Utils::getCurrentUser();
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $user_name_php = Install::get_user_php();
        $root_path = substr(getcwd(), 0, -7);

        return $this->view->render($response, 'install.twig.html', [
            'username' => $this->username,
            'user_php' => $user_name_php,
            'root_path' => $root_path
        ]);
    }
}
