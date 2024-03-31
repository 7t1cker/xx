<?php

namespace Handlers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use ZxcvbnPhp\Zxcvbn;
use RedBeanPHP\R;
use RedBeanPHP\RedException\SQL;
require __DIR__ . '/../db/db.php';
require __DIR__ . '/../src/ResponseCreator.php';

class RegisterHandler {
    public function register(Request $request, Response $response, array $args) {
        $data = json_decode($request->getBody(), true);
        if (empty($data['email']) || empty($data['password'])) {
            return $response = response_creator(['error' => 'Missing required parameters'], 400, $response);
        }

        $email = $data['email'];
        $password = $data['password'];

        try {
            $existingUser = R::findOne('users', 'email = ?', [$email]);
            if ($existingUser) {
                return $response = response_creator(['error' => 'User with this email already exists'], 400, $response);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $response = response_creator(['error' => 'Invalid email format'], 400, $response);
            }

            $check_password = new Zxcvbn();
            $state_password = $check_password->passwordStrength($password);

            if ($state_password['score'] <= 2) {
                return $response = response_creator(['error' => 'weak_password'], 400, $response);
            }

            $user = R::dispense('users');
            $user->email = $email;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            R::store($user);

            $password_check_status = 'good';
            if ($state_password['score'] == 4) {
                $password_check_status = 'perfect';
            }

            $result = [
                'user_id' => $user->id,
                'password_check_status' => $password_check_status,
            ];
            return $response = response_creator($result, 201, $response);
        } catch (SQL $e) {
            return $response = response_creator(['error' => 'Service error'], 500, $response);
        } catch (\Exception $e) {
            return $response = response_creator(['error' => 'Service error'], 500, $response);
        }
    }
}