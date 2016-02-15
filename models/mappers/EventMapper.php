<?php
namespace Mappers;

use App\AbstractMapper;
use Models\Event;


class EventMapper extends AbstractMapper
{
    protected $tableName = 'events';
    protected function mapModelToArray($model){
        return array(
          'id' => $model->getId(),
          'type' => $model->getType(),
          'description' => $model->getDescription(),
          'from' => $model->getFrom(),
          'to' => $model->getTo(),
          'comment' => $model->getComment(),
          'location' => $model->getLocation(),
        );
    }
    protected function mapRowToModel($row){
        $event = new Event();
        $event->setId($row['id']);

        $event->setType($row['type']);
        $event->setDescription($row['description']);
        $event->setFrom($row['from']);
        $event->setTo($row['to']);
        $event->setComment($row['comment']);
        $event->setLocation($row['location']);

        return $event;
    }

    /**
     * @param $id
     * @return Event
     */
    public function getById($id){
        $where = array('id' => $id);
        return $this->_getBy($where);
    }

    public function delete(Event $event, $calendarId, $userId)
    {
        //TODO: implement flags and try to avoid db deleting

        //first delete it from calendar
        $this->db->where("calendar_id", $calendarId);
        $this->db->where("user_id", $userId);
        $this->db->where("event_id", $event->getId());
        $this->db->delete('users_calendars_events', 1);
        return parent::delete($event);
    }

    public function save(Event $event, $calendarId, $userId){
        parent::insert($event);
        $connection = array(
            'user_id' => $userId,
            'calendar_id' => $calendarId,
            'event_id' => $event->getId()
        );
        $this->db->insert('users_calendars_events', $connection);

    }

    /**
     * @param $userId
     * @param $calendarId
     * @param $eventId
     * @return Event
     * @throws \Exception
     */
    public function getUserCalendarEvent($userId, $calendarId, $eventId){
        $this->db->join("users_calendars_events uce", "uce.event_id=e.id", "INNER");
        $this->db->where("uce.calendar_id", $calendarId);
        $this->db->where("uce.user_id", $userId);
        $this->db->where("uce.event_id", $eventId);

        $row = $this->db->getOne("events e", null, "e.*");

        return $this->mapRowToModel($row);
    }

    public function update(Event $event, $calendarId, $userId){
        parent::update($event);
        if (!$this->getUserCalendarEvent($event->getId(), $calendarId, $userId)->getId()) {
            $connection = array(
                'user_id' => $userId,
                'calendar_id' => $calendarId,
                'event_id' => $event->getId()
            );
            $this->db->insert('users_calendars_events', $connection);
        }
    }

    /**
     * @param $userId
     * @return Event[]
     * @throws \Exception
     */
    public function findByUserId($userId, $sort = null){
        $this->db->join("users_calendars_events uce", "uce.event_id=e.id", "INNER");
        $this->db->where("uce.user_id", $userId);
        $rows = $this->db->get("events e", null, "e.*");

        $events = array();
        foreach($rows as $row){
            $events[] = $this->mapRowToModel($row);
        }

        return $events;
    }
}