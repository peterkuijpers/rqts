<?php

class CatController extends Zend_Controller_Action
{

    public function init()
    {
    }

   public function postDispatch()
    {
		$this->view->render('cat/_sidebar.phtml');
    }

    public function indexAction()
    {
        // action body
		$cat = new Application_Model_CatMapper();
		$this->view->entries = $cat->fetchAll();

		//unregister cat
		$nc = new Zend_Session_Namespace('cat');
		unset( $nc->id);
    }

    public function updateAction()
    {
        // action body


		$form = new Application_Form_Cat();
//		$form->submit->setLabel('Save');
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$cat = new Application_Model_Cat($formData);
				$mapper  = new Application_Model_CatMapper();
				$mapper->save($cat);

				// add to registry
				$nc = new Zend_Session_Namespace('cat');
				$nc->id = $cat->getId();
				echo $cat->getId();
				$this->_helper->redirector('index');
			} else {
				$form->populate($formData);
			}
		} else {
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$cat = new Application_Model_Cat();
				$mapper = new Application_Model_CatMapper();
				$data = $mapper->find( $id, $cat);
				$form->populate($data);

				// add to registry
				$nc = new Zend_Session_Namespace('cat');
				$nc->id = $id;
			}
		}
    }

    public function addAction()
    {
        // action body
		// action body
		$form = new Application_Form_Cat();
//		$form->submit->setLabel('Save');
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$cat = new Application_Model_Cat($formData);
				$mapper  = new Application_Model_CatMapper();
				$mapper->save($cat);
				
				// add to registry
				$nc = new Zend_Session_Namespace('cat');
				$nc->id = $formData['id'];

				$this->_helper->redirector('index');
			} else {
				$form->populate($formData);
			}
		}
	}
}





