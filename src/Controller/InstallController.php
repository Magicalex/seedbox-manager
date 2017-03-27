<?php

namespace App\Controller;

use App\Seedbox\Install;
use App\Seedbox\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class InstallController
{
    protected $view;
    protected $username;
    protected $fileini;
    protected $router;

    public function __construct(Twig $view, $router)
    {
        $this->view = $view;
        $this->router = $router;
        $this->username = Utils::getCurrentUser();
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        if (false === $this->isAlreadyInstalled()) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('home'));
        }

        $user_name_php = Install::get_user_php();
        $root_path = getcwd();

        return $this->view->render($response, 'install.twig.html', [
            'username' => $this->username,
            'user_php' => $user_name_php,
            'root_path' => $root_path,
            'theme' => 'default'
        ]);
    }

    protected function isAlreadyInstalled()
    {
        $path_conf = __DIR__.'/../../conf/users';
        $path_reboot = __DIR__.'/../../reboot-rtorrent';

        if (is_writable($path_conf) && Install::getChmod($path_reboot, 4) == 4755) {
            $result = true;
        }

        return isset($result) ? false : true;
    }
}
