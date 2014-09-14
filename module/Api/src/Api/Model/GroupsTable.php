<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;
//use Zend\Db\Sql\Sql;

class GroupsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Fetch all groups
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Get group info by ID
     * @param $id
     * @return array|\ArrayObject|null
     * @throws \Exception
     */
    public function getGroup($id)
    {
        $rowset = $this->tableGateway->select(array('id' => (int)$id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Wrong group ID: $id");
        }
        return $row;
    }

    /**
     * Get users in group
     * @param $id
     * @return null|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function getGroupUsers($id)
    {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('group_name' => 'name'));
        $sqlSelect->join('users_to_groups', 'groups.id = users_to_groups.group_id', array(), 'inner');
        $sqlSelect->join('users', 'users.id = users_to_groups.user_id', array('id', 'name'), 'inner');
        $sqlSelect->where(array('groups.id' => (int)$id));
        return $this->tableGateway->selectWith($sqlSelect);
    }

    /**
     * Get users count in group
     * @param $id
     * @return int
     */
    public function getGroupUsersCount($id)
    {
        $result = $this->getGroupUsers($id);
        return $result->count();
    }

    /**
     * Save group info
     * @param Group $group
     * @throws \Exception
     */
    public function saveGroup(Group $group)
    {
        $data = array(
            'name' => $group->name
        );

        $id = (int)$group->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getGroup($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception("Wrong group ID: $id");
            }
        }
    }

    /**
     * Delete group by ID
     * @param $id
     */
    public function deleteGroup($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}