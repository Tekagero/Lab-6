<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\Validation\EnterValidation;
use App\Validation\RegistrationValidation;
use App\Models\User;

class AuthController extends BaseController
{
    public function errorHandler(array $errors, string $newError) 
    {
        if($newError !== '') {
            array_push($errors, $newError);
        }
        $response = [
            "status" => false,
            "errors" => $errors
        ];
        return $response;
    }

    public function registration(array $parameters): Response
    {
        $data = $this->request->all();
        $validator = new RegistrationValidation($data);
        $validator->isValid();
        $errors = $validator->getErrors();

        if (!empty($errors)) {
            $response = errorHandler($errors, "");
            return (new Response())->setContent(json_encode($response))
            ->headers->set('Content-Type', 'application/json');
        }

        $user_pdo = new User();

        if (!$user_pdo->isFreeEmail($data['email'])) {
            $response = errorHandler($errors, "This email is used by another user");
            return (new Response())->setContent(json_encode($response))
            ->headers->set('Content-Type', 'application/json');
        }
        $user_id = $user_pdo->addUser($data['name'], $data['email'], $data['password'], $data['phone_number']);

        $session = $this->request->getSession();
        $session->set('user_id', $user['id']);
        $session->set('is_moderator', 0);
        $session->set('user_name', $user['name']);

        $response = [
            "status" =>true,
            "user_login"=>$data['email'],
            "is_moderator"=>0
        ];
        return (new Response())->setContent(json_encode($response))
        ->headers->set('Content-Type', 'application/json');

    }

    public function auth(array $parameters): Response
    {
        $data = $this->request->all();
        $validator = new EnterValidation($data);
        $validator->isValid();
        $errors = $validator->getErrors();

        if (!empty($errors)) {
            $response = errorHandler($errors, "");
            return (new Response())->setContent(json_encode($response))
            ->headers->set('Content-Type', 'application/json');
        }
        $user_pdo = new User();
        $user = $user_pdo->getUserByEmail($data['email']);
        $save_password = htmlspecialchars($data['password']);

        $verified = password_verify($save_password, $user['password_hash']);
        
        if(!$verified) {
            $response = errorHandler($errors, "wrong password");
            return (new Response())->setContent(json_encode($response))
                ->headers->set('Content-Type', 'application/json');
        }
        
        $session = $this->request->getSession();
        $session->set('user_id', $user['id']);
        $session->set('is_moderator', $user['is_moderator']);
        $session->set('user_name', $user['name']);

        $response =[
           "status" =>true,
            "user_login"=>$user['email'],
            "is_moderator"=>$user['is_moderator']
        ];

        return (new Response())->setContent(json_encode($response))
        ->headers->set('Content-Type', 'application/json');
    }

    public function logout(array $parameters): Response
    {
        $this->request->getSession()->clear();
        return (new Response())->headers->set('Refresh', '0; url=index.php');
    }
}