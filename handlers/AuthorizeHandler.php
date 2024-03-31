<?php

namespace Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ZxcvbnPhp\Zxcvbn;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;
require __DIR__ . '/../src/JwtTools.php';

class AuthorizeHandler {
    public function authorize(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody(), true);
        if (empty($data['email']) || empty($data['password'])) {
            return $response = response_creator(['error' => 'Missing required parameters'], 400, $response);
        }

        $email = $data['email'];
        $password = $data['password'];

        try {
            $user = R::findOne('users', 'email = ?', [$email]);
            if (!$user || !password_verify($password, $user->password)) {
                return $response = response_creator(['error' => 'Invalid email or password'], 400, $response);
            }
            $user_id = $user->id;
            $token = generateJWTToken($user_id);

            return $response = response_creator(['access_token' => $token], 200, $response);
        } catch (SQL $e) {
            return $response = response_creator(['error' => 'Service error'], 500, $response);
        }
    }
}