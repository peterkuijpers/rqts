<?php

class CcItemController extends Zend_Controller_Action
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
		$this->view->render('ccitem/_sidebar.phtml');
    }
    public function indexAction()
    {
			// get current nc
			$cat = $this->view->getCurrentNc();

			$ccMapper = new Application_Model_CcItemMapper( );
			$this->view->entries = $ccMapper->fetchAllItemsForNc( $cat->getId() );
	}


}

