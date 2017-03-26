<?php

namespace App\Controller;

use Apfelbox\FileDownload\FileDownload;
use App\Seedbox\FileConfiguration;
use App\Seedbox\Users;
use App\Seedbox\Utils;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class DownloadController
{
    public function download(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $file = $args['file'];
        $host = $request->getServerParams()['HTTP_HOST'];

        $username = Utils::getCurrentUser();
        $fileini = Utils::getFileini($username);
        $user = new Users($fileini, $username);

        if ($file == 'filezilla') {
            $data = FileConfiguration::filezilla($user, $host);
            $file = 'filezilla.xml';
        } elseif ($file == 'transdroid') {
            $data = FileConfiguration::transdroid($user, $host);
            $file = 'settings.json';
        } else {
            return $response->withStatus(404);
        }

        $fileDownload = FileDownload::createFromString($data);
        $fileDownload->sendDownload($file);
    }
}
