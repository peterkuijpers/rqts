<?php

class AttachmentController extends Zend_Controller_Action
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
		$nc = $this->view->getCurrentNc();
		$thisDir= $this->view->getDownloadDir( $nc )."/.";

		echo $thisDir. "<br/>";

		$list = array();
		$fi =array();
		$cnt = 1;
		if ( $thisDir ) {
			$dir = new DirectoryIterator(dirname( $thisDir ));
			foreach ($dir as $fileinfo) {
				if (! $fileinfo->isDot()) {
					$fi['name'] =  $fileinfo->getFilename();
					$fi['size'] = $fileinfo->getSize();
					$fi['time'] = $fileinfo->getCTime();
					$list[] = $fi;
				}
			}
		}
		$this->view->files = $list;	
    }

    public function downloadAction()
    {
        // action body
    }

    public function uploadAction()
    {
        // action body
		$form = new Application_Form_Attachment();

		$this->view->form = $form;

		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {

				$adapter = new Zend_File_Transfer_Adapter_Http();

				$nc = $this->view->getCurrentNc();
				$thisDir= $this->view->getDownloadDir( $nc )."/.";
				$destination = $thisDir . $fromData['filename'];

				$adapter->setDestination( $destination );
				if (!$adapter->receive()) {
					$messages = $adapter->getMessages();
					echo implode("\n", $messages);
				}
//				$user = new Application_Model_User($formData);
//				$user->setPassword( md5( $formData['password']));
//				$mapper  = new Application_Model_UserMapper();
//				$mapper->save($user);
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'Attachment uploaded successfully'));
				$this->_helper->redirector('index');
			} else {
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'File not uploaded'));
				$form->populate($formData);
			}
		}

		
    }

    public function deleteAction()
    {
        // action body
    }


}







