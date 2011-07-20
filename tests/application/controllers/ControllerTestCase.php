<?php
require_once 'zZend/Test/PHPUnit/ControllerTestCase.php';

abstract class Controller_TestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
	protected function setUp()
	{
		$this->bootstrap=array($this, 'appBootstrap');
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_NonPersistent());
        parent::setUp();
	}

	protected function tearDown()
	{
		Zend_Auth::getInstance()->clearIdentity();

	}

	protected function appBootStrap()
	{
		Application::setup();
	}
}

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
