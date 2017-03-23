<?php

namespace App\Middleware;

use \App\Seedbox\Install;
use \App\Seedbox\Utils;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

class Installed
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $username = Utils::getCurrentUser();

        if (false === is_writable(__DIR__.'/../../conf/users')) {
            return $response->withStatus(302)->withHeader('Location', '/install');
        } elseif (false === file_exists(__DIR__."/../../conf/users/{$username}/config.ini")) {
            Install::create_new_user($username);
        } elseif (false === file_exists(__DIR__."/../../conf/users/{$username}/config.ini")) {
            //
        }

        return $next($request, $response);
    }

    /*

    // check install
    if (false === is_writable(__DIR__.'/../conf/users')) {
        require __DIR__.'/../public/install/installation.php';
        exit(1);
    } elseif (file_exists(__DIR__."/../conf/users/{$username}/config.ini")) {
        $file_user_ini = __DIR__."/../conf/users/{$username}/config.ini";
    } else {
        Install::create_new_user($username);
        $file_user_ini = __DIR__."/../conf/users/{$username}/config.ini";
    }

    */
}
