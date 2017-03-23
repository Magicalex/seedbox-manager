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

    public function __construct(Twig $view)
    {
        $this->view = $view;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $username = Utils::getCurrentUser();
        $user_name_php = Install::get_user_php();
        $root_path = substr(getcwd(), 0, -7);

        return $this->view->render($response, 'install.twig.html', [
            'username' => $username
        ]);
    }
}
