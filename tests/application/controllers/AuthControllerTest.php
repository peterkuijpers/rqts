<?php

//require_once 'PHPUnit/Framework/TestCase.php';

require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';
require_once 'ControllerTestCase.php';
//class AuthControllerTest extends PHPUnit_Framework_TestCase
//class AuthControllerTest extends Zend_Test_PHPUnit_ControllerTestCase

class AuthControllerTest extends Controller_TestCase
{
	public function myVeryFirstTest()
	{
		$this->dispatch('/auth');
		$this->assertController('auth');
		$this->assertAction('index');
	}

}

