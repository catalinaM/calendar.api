<?php
namespace Mappers;

use App\AbstractMapper;
use Models\User;


class UserMapper extends AbstractMapper
{
    protected $tableName = 'users';

    protected function mapModelToArray($model){
        return array(
          'id' => $model->getId(),
          'type' => $model->getType(),
          'username' => $model->getUsername(),
          'email' => $model->getEmail(),
          'password_hash' => $model->getPasswordHash(),
        );
    }
    protected function mapRowToModel($row){
        $user = new User();
        $user->setId($row['id']);
        $user->setType($row['type']);
        $user->setUsername($row['username']);
        $user->setEmail($row['email']);
        $user->setPasswordHash($row['password_hash']);

        return $user;
    }

    /**
     * @param $id
     * @return User
     */
    public function getById($id){
        $where = array('id' => $id);
        return $this->_getBy($where);
    }

    /**
     * @param $name
     * @return User
     */
    public function getByName($name){
        $where = array('username' => $name);
        return $this->_getBy($where);
    }
}