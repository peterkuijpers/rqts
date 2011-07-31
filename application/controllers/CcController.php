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
		// get current user
		$user = $this->view->getCurrentUser();
		//
		$ccItemMapper = new Application_Model_CcItemMapper( );

		// get all action items for the nc
		$this->view->actions = $ccItemMapper->fetchAllItemsForCc( $cat->getId() );

		$cc = new Application_Model_Cc();
		$ccMapper = new Application_Model_CcMapper();
		$data = $ccMapper->find( $cat->getId(), $cc);
		$data['statusid'] = $cat->getStatusid();
		$form = new Application_Form_Cc();
		//
		// get all users to populate pull-down
		$userMapper = new Application_Model_UserMapper();
		$allShortnames = $userMapper->fetchAllShortnames();
		$form->assigneeid->setMultiOptions( $allShortnames);
		$form->id->setValue( $cat->getId() );
		//
		// populate status pulldown
		$form->statusid->setMultiOptions( Zend_Registry::get('reverseStatus') );
		$statId = $cat->getStatusid();

		// if current user is focal and ( status is nc_approved or cc_planrejected )
		if ( $user->getId() == $cat->getFocalid() && ( $statId == 3 || $statId = 6) ) {
			$form->addElement('submit', 'submit', array(
				'required' => false,
	            'ignore'   => true,
	            'label'    => 'Submit C&C',
			));
			// enable QA selection
			$form->assigneeid->setAttrib( 'readonly', false );
		}
		$form->populate($data);
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
				//write to log
				$this->view->logMessage( array( 'user'=>$user->getShortname(), 'ncid'=>$cat->getId(), 'action'=>'CC update', 'message'=>'CC_Submitted'));
				//
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'C & C submitted'));
				$this->_helper->redirector('index');
			} else {
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'CC not updated'));
				$form->populate($formData);
			}
		}
	
	}

	/*
	 * C&C approved by QA
	 * Change status to CC_PlanApproved
	 */
	public function approveplanAction()
	{
		// update the nc status to CC_PlanApproved ( code = 5  )
		//and save updated status to the db
		$nc = $this->view->getCurrentNc();
		$nc->setStatusid( 5 );
		$ncMapper = new Application_Model_CatMapper();
		$ncMapper->save( $nc );

		$user = $this->view->getCurrentUser();
		//write to log
		$this->view->logMessage( array( 'user'=>$user->getShortname(), 'ncid'=>$nc->getId(), 'action'=>'CC update', 'message'=>'CC_Approved'));
		//
		$this->_helper->flashMessenger->addMessage(array('successMsg'=>'C & C Approved'));
		$this->_helper->redirector('index');
	}
	/*
	 * C&C rejected by QA
	 * Change status to CC_PlanRejected
	 */
	public function rejectplanAction()
	{
		// update the nc status to CC_submitted ( code = 6 )
		$cat = $this->view->getCurrentNc();
		$cat->setStatusid( 6 );
		//and save updated status to the db
		$ncMapper = new Application_Model_CatMapper();
		$ncMapper->save( $cat );
		//write to log
		$user = $this->view->getCurrentUser();
		$this->view->logMessage( array( 'user'=>$user->getShortname(), 'ncid'=>$cat->getId(), 'action'=>'CC update', 'message'=>'CC Plan Rejected'));
		//
		$this->_helper->flashMessenger->addMessage(array('successMsg'=>'C&C Plan Rejected'));
		$this->_helper->redirector('index');
	}
}

