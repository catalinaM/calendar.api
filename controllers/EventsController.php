<?php
namespace Controllers;

use App\AuthBaseController;
use Mappers\EventMapper;

class EventsController extends AuthBaseController {
	/**
	 * @Route("events")
	 * @Method("GET")
	 */
	public function getAction(){
		$uid = $this->authUser->getId();
		$request = $this->getRequest();
		$sort = (isset($request['sort']) && $request['sort'] == true);
		$events = EventMapper::getInstance()->findByUserId($uid, $sort);

		$this->sendResponse(array("collection" => serialize($events)));
	}
}