<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
		$user = new Application_Model_UserMapper();
		$this->view->entries = $user->fetchAll();
    }

    public function getAction()
    {
        // action body
    }

    public function putAction()
    {
        // action body
    }

    public function postAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

}









