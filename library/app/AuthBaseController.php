<?php
namespace App;

use Firebase\JWT\JWT;
use Mappers\UserMapper;

class AuthBaseController extends BaseController{
	/**@var \Models\User $authUser*/
	protected $authUser;

    public function __construct() {
        parent::__construct();

		$token = null;
		$headers = apache_request_headers();
		if(isset($headers['Authorization'])){
			$matches = array();
			preg_match('/Token token="(.*)"/', $headers['Authorization'], $matches);
			if(isset($matches[1])){
				$token = $matches[1];
			}
		}
		if (!isset($token))  $this->sendError(403);

		$this->verifyToken($token);
	}


	private function verifyToken($token){
		$secretKey = base64_decode(SECRET_KEY);
		$jwt = JWT::decode($token, $secretKey, array('HS512'));
		if (!$jwt['id']) return false;
		$user = UserMapper::getInstance()->getById($jwt['id']);

		if (!$user->getId()) return false;

		$this->authUser = $user;
	}
}
