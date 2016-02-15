<?php
namespace Controllers;

use App\AuthBaseController;
use Mappers\EventMapper;
use Models\Event;

class EventController extends AuthBaseController{
	public function getAction($id){
		$event = EventMapper::getInstance()->getById($id);
		if (!$event->getId()) $this->sendBadFilterResponse();
		$this->sendResponse((array)$event);
	}

	public function createAction(){
		$request = $this->getRequest();
		$event = $this->mapRequestToModel($request);
		EventMapper::getInstance()->save($event, $request['calendar'], $this->authUser->getId());

		$this->sendResponse((array)$event);
	}
	private function mapRequestToModel($request){
		$event = new Event();
		$event->setType(Event::TYPE_USER);
		$event->setLocation($request['location']);
		$event->setComment($request['comment']);
		$event->setDescription($request['description']);
		$event->setFrom($request['from']);
		$event->setTo($request['to']);
		return $event;
	}
	public function updateAction($id){
		$event = EventMapper::getInstance()->getById($id);
		if (!$event->getId()) $this->sendBadFilterResponse();

		$request = $this->getRequest();
		$updateEvent = $this->mapRequestToModel($request);
		$updateEvent->setId($id);
		EventMapper::getInstance()->save($updateEvent, $request['calendar'], $this->authUser->getId());

		$this->sendResponse((array)$event);
	}
	public function deleteAction($id){
		$event = EventMapper::getInstance()->getById($id);
		if (!$event->getId()) $this->sendBadFilterResponse();
		EventMapper::getInstance()->delete($event, $_REQUEST['calendar'], $this->authUser->getId());

		$this->sendResponse((array)$event);
	}
}