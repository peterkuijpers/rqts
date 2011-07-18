<?php

class SettingsController extends Zend_Controller_Action
{

	protected $_data = array();

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {


		$form = new Application_Form_Settings();
		$this->view->form = $form;


		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				// save
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'Updated successfully'));
				$this->_helper->redirector('index');
			} else {
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Not updated'));
				$form->populate($formData);
			}
		} else {
			$data = $this->readData();
			$form->populate( $data);
		}
	}


	public function readData()
	{
		$config = new Zend_Config_Xml( APPLICATION_PATH . '/configs/settings.xml',
						  null,
						  array('skipExtends'        => true,
								'allowModifications' => true));
		$result = array();
		$result[ 'host'] = $config->database->host;
		$result[ 'password'] = $config->database->password;
		$result['username'] = $config->database->username;
		$result['dbname'] = $config->database->dbname;

		$result['mailserver'] = $config->mailserver;
		$result['filedir'] = $config->filedir;
		return  $result;
	}




}


/*

		// Modify a value
		$config->production->hostname = 'foobar';

		// Write the config file
		$writer = new Zend_Config_Writer_Xml(array('config'   => $config,
                                           'filename' => APPLICATION_PATH . '/configs/settings.xml'));
		$writer->write();
    }

*/

