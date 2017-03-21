<?php

use \Seedbox\Users;

$isAdmin = function ($request, $response, $next) use ($file_user_ini, $username) {

    $user = new Users($file_user_ini, $username);

    if ($user->is_owner() === false){
        return $response->withStatus(403);
    }

    return $next($request, $response);
};
