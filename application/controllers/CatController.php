<?php

class CatController extends Zend_Controller_Action
{
	public  $_filtered;

    public function init()
    {
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
		$cat = new Application_Model_CatMapper();
		$ncs = $cat->fetchAll();
		$this->view->entries = $ncs;
/*
		//unregister cat
		$nc = new Zend_Session_Namespace('cat');
		unset( $nc->id);
 * 
 */
    }
	/**
	 * Updates nc-details when status = NC_Draft (code=1) and 'raised by' is 'me'
	 * or else displays nc-details
	 */
    public function updateAction()
    {
        // action body
		$form = new Application_Form_Cat();
		$this->setGeneralFormParams($form);

		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$cat = new Application_Model_Cat($formData);
				if ( isset( $formData['submit']))  {
					echo 'form submitted';
					//submit form change status to NC_Submitted
					$cat->setStatusid( 2 );
				}
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
				//
				// check raised-by is me and status =draft
				// get login id
				$auth = Zend_Auth::getInstance();
				$loginId = $auth->getIdentity()->id;
				if ( $loginId == $data['initiatorid'] && $data['statusid'] == 1 ) {
					//owner of the nc can update the nc or submit it
					echo 'Owner can update draft';
					$this->setUpdateFormParams($form);
				} else {
					echo 'Display only';
					$this->setViewFormParams( $form);
				}
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
		// alter form
		$this->setGeneralFormParams($form);
		$this->setNewFormParams( $form );
		//
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			unset( $formData['id'] );
			if ($form->isValid($formData)) {
				$cat = new Application_Model_Cat($formData);
				if ( isset( $formData['submit']))  {
					// Submit pressed
					//set status to NC_Submitted
					$cat->setStatusid = 2;
				}
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

	private function setGeneralFormParams( $form )
	{
		$userMapper = new Application_Model_UserMapper();
		$allShortnames['0'] = '';
		$allShortnames += $userMapper->fetchAllShortnames();
		// id
		$form->id->setAttrib( 'readonly', true );
		// status
		$statlist = Zend_Registry::get('status');
		$form->statusid->setMultiOptions( $statlist);
		// initiator
		$form->initiatorid->setMultiOptions( $allShortnames);
		//focalid
		$form->focalid->setMultiOptions( $allShortnames);
	}
	/**
	 * View form sets form params for only viewing
	 * @param <type> $form
	 */
	private function setViewFormParams( $form )
	{
		$form->id->setAttrib( 'readonly' , true);
		$form->initdate->setAttrib( 'readonly', true );
		$form->summary->setAttrib( 'readonly', true );
		$form->details->setAttrib( 'readonly', true );


	}

	private function setUpdateFormParams( $form )
	{
		// add submit button to form
        $form->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Submit',
        ));

		// add save button to form
		$form->addElement('submit', 'save', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Save',
        ));

	}

	private function setNewFormParams( $form)
	{
		//
		// set default status to NC_draft (=1 )
		$form->statusid->setValue( 1 );
		// initiatorid
		// set to current login user
//		$form->initiatorid->setMultiOptions( $allShortnames);
		$auth = Zend_Auth::getInstance();
        $id = $auth->getIdentity()->id;
		$form->initiatorid->setValue( $id );
		//focalid
//		$form->focalid->setMultiOptions( $allShortnames);
		// date  raised default to today
		$form->initdate->setValue( date('Y-m-d') );

		// add submit button to form
        $form->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Submit',
        ));

		// add save button to form
		$form->addElement('submit', 'save', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Save',
        ));
	
	}
}





