<?php

class CatController extends Zend_Controller_Action
{
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
		$cc = new Application_Model_CcMapper();
		// 
		// get filter
		$filter = new Zend_Session_Namespace('filter');
		$type = $filter->type;
		$id = $filter->ncid;
		if ( $type == "MyNc" ) {
			// get 'my id'
			$user = $this->view->getCurrentUser();
			// get all nc's that belong to me
			$ncs = $cat->fetchAllMy( $user->getId() );
		} elseif ( $type == "NcId" ) {
//			Zend_Debug::dump(  $id );
			$nc = new Application_Model_Cat();
			$cat->find( $id, $nc );
			$ncs = array();
			if ( $nc->getId() != null)
				$ncs[] = $nc;
		} else { // default select all
			$ncs = $cat->fetchAll();
		}
		//
		$this->view->ncs = $ncs;
    }

	/**
	 * Updates nc-details when status = NC_Draft (code=1) and 'raised by' is 'me'
	 * or else displays nc-details
	 */
    public function updateAction()
    {
		$form = new Application_Form_Cat();
		$this->setGeneralFormParams($form);
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();	
			/*****************************************
			 * check raised-by is me and status =draft
			 * get login id
			 **************************************/
			$user = $this->view->getCurrentUser();
			if ( $user->getId() == $formData['initiatorid'] && $formData['statusid'] == 1 ) {
				//owner of the nc can update the nc or submit it
				$this->_helper->flashMessenger->addMessage( array('infoMsg' =>'>>Owner can update draft' ));
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
					if ( ! $this->additionalCheck( $cat ) )
						return;
					else {
						// Submit
						$cat->setStatusid( 2 );
						$msg = 'NonCompliance was submitted';
						//enter line in log
						$logMsg = 'NC Submitted';
					}
				} else {
					// Save
					$msg = 'NonCompliance was saved';
					//log msg
					$logMsg = 'NC Draft saved';
				}

				// save to database
				$mapper  = new Application_Model_CatMapper();
				$mapper->save($cat);
				// show message
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>$msg));
				//add line in log
				$this->view->logMessage( array( 'user'=>$user->getShortname(), 'ncid'=>$cat->getId(), 'action'=>'Nc status updated', 'message'=>$logMsg));
				// add to registry
				$nc = new Zend_Session_Namespace('cat');
				$nc->id = $cat->getId();
				$this->_helper->redirector('index');
			} else {
				// invalid entry - resubmit form
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Non Compliance not submitted or saved'));
				$form->populate($formData);
			}
		} else {   // not post
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$cat = new Application_Model_Cat();
				$catMapper = new Application_Model_CatMapper();
				$data = $catMapper->find( $id, $cat);
				//
				// check raised-by is me and status =draft
				// get login id
				$user = $this->view->getCurrentUser();
				if ( $user->getId() == $data['initiatorid'] && $data['statusid'] == 1 ) {
					//owner of the nc can update the nc or submit it
					$this->_helper->flashMessenger->addMessage( array('infoMsg' =>'>>Owner can update draft<<' ));

//					echo '>>Owner can update draft';
					$this->setUpdateFormParams($form);
				} else {
					// display only
//					echo '>>Display only';
					$this->_helper->flashMessenger->addMessage( array('infoMsg' =>'>> Display only <<' ));
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
		$user = $this->view->getCurrentUser();
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
						$this->view->logMessage( array('ncid'=>$newCatId, 'user'=>$user->getShortname(), 'action'=>'New Nc', 'message'=>'NC_Draft'));
						/*************
						* create new (empty) CC
						**************/
						$cc = new Application_Model_Cc();
						$cc->setId( $newCatId);
						$ccMapper = new Application_Model_CcMapper(  );
						$ccMapper->insert( $cc );


						// add to registry
						$nc = new Zend_Session_Namespace('cat');
						$nc->id = $newCatId;
						//
						$msg = "NonCompliance with id '".$newCatId. "' added successfully";
 						$this->_helper->flashMessenger->addMessage(array('successMsg' => $msg));
						$this->_helper->redirector('index');

					} 
				} else {
					// save nc with status nc_draft
					$mapper  = new Application_Model_CatMapper();
					// save
					$ncid =  $mapper->save($cat);

					//enter line in log
					$this->view->logMessage( array('ncid'=>$newCatId, 'user'=>$user->getShortname(), 'action'=>'New Nc', 'message'=>'NC_Draft'));
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

	/*
	 * Approve a nc
	 *
	*/
	public function approveAction()
	{
		$nc = $this->view->getCurrentNc();
		$user = $this->view->getCurrentUser();
		// update database
		$catMapper = new Application_Model_CatMapper();
		$nc->setStatusid( 3 );
		$catMapper->save( $nc );
		// message to screen
		$str = "NonCompliance ".$nc->getId(). ", status changed to 'Approved'";
		$this->_helper->flashMessenger->addMessage(array('successMsg'=>$str));
		// enter in log
		$this->view->logMessage( array( 'user'=>$user->getShortname(), 'ncid'=>$nc->getId(), 'action'=>'Nc status changed', 'message'=>'NC_Approved'));

		$this->_helper->redirector('index');


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
		$form->statusid->setMultiOptions( Zend_Registry::get('reverseStatus') );
		// initiator
		$form->initiatorid->setMultiOptions( $allShortnames);
		//focalid
		$form->focalid->setMultiOptions( $allShortnamesWithEmpty);
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

	/*
	 * Adjust form for updates (like adding a submit and a save button)
 	 *
	 */
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
		$user = $this->view->getCurrentUser();
		$form->initiatorid->setValue( $user->getId() );
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
		if (  ! $cat->focalid ) {
			$result = false;
			$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Please assign the NonCompliance to someone'));
		} elseif ( $cat->focalid == $cat->initiatorid ){
			$result = false;
			$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'You can not assign the NonCompliance to yourself'));
		}
		return $result;
	}
}





