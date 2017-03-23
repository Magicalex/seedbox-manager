<?php

namespace App\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

class Auth
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $server = $request->getServerParams();
        $auth = self::auth($server);

        if ($auth === false) {
            $response->getBody()->write('Error authentication');
            return $response->withStatus(401);
        }

        return $next($request, $response);
    }

    protected function auth($server)
    {
        if ( (isset($server['REMOTE_USER'])) || (isset($server['PHP_AUTH_USER'])) ) {
            $auth = true;
        }

        return (isset($auth)) ? true : false;
    }

}
