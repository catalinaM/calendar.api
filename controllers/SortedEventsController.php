<?php
namespace Controllers;

use App\AuthBaseController;

class EventsController extends AuthBaseController {
	public function getAction(){

		$this->sendResponse(array("sd"=>"sad"));
	}
}