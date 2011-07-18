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
		$this->view->form = $form;
/*
		$config = new Zend_Config_Xml( APPLICATION_PATH . 'configs/settings.xml',
                              null,
                              array('skipExtends'        => true,
                                    'allowModifications' => true));

		// Modify a value
		$config->production->hostname = 'foobar';

		// Write the config file
		$writer = new Zend_Config_Writer_Xml(array('config'   => $config,
                                           'filename' => APPLICATION_PATH . 'configs/settings.xml'));
		$writer->write();
    }

*/
	}
}

