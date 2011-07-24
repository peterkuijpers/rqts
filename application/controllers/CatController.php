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
		$this->view->render('cat/_filter.phtml');
    }

	public function indexAction()
    {

		$cat = new Application_Model_CatMapper();
		// 
		// get filter
		$filter = new Zend_Session_Namespace('filter');
		$type = $filter->type;
		$id = $filter->ncid;
		if ( $type == "MyNc" ) {
			// get 'my id'
			$auth = Zend_Auth::getInstance();
			$loginId = $auth->getIdentity()->id;
			// get all nc's that belong to me
			$ncs = $cat->fetchAllMy( $loginId );
		} elseif ( $type == "NcId" ) {
//			Zend_Debug::dump(  $id );
			$nc = new Application_Model_Cat();
			$cat->find( $id, $nc );
			$ncs = array();
			if ( $nc->getId() != null)
				$ncs[] = $nc;
		} else  // default select all
			$ncs = $cat->fetchAll();
		//
		$this->view->entries = $ncs;
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
			//
			// check if it is 'approval'
			if ( isset($formData['approve']) ) {
				$catMapper = new Application_Model_CatMapper();
				$catMapper->approve( $formData['id'] );
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'Non Compliance was changed to "NC Approved" status'));

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
				$cat = new Application_Model_Cat($formData);
				if ( isset( $formData['submit']))  {
					//submit form change status to NC_Submitted
					$this->_helper->flashMessenger->addMessage(array('successMsg'=>'Non Compliance was submitted'));
					$cat->setStatusid( 2 );
				} else
					$this->_helper->flashMessenger->addMessage(array('successMsg'=>'Non Compliance was saved'));
					//enter line in log
					$this->view->logMessage( array( 'user'=>$loginId, 'ncid'=>$cat->getId(), 'action'=>'Nc Updated', 'message'=>'NC_Draft'));

				// save to database
				$mapper  = new Application_Model_CatMapper();
				$mapper->save($cat);

				// add to registry
				$nc = new Zend_Session_Namespace('cat');
				$nc->id = $cat->getId();
				$this->_helper->redirector('index');
			} else {
				// invalid entry - resubmit form
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Non Compliance not submitted or saved'));
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
					echo '>>Owner can update draft';
					$this->setUpdateFormParams($form);
				} else {
					// display only
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
		$form = new Application_Form_Cat();
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
				$cat = new Application_Model_Cat($formData);
				if ( isset( $formData['submit']))  {
					// Submit pressed
					if ($this->additionalCheck( $cat ) ) {
						//set status to NC_Submitted
						$cat->setStatusid( 2);
						// save to database
						$mapper  = new Application_Model_CatMapper();
						$newCatId = $mapper->save($cat);

						//enter line in log
						$this->view->logMessage( array('ncid'=>$newCatId, 'user'=>'P Kuijpers', 'action'=>'New Nc', 'message'=>'NC_Draft'));
						
						/*************
						*
						* create new (empty) CC
						*
						**************/
						$cc = new Application_Model_Cc();
						$cc->setId( $newCatId);
						$ccMapper = new Application_Model_CcMapper(  );
						$ccMapper->insert( $cc );


						// add to registry
						$nc = new Zend_Session_Namespace('cat');
						$nc->id = $formData[100];
						//
						$msg = "NonCompliance with id '".$newCatId. "' added successfully";
 						$this->_helper->flashMessenger->addMessage(array('successMsg' => $msg));
						$this->_helper->redirector('index');

					} else {
						// extrea validation errors
						$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Extra validation errors'));
					}
				} else {
					// save nc with status nc_draft
					$mapper  = new Application_Model_CatMapper();
					// save
					$ncid =  $mapper->save($cat);

					//enter line in log
					$this->view->logMessage( array('ncid'=>$newCatId, 'user'=>'P Kuijpers', 'action'=>'New Nc', 'message'=>'NC_Draft'));

					/*************
					*
					* create new (empty) CC
					*
					**************/
					$cc = new Application_Model_Cc();
					$cc->setId( $ncid);
					$ccMapper = new Application_Model_CcMapper(  );
					$ccMapper->insert( $cc );

					// add to registry
					$nc = new Zend_Session_Namespace('cat');
					$nc->id = $ncid;
					// message
					$msg = 'NonCompliance with id '.$ncid . ' was saved as draft';
					$this->_helper->flashMessenger->addMessage(array('successMsg'=> $msg));

					$this->_helper->redirector('index');
				}
			} else {
				$form->populate($formData);
			}
		}
	}

	// get the filter values from the sidebar form and store them as Globals
	public function filterAction()
	{
		$this->view->selection = "All";
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
//			Zend_Debug::dump( $formData );
//			echo $formData['nc'];
			$filter = new Zend_Session_Namespace('filter');
			$filter->type = $formData['nc'];
			$filter->ncid = $formData['ncid'];

			$this->_helper->redirector('index');

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
		$form->statusid->setAttrib( 'readonly', true );

		$form->initiatorid->setAttrib( 'readonly', true );
		$form->initdate->setAttrib( 'readonly', true );
		$form->focalid->setAttrib( 'readonly', true );
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
	 * @param <type> $cat
	 * @return <type> boolean
	 */
	private function additionalCheck( $cat)
	{
		//
		$result = true;
		if (  !$cat->focalid ) {
			$result = false;
			$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Please assign the NonCompliance'));
		} elseif ( $cat->focalid == $cat->initiatorid ){
			$result = false;
			$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'You can not assign the NonCompliance to yourself'));
		}
		return $result;
	}
}





