<?php

namespace App\Controller;

use App\Seedbox\Users;
use App\Seedbox\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Flash\Messages as Flash;
use Slim\Views\Twig;
use Symfony\Component\Translation\Translator;
use WriteiniFile\WriteiniFile;

class AdminController
{
    protected $view;
    protected $flash;
    protected $username;
    protected $fileini;
    protected $user;
    protected $router;

    public function __construct(Twig $view, Flash $flash, Translator $translator, $router)
    {
        $this->view = $view;
        $this->flash = $flash;
        $this->router = $router;

        $this->username = Utils::getCurrentUser();
        $this->fileini = Utils::getFileini($this->username);
        $this->user = new Users($this->fileini, $this->username);

        $translator->setLocale($this->user->language);
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'admin.twig.html', [
            'user' => $this->user,
            'member' => $this->user,
            'all_users' => Utils::get_all_users(),
            'notifications' => $this->flash->getMessages()
        ]);
    }

    public function user(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $username = $args['username'];
        $member = new Users(__DIR__."/../../conf/users/{$username}/config.ini", $username);

        return $this->view->render($response, 'admin.twig.html', [
            'user' => $this->user,
            'member' => $member,
            'all_users' => Utils::get_all_users(),
            'notifications' => $this->flash->getMessages()
        ]);
    }

    public function update(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $param = $request->getParsedBody();
        $username = $args['username'];
        $update = new WriteiniFile(__DIR__."/../../conf/users/{$username}/config.ini");
        $update->update([
            'user' => [
                'user_directory' => $param['user_directory'],
                'scgi_folder' => $param['scgi_folder']
            ],
            'nav' => [
                'data_link' => $param['data_link']
            ],
            'ftp' => [
                'port_ftp' => $param['port_ftp'],
                'port_sftp' => $param['port_sftp']
            ],
            'support' => [
                'adresse_mail' => $param['adresse_mail']
            ]
        ]);
        $logs = $update->write();
        $this->flash->addMessage('admin_update_ini', $logs);

        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('adminProfil', ['username' => $username]));
    }

    public function delete(ServerRequestInterface $request, ResponseInterface $response)
    {
        $username = $request->getParsedBody()['deleteUserName'];
        $logs = Utils::delete_config_old_user(__DIR__."/../../conf/users/{$username}");
        $this->flash->addMessage('admin_delete_user', $logs);
        $this->flash->addMessage('admin_delete_user', $username);

        return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('admin'));
    }
}
