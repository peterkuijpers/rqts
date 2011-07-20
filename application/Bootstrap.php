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
	/**
	 * Initiate topmenu
	 */
	protected function _initTopmenu()
    {
        $this->bootstrap('View');
        $view = $this->getResource('View');

        $view->placeholder('topmenu')
             // "prefix" -> markup to emit once before all items in collection
             ->setPrefix("<div class=\"top_menu\">\n    <div class=\"block\">\n")
             // "separator" -> markup to emit between items in a collection
             ->setSeparator("</div>\n    <div class=\"block\">\n")
             // "postfix" -> markup to emit once after all items in a collection
             ->setPostfix("</div>\n</div>");
    }

	protected function _initNamespace()
	{
		Zend_Session::start();
//		$nc = new Zend_Session_Namespace('cat');
	}

	protected function _initStatuscodes()
	{
		$values = array(
			'1' => 'NC_Draft',
			'2' => 'NC_Submitted',
			'3' => 'NC_Approved',
			'4' => 'CC_Submitted',
		);
		Zend_Registry::set( 'status', $values );
	}

	protected function _initDownloadDir()
	{

	}
/*
	public function _initDojo()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');

		$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');

		Zend_Dojo::enableView($view);
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewRenderer->setView($view);
	}
*/
}

