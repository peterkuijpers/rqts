<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initDoctype()
	{
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
	}

	protected function _initRestRouter()
	{
		$front  = Zend_Controller_Front::getInstance();
		$restRoute = new Zend_Rest_Route($front);
		$front->getRouter()->addRoute('default', $restRoute);
	}

}

