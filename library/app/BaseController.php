<?php
namespace App;

class BaseController extends \Pux\Controller{
	protected $request;

	public function delete(){
		$this->sendError('Not found.', 404);
	}

	public function get(){
		$this->sendError('Not found.', 404);
	}

	public function update(){
		$this->sendError('Not found.', 404);
	}

	public function post(){
		$this->sendError('Not found.', 404);
	}

	protected function getRequest(){
		if ($this->request) return $this->request;
		switch($_SERVER['REQUEST_METHOD'])
		{
			case 'GET': $request = $_GET; break;
			case 'POST': $request = $_POST; break;
			case 'PUT': $request = $_POST; break;
			default:
		}
		$this->request = $request;

		return $this->request;
	}
	protected function sendError($err, $httpCode = 500) {
		http_response_code($httpCode);

		$output = array(
			'error' => $err,
			'errorCode' => $httpCode
		);
		echo json_encode($output);
		exit;
	}

	protected function sendResponse($data, $httpCode = 200) {
		http_response_code($httpCode);
		header('Content-type:application/json;charset=utf-8');
		echo json_encode($data);
		exit;
	}

	protected function sendBadFilterResponse($httpCode = 400) {
		http_response_code($httpCode);
		header('Content-type:application/json;charset=utf-8');
		echo json_encode(array('message' => 'Bad filter'));
		exit;
	}

}
