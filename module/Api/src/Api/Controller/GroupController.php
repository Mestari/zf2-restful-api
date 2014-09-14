<?php

namespace Api\Controller;

use Zend\View\Model\JsonModel;
use Api\Model\Group;

class GroupController extends MainController
{
    /**
     * Get group info
     * @return JsonModel
     */
    public function getAction()
    {
        $this->getInputGroupId();
        $this->groupInfo = $this->getGroupsTable()->getGroup($this->inputGroupId);

        return new JsonModel(array(
            'result' => $this->groupInfo,
        ));
    }

    /**
     * Create new group
     * @return JsonModel
     * @throws \Exception
     */
    public function createAction()
    {
        $this->getContentData();
        $this->checkAccess();

        if (empty($this->jsonDecodedData->group)
            || empty($this->jsonDecodedData->group->name)
        ) {
            throw new \Exception("Wrong input data");
        }

        $group = new Group();
        $group->exchangeArray($this->jsonDecodedData->group);
        $this->getGroupsTable()->saveGroup($group);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Update group info
     * @return JsonModel
     * @throws \Exception
     */
    public function updateAction()
    {
        $this->getInputGroupId();
        $this->getContentData();
        $this->checkAccess();

        if (empty($this->jsonDecodedData->group)
            || empty($this->jsonDecodedData->group->name)
        ) {
            throw new \Exception("Wrong input data");
        }

        $this->groupInfo = $this->getGroupsTable()->getGroup($this->inputGroupId);

        $newGroupData = $this->jsonDecodedData->group;
        $newGroupData->id = $this->inputGroupId;

        $group = new Group();
        $group->exchangeArray($newGroupData);
        $this->getGroupsTable()->saveGroup($group);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Delete group
     * @return JsonModel
     * @throws \Exception
     */
    public function deleteAction()
    {
        $this->getInputGroupId();
        $this->getContentData();
        $this->checkAccess();

        $this->groupInfo = $this->getGroupsTable()->getGroup($this->inputGroupId);
        $groupUsersCount = $this->getGroupsTable()->getGroupUsersCount($this->inputGroupId);

        if ($groupUsersCount > 0) {
            throw new \Exception("Can't delete group cause it is not empty");
        }

        $this->getGroupsTable()->deleteGroup($this->inputGroupId);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Get groups list
     * @return JsonModel
     */
    public function getListAction()
    {
        $result = $this->getGroupsTable()->fetchAll();

        return new JsonModel(array(
            'result' => $result,
        ));
    }
}