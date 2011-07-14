<?php

class CcItemController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

	public function preDispatch()
	{
		$this->view->render('ccitem/_topmenu.phtml');
	}

	public function postDispatch()
    {
		$this->view->render('ccitem/_sidebar.phtml');
    }
    public function indexAction()
    {

			$cc = new Application_Model_CcItemMapper();
			$this->view->entries = $cc->fetchAll();
    }


}

