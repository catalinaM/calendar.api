<?php
namespace App;

use Firebase\JWT\JWT;
use Mappers\UserMapper;

class AuthBaseController extends BaseController{
	/**@var \Models\User $authUser*/
	protected $authUser;

    public function __construct() {
		$headers = apache_request_headers();
		if(isset($headers['Authorization'])){
			if (isset($_SERVER['PHP_AUTH_USER'])) {
				$token = $_SERVER['PHP_AUTH_USER'];
			} else {
				$matches = array();
				preg_match('/(Token token="(.*)")|(Basic (.*)=)/', $headers['Authorization'], $matches);
				if(isset($matches[4])){
					$token = base64_decode($matches[4]);
				}
			}
		}

		if (!isset($token))  $this->sendError('Unauthorized access', 403);

		$this->verifyToken($token);
	}


	private function verifyToken($token){
		$secretKey = base64_decode(SECRET_KEY);
		$jwt = JWT::decode($token, $secretKey, array('HS512'));
		if (!isset($jwt->uid)) return false;
		$user = UserMapper::getInstance()->getById($jwt->uid);

		if (!$user->getId()) return false;

		$this->authUser = $user;
	}
}
