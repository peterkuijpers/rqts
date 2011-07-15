<?php

class CcItemController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function preDispatch()
    {
    }

    public function postDispatch()
    {
		$this->view->render('cat/_topmenu.phtml');
		$this->view->render('cat/_sidebar.phtml');
    }

    public function indexAction()
    {
			// get current nc
			$cat = $this->view->getCurrentNc();
			$ccMapper = new Application_Model_CcItemMapper( );
			// get all action items for the nc
			$this->view->entries = $ccMapper->fetchAllItemsForNc( $cat->getId() );
    }

    public function newAction()
    {
		$form = new Application_Form_CcItem();
		//
		// load all owners for pull-down box
		$userMapper = new Application_Model_UserMapper();
		$allShortnames = $userMapper->fetchAllShortnames();
		$form->ownerid->setMultiOptions( $allShortnames);
		
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			$formData['ncid'] = $this->view->getCurrentNc()->getId();
			if ( $formData['completiondate'] == '' )  unset($formData['completiondate']);
			unset( $formData['id'] );
			if ($form->isValid($formData)) {
				// save to database
				$ccitem = new Application_Model_CcItem( $formData);
				$mapper  = new Application_Model_CcItemMapper();
				Zend_Debug::dump( $formData );
				$mapper->save($ccitem);
				$this->_helper->redirector('index');
			} else {
				$form->populate($formData);
			}
		}
		
    }

    public function updateAction()
    {
		$form = new Application_Form_CcItem();
		//
		// load all owners for pull-down box
		$userMapper = new Application_Model_UserMapper();
		$allShortnames = $userMapper->fetchAllShortnames();
		$form->ownerid->setMultiOptions( $allShortnames);

		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			$formData['ncid'] = $this->view->getCurrentNc()->getId();
			if ( $formData['completiondate'] == '' )  unset($formData['completiondate']);
			// unset( $formData['id'] );
			if ($form->isValid($formData)) {
				// save to database
				$ccitem = new Application_Model_CcItem( $formData);
				$mapper  = new Application_Model_CcItemMapper();
				$mapper->save($ccitem);
				$this->_helper->redirector('index');
			} else {
				$form->populate($formData);
			}
		} else {
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$ccitem = new Application_Model_CcItem();
				$ccitemMapper = new Application_Model_CcItemMapper();
				$data = $ccitemMapper->find( $id, $ccitem);
				
				$form->populate($data);
			}
		}

    }

    public function deleteAction()
    {
		$id = $this->_getParam('id', 0);
		if ($id > 0) {
			$mapper  = new Application_Model_CcItemMapper();
			$mapper->delete($id);
		}
		$this->_helper->redirector( 'index' ); // back to login page
    }


}


