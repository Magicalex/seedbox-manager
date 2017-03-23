<?php

namespace App\Middleware;

use \App\Seedbox\Utils;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

class Auth
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $server = $request->getServerParams();

        $server['PHP_AUTH_USER'] = Utils::getCurrentUser();
        unset($server['REMOTE_USER']);

        if (isset($server['REMOTE_USER'])) {
            $response->getBody()->write('There is no authentication');
            return $response->withStatus(401);
        }

        return $next($request, $response);
    }

}
