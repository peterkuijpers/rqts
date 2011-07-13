<?php

class CatController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }
   public function preDispatch()
    {
		$this->view->render('cat/_sidebar.phtml');
    }


    public function indexAction()
    {
        // action body
		$cat = new Application_Model_CatMapper();
		$this->view->entries = $cat->fetchAll();
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
				$this->_helper->redirector('index');
			} else {
				$form->populate($formData);
			}
		}
	}


}





