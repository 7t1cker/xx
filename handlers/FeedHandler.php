<?php
namespace Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response as SlimResponse; 
use Slim\Psr7\Request as SlimRequest; 

class FeedHandler {
    public function feed(SlimRequest $request, Response $response, array $args) {
        global $secretKey;

        $headers = $request->getHeader('Authorization');
        $token = $headers[0];
        if (!$token || !isValidJWTToken($token, $secretKey)) {
            return $response = response_creator(['error' => 'Unauthorized'], 401, $response);
        }
        return $response->withStatus(200);
    }

}

    

