<?php

class SettingsController extends Zend_Controller_Action
{
	private $_CONFIGFILE =  '/configs/application.ini';


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
				//
				// save
				$this->writeData( $formData );
				//
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
		$config = new Zend_Config_Ini( APPLICATION_PATH . $this->_CONFIGFILE, 'development');

		$result = array();
		$result[ 'host'] = $config->resources->db->params->host;
		$result[ 'password'] = $config->resources->db->params->password;
		$result['username'] = $config->resources->db->params->username;
		$result['dbname'] = $config->resources->db->params->dbname;

		$result['mailserver'] = $config->mailserver;
		$result['filedir'] = $config->filedir;
		return  $result;
	}

	public function writeData( $data )
	{
		$config = new Zend_Config_Ini( APPLICATION_PATH . $this->_CONFIGFILE, null,
					array('skipExtends' => true, 'allowModifications' => true) );
		


		$config->setExtend('development', 'production');


		$config->development->mailserver = $data['mailserver'];
		$config->development->filedir = $data['filedir'];

		$writer = new Zend_Config_Writer_Ini(array('config'   => $config,
                                           'filename' => APPLICATION_PATH . $this->_CONFIGFILE));
		$writer->write();
	}
}