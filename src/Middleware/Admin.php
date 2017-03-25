<?php

namespace App\Middleware;

use App\Seedbox\Users;
use App\Seedbox\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Admin
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $username = Utils::getCurrentUser();
        $file_user_ini = Utils::getFileini($username);
        $user = new Users($file_user_ini, $username);

        if ($user->isAdmin === false) {
            return $response->withStatus(403);
        }

        return $next($request, $response);
    }
}
