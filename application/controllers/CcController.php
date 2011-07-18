<?php

class CcController extends Zend_Controller_Action
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

        // action body
		// get current nc
		$cat = $this->view->getCurrentNc();
		$ccItemMapper = new Application_Model_CcItemMapper( );
		// get all action items for the nc
		$this->view->actions = $ccItemMapper->fetchAllItemsForCc( $cat->getId() );


		$form = new Application_Form_Cc();

		// get all users to populate pull-down
		$userMapper = new Application_Model_UserMapper();
		$allShortnames = $userMapper->fetchAllShortnames();
		$form->assigneeid->setMultiOptions( $allShortnames);
		$form->id->setValue( $cat->getId() );
		
		$statId = $cat->getStatusid();

		// if status is nc_approved
		if ( $statId == 3 ) {
			$form->addElement('submit', 'submit', array(
				'required' => false,
	            'ignore'   => true,
	            'label'    => 'Submit C&C',
			));
		}
		$this->view->form = $form;

		if ( $this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$cc = new Application_Model_Cc($formData);
				$ccMapper  = new Application_Model_CcMapper();
				// update the cc in the database
				$ccMapper->update($cc);
				// update the nc status to CC_submitted ( code =4 )
				$cat->setStatusid( 4 );
				//and save updated status to the db
				$ncMapper = new Application_Model_CatMapper();
				$ncMapper->save( $cat );
				//
				//
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'C & C submitted'));
				$this->_helper->redirector('index');
			} else {
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'CC not updated'));
				$form->populate($formData);
			}
		}
	}


}

