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

		$thisDir= $this->view->getDownloadDir( $nc );

		echo $thisDir;
		
    }

    public function downloadAction()
    {
        // action body
    }

    public function uploadAction()
    {
        // action body
    }


}





