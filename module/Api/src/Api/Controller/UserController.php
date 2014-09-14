<?php

namespace Api\Controller;

use Zend\View\Model\JsonModel;
use Api\Model\User;

class UserController extends MainController
{
    /**
     * Get user info
     * @return JsonModel
     */
    public function getAction()
    {
        $this->getInputUserId();
        $this->userInfo = $this->getUsersTable()->getUser($this->inputUserId);

        return new JsonModel(array(
            'result' => $this->userInfo,
        ));
    }

    /**
     * Create new user
     * @return JsonModel
     * @throws \Exception
     */
    public function createAction()
    {
        $this->getContentData();
        $this->checkAccess();

        if (empty($this->jsonDecodedData->user)
            || empty($this->jsonDecodedData->user->name)
            || empty($this->jsonDecodedData->user->role)
            || empty($this->jsonDecodedData->user->password)
        ) {
            throw new \Exception("Wrong input data");
        }

        $user = new user();
        $user->exchangeArray($this->jsonDecodedData->user);
        $this->getUsersTable()->saveUser($user);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Update user info
     * @return JsonModel
     * @throws \Exception
     */
    public function updateAction()
    {
        $this->getInputUserId();
        $this->getContentData();
        $this->checkAccess();

        if (empty($this->jsonDecodedData->user)) {
            throw new \Exception("Wrong input data");
        }

        $this->userInfo = $this->getUsersTable()->getUser($this->inputUserId);

        $newUserData = $this->jsonDecodedData->user;
        $newUserData->id = $this->inputUserId;

        $user = new user();
        $user->exchangeArray($newUserData);
        $this->getUsersTable()->saveUser($user);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Delete user
     * @return JsonModel
     */
    public function deleteAction()
    {
        $this->getInputUserId();
        $this->getContentData();
        $this->checkAccess();
        $this->userInfo = $this->getUsersTable()->getUser($this->inputUserId);

        $this->getUsersTable()->deleteuser($this->inputUserId);
        $this->getUsersToGroupsTable()->delete($this->inputUserId);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Add user to group
     * @return JsonModel
     * @throws \Exception
     */
    public function addToGroupAction()
    {
        $this->getInputUserId();
        $this->getInputGroupId();
        $this->getContentData();
        $this->checkAccess();
        $this->userInfo = $this->getUsersTable()->getUser($this->inputUserId);
        $this->groupInfo = $this->getGroupsTable()->getGroup($this->inputGroupId);

        if ($this->getUsersToGroupsTable()->get($this->inputUserId, $this->inputGroupId)) {
            throw new \Exception("User " . $this->userInfo->name . " already in group " . $this->groupInfo->name);
        }

        $this->getUsersToGroupsTable()->save($this->inputUserId, $this->inputGroupId);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Delete user from group
     * @return JsonModel
     * @throws \Exception
     */
    public function deleteFromGroupAction()
    {
        $this->getInputUserId();
        $this->getInputGroupId();
        $this->getContentData();
        $this->checkAccess();
        $this->userInfo = $this->getUsersTable()->getUser($this->inputUserId);
        $this->groupInfo = $this->getGroupsTable()->getGroup($this->inputGroupId);

        if (!$this->getUsersToGroupsTable()->get($this->inputUserId, $this->inputGroupId)) {
            throw new \Exception("User " . $this->userInfo->name . " not in group " . $this->groupInfo->name);
        }

        $this->getUsersToGroupsTable()->delete($this->inputUserId, $this->inputGroupId);

        return new JsonModel(array(
            'result' => 'OK',
        ));
    }

    /**
     * Get users list
     * @return JsonModel
     */
    public function getListAction()
    {
        $result = $this->getUsersTable()->fetchAll();

        return new JsonModel(array(
            'result' => $result,
        ));
    }
}