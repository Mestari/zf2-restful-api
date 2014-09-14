<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Fetch all users
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Get user info by ID
     * @param $id
     * @return array|\ArrayObject|null
     * @throws \Exception
     */
    public function getUser($id)
    {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Wrong user ID: $id");
        }
        return $row;
    }

    /**
     * Save user info
     * @param User $user
     * @throws \Exception
     */
    public function saveUser(User $user)
    {
        if ($user->name) {
            $data['name'] = $user->name;
        }

        if ($user->role) {
            $data['role'] = $user->role;
        }

        if ($user->password) {
            $data['password'] = md5($user->password);
        }

        $id = (int)$user->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getUser($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception("Wrong user ID: $id");
            }
        }
    }

    /**
     * Delete user by ID
     * @param $id
     */
    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}