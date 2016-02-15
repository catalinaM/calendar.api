<?php
namespace controllers;

use FrontController;
use App\BaseController;
use Mappers\UserMapper;
use Models\User;
use Firebase\JWT\JWT;

class UserController extends BaseController {

    public function loginAction(){
        $request = $this->getRequest();
        $existingUser = UserMapper::getInstance()->getByName($request['username']);
        if (!$existingUser->getId()) {
            $this->sendBadFilterResponse();
            return;
        }
        if (!password_verify($request['password'], $existingUser->getPasswordHash())) {
            $this->sendError('Bad credentials', 400);
            return;
        }
        $token = $this->createToken($existingUser->getId());
        $this->sendResponse(array("token"=> $token, 'user' => (array)$existingUser));
    }

    private function createToken($uid)
    {
        try {
            $secretKey = base64_decode(SECRET_KEY);
            $jwt = JWT::encode(
                array('uid' => $uid),//Data to be encoded in the JWT
                $secretKey, // The signing key
                'HS512'
            );
        } catch (\Exception $e) {
            $this->sendError('Unauthorized access', 403);
        }

        return $jwt;
    }

    public function registerAction(){
        $request = $this->getRequest();
        //TODO: validate request
        $username = $request['username'];
        $existingUser = UserMapper::getInstance()->getByName($username);

        if ($existingUser->getId()) {
            $this->sendError('User exists', 400);
            return;
        }

        $user = new User();
        $user->setEmail($request['email']);
        $user->setType(User::TYPE_USER);
        $user->setUsername($username);
        $hash = password_hash($request['password'], PASSWORD_BCRYPT);
        $user->setPasswordHash($hash);
        UserMapper::getInstance()->insert($user);

        $this->sendResponse(array("sd"=>"sad"));
    }

}

