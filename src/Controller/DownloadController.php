<?php

namespace App\DownloadController;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Stream;

class DownloadController
{
    public function download(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $file = $args['file'];

        $file = '/path/to';
        $fh = fopen($file, 'rb');
        $stream = new Stream($fh); // create a stream instance for the response body

        $response->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="'.basename($file).'"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);

        return $response;
    }
}

// $downlad = function ($file_config_name, $conf_ext_prog) {
//
//     file_put_contents('../conf/users/' . $this->userName . '/' . $file_config_name, $conf_ext_prog);
//
//     $file_size = filesize($path_file_name);
//
//     header('Content-Description: File Transfer');
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename="' . $file_name . '"');
//     header('Content-Transfer-Encoding: binary');
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//     header('Pragma: public');
//     header('Content-Length: ' . filesize($path_file_name));
//
//     //delete file config (transdroid|filezilla) for security.
//     unlink('../conf/users/' . $this->userName . '/' . $file_config_name);
//
//     exit;
// }
