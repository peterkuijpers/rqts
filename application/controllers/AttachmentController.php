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
    }


}





