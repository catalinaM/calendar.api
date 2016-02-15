<?php
namespace App;

class AbstractMapper {
    static protected $instances;

    protected $tableName = null;
    protected $db;
    protected $primary = null;

    /**
     * @return static
     */
    static function getInstance() {
        $class = \get_called_class();
        if (!isset(static::$instances[$class])) {
            static::$instances[$class] = new static;
        }
        return static::$instances[$class];
    }
    private function getPrimaryKey(){
        $this->db->rawQuery("SHOW KEYS FROM {$this->tableName} WHERE Key_name = \'PRIMARY\'");
    }

    protected function  __construct()
    {
        $this->db = \MysqliDb::getInstance();
    }

    protected function _findBy($where, $order=null) {
        foreach($where as $column => $value){
            $this->db->where($column, $value);
        }
        if ($order) {
            foreach($order as $column => $orderType){
                $this->db->orderBy($column, $orderType);
            }
        }
        $rows = $this->db->get($this->tableName);
        $results = array();
        foreach($rows as $row){
            $results = $this->mapRowToModel($row);
        }
        return $results;
    }

    protected function _getBy($where) {
        foreach($where as $column => $value){
            $this->db->where ($column, $value);
        }
        $result = $this->db->getOne($this->tableName);
        return $this->mapRowToModel($result);

    }

    protected function mapRowToModel($row){

    }

    protected function mapModelToArray($model){

    }

    public function insert($model) {
        $data = $this->mapModelToArray($model);

        $id = $this->db->insert ($this->tableName, $data);

        if (isset($model->id)) $model->setId($id);

    }
    public function delete($model) {
        $array = $this->mapModelToArray($model);
        $primaryKeys = $this->getPrimaryKey();

        foreach($primaryKeys as $column){
            $this->db->where($column,  $array[$column]);
        }
        return $this->db->delete($this->tableName);

    }

    public function update($model) {
        $array = $this->mapModelToArray($model);
        $primaryKeys = $this->getPrimaryKey();
        foreach($primaryKeys as $column){
            $this->db->where($column,  $array[$column]);
        }
        $this->db->update($this->tableName, $array);
    }
}