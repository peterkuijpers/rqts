<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initAutoloader()
	{
		require_once 'Zend/Loader/Autoloader.php';
		$loader = Zend_Loader_Autoloader::getInstance();
//		$loader->registerNamespace('Foo_');
//		$loader->registerNamespace(array('Foo_', 'Bar_'));

	}
	protected function _initDoctype()
	{
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
	}
/*
	protected function _initRestRouter()
	{
		$front  = Zend_Controller_Front::getInstance();
		$restRoute = new Zend_Rest_Route($front);
		$front->getRouter()->addRoute('default', $restRoute);
	}
*/
	protected function _initSidebar()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');

        $view->placeholder('sidebar')
             // "prefix" -> markup to emit once before all items in collection
             ->setPrefix("<div class=\"sidebar\">\n    <div class=\"block\">\n")
             // "separator" -> markup to emit between items in a collection
             ->setSeparator("</div>\n    <div class=\"block\">\n")
             // "postfix" -> markup to emit once after all items in a collection
             ->setPostfix("</div>\n</div>");
    }

}

