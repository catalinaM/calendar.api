<?php
namespace Mappers;

use App\AbstractMapper;
use Models\Calendar;


class CalendarMapper extends AbstractMapper
{
    protected $tableName = 'calendars';
    protected function mapModelToArray($model){
        return array(
          'id' => $model->getId(),
          'type' => $model->getType(),
          'name' => $model->getName(),
        );
    }
    protected function mapRowToModel($row){
        $event = new Event();
        $event->setId($row['id']);
        $event->setType($row['type']);
        $event->setName($row['name']);

        return $event;
    }

    /**
     * @param $id
     * @return Calendar
     */
    public function getById($id){
        $where = array('id' => $id);
        return $this->_getBy($where);
    }

}