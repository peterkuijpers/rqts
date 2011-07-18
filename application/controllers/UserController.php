<?php

class UserController extends Zend_Controller_Action
{

    protected $_FORM = null;

    public function init()
    {
        /* Initialize action controller here */
    }

    public function preDispatch()
    {
		$this->view->render('user/_sidebar.phtml');
    }

    public function indexAction()
    {
        // action body
		$user = new Application_Model_UserMapper();
		$this->view->entries = $user->fetchAll();
    }

    public function deleteAction()
    {
        // action body

		$id = $this->_getParam('id', 0);
		if ($id > 0) {
			$mapper  = new Application_Model_UserMapper();
			$mapper->delete($id);
			$this->_helper->flashMessenger->addMessage(array('successMsg'=>'User deleted successfully'));

		}	
		$this->_helper->redirector( 'index' ); // back to login page
    }

    public function addAction()
    {
        // action body
		$form = new Application_Form_User();

		$form->submit->setLabel('Add');
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				unset( $formData['id'] );
				$user = new Application_Model_User($formData);
				$user->setPassword( md5( $formData['password']));
				$mapper  = new Application_Model_UserMapper();
				$mapper->save($user);
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'User added successfully'));
				$this->_helper->redirector('index');
			} else {
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'User not added'));
				$form->populate($formData);
			}
		
		}
	}

    public function updateAction()
    {
        // action body
		$form = new Application_Form_User();
		$form->submit->setLabel('Save');
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {

				$user = new Application_Model_User($formData);
				$user->setPassword(md5($formData['password']));
				$mapper  = new Application_Model_UserMapper();
				$mapper->save($user);
				$this->_helper->redirector('index');
			} else {
				$form->populate($formData);
			}
		} else {
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$user = new Application_Model_User();
				$mapper = new Application_Model_UserMapper();
				$data = $mapper->find( $id, $user);
				$form->populate($data);
			}
		}
    }

}

