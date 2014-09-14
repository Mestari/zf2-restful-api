<?php

namespace Api\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersToGroupsTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Check user in group
     * @param $user_id
     * @param $group_id
     * @return array|\ArrayObject|null
     */
    public function get($user_id, $group_id)
    {
        $rowset = $this->tableGateway->select(array('user_id' => (int)$user_id, 'group_id' => (int)$group_id));
        return $rowset->current();
    }

    /**
     * Add user to group
     * @param $user_id
     * @param $group_id
     */
    public function save($user_id, $group_id)
    {
        $this->tableGateway->insert(array('user_id' => (int)$user_id, 'group_id' => (int)$group_id));
    }

    /**
     * Delete user from group
     * @param $user_id
     * @param null $group_id
     */
    public function delete($user_id, $group_id = null)
    {
        $data = array('user_id' => (int)$user_id);
        if (!empty($group_id)) {
            $data['group_id'] = (int)$group_id;
        }
        $this->tableGateway->delete($data);
    }
}