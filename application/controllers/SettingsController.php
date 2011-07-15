<?php

class SettingsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
		$form = new Application_Form_Settings();

		$config = new Zend_Config_Ini( APPLICATION_PATH . 'configs/application.ini',
                              null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));

		// Modify a value
		$config->production->hostname = 'foobar';

		// Write the config file
		$writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                           'filename' => APPLICATION_PATH . 'configs/application.ini'));
		$writer->write();
    }


}
