<?php

require_once 'PHPUnit/Framework/TestCase.php';

//class AuthControllerTest extends PHPUnit_Framework_TestCase
class AuthControllerTest extends Zend_Test_PHPUnit_ControllerTestCase
{

    public function setUp()
    {
        /* Setup Routine */
		 $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }
	public function appBootstrap()
	{
		$this->frontController
				->registerPlugin( new Buggapp_Plugin_Initialize('development' ));
	}




    public function tearDown()
    {
        /* Tear Down Routine */
    }


}

