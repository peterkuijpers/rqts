<?php

class NcController extends Zend_Controller_Action
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
		$this->view->render('nc/_topmenu.phtml');
		$this->view->render('nc/_sidebar.phtml');
    }

	public function indexAction()
    {
        // action body
		$nc = new Application_Model_NcMapper();
		$ncs = $nc->fetchAll();
		$this->view->entries = $ncs;
	}
	/**
	 * Updates nc-details when status = NC_Draft (code=1) and 'raised by' is 'me'
	 * or else displays nc-details
	 */
    public function updateAction()
    {
        // action body
		$form = new Application_Form_Nc();
		$this->setGeneralFormParams($form);
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			// check if it is 'approval'
			if ( isset($formData['approve']) ) {
				$ncMapper = new Application_Model_NcMapper();
				$ncMapper->approve( $formData['id'] );
				$this->_helper->redirector('index');
			}
			// check raised-by is me and status =draft
			// get login id
			$auth = Zend_Auth::getInstance();
			$loginId = $auth->getIdentity()->id;
			if ( $loginId == $formData['initiatorid'] && $formData['statusid'] == 1 ) {
				//owner of the nc can update the nc or submit it
				echo '>>Owner can update draft';
				$this->setUpdateFormParams($form);
			} else {
				echo '>>Display only';
				$this->setViewFormParams( $form);
			}
			// change from '' to null for database
			if ($formData['focalid'] == '0') unset( $formData['focalid']);
			//
			if ($form->isValid($formData)) {
				//
				$nc = new Application_Model_Nc($formData);
				if ( isset( $formData['submit']))  {
					//submit form change status to NC_Submitted
					$nc->setStatusid( 2 );
				}
				// save to database
				$mapper  = new Application_Model_NcMapper();
				$mapper->save($nc);

				// add to registry
				$nc = new Zend_Session_Namespace('cat');
				$nc->id = $nc->getId();
				$this->_helper->redirector('index');
			} else {
				// invalid entry - resubmit form
				$form->populate($formData);
			}
		} else {
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$nc = new Application_Model_Nc();
				$mapper = new Application_Model_NcMapper();
				$data = $mapper->find( $id, $nc);
				//
				// check raised-by is me and status =draft
				// get login id
				$auth = Zend_Auth::getInstance();
				$loginId = $auth->getIdentity()->id;
				if ( $loginId == $data['initiatorid'] && $data['statusid'] == 1 ) {
					//owner of the nc can update the nc or submit it
					echo '>>Owner can update draft';
					$this->setUpdateFormParams($form);
				} else {
					echo '>>Display only';
					$this->setViewFormParams( $form);
					// check if current user is focal and nc-status = nc_submitted
					// if this is the case then allow user to approve nc
					if ( $loginId == $data['focalid'] &&$data['statusid'] == 2 ) {
						$this->setApproveFormParams($form);
					}
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
		$form = new Application_Form_Nc();
		// alter form
		$this->setGeneralFormParams($form);
		$this->setNewFormParams( $form );
		//
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($formData['focalid'] == '0') unset( $formData['focalid']);
			unset( $formData['id'] );
			if ($form->isValid($formData)) {
				$nc = new Application_Model_Nc($formData);
				if ( isset( $formData['submit']))  {
					// Submit pressed
					if ($this->additionalCheck( $nc ) ) {
						//set status to NC_Submitted
						$nc->setStatusid( 2);
						// save to database
						$mapper  = new Application_Model_NcMapper();
						$mapper->save($nc);
						// add to registry
						$nc = new Zend_Session_Namespace('cat');
						$nc->id = $formData['id'];
						$msg = "NonCompliance with ".$nc->id. " added successfully";
 						$this->_helper->flashMessenger->addMessage(array('successMsg'=>$msg));
						$this->_helper->redirector('index');

					} else {
						// extrea validation errors
						$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Extra validation errors'));
					}
				} else {
					$mapper  = new Application_Model_NcMapper();
					$mapper->save($nc);

					// add to registry
					$nc = new Zend_Session_Namespace('cat');
					$nc->id = $formData['id'];
					$this->_helper->redirector('index');
				}
			} else {
				$form->populate($formData);
			}
		}
	}
	private function setGeneralFormParams( $form )
	{
		$userMapper = new Application_Model_UserMapper();
		$allShortnamesWithEmpty['0'] = '';
		$allShortnames = $userMapper->fetchAllShortnames();
		$allShortnamesWithEmpty += $allShortnames;
		// id
		$form->id->setAttrib( 'readonly', true );
		// status
		$statlist = Zend_Registry::get('status');
		$form->statusid->setMultiOptions( $statlist);
		// initiator
		$form->initiatorid->setMultiOptions( $allShortnames);
		//focalid
		$form->focalid->setMultiOptions( $allShortnamesWithEmpty);
	}
	/**
	 * Form settings for approval of nc
	 * @param <type> $form
	 */
	private function setApproveFormParams( $form )
	{
		// add submit button to form

      $approve = new Zend_Form_Element_Submit('approve');
      $approve->setLabel('Approve')
			->setAttrib('onclick',"return confirm('Are you sure you want to approve?')" );


	  $form->addElement( $approve );
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
		$auth = Zend_Auth::getInstance();
        $id = $auth->getIdentity()->id;
		$form->initiatorid->setValue( $id );
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

	/**
	 * Extra check before submitting nc
	 * @param <type> $nc
	 * @return <type> boolean
	 */
	private function additionalCheck( $nc)
	{
		//
		$result = true;
		if (  !$nc->focalid ) {
			$result = false;
			$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Please assign the NonCompliance'));
		} elseif ( $nc->focalid == $nc->initiatorid ){
			$result = false;
			$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'You can not assign the NonCompliance to yourself'));
		}
		return $result;
	}

}

