<?php
namespace Controllers;

use App\AuthBaseController;
use Mappers\EventMapper;

class CalendarController extends AuthBaseController{

	/**
	 * @param $calendar_id
	 * @Route("calendar/events/:calendar_id")
	 * @Method("GET")
	 */
	public function getAction($calendar_id){

		if (!$calendar_id) $this->sendBadFilterResponse();

		$uid = $this->authUser->getId();
		$sort = (isset($request['sort']) && $request['sort'] == true);
		$events = EventMapper::getInstance()->findByUserIdAndCalendar($uid, $calendar_id, $sort);

		$this->sendResponse(array("collection" => serialize($events)));
	}

}