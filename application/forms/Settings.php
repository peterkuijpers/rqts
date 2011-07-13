<?php

class Application_Form_Settings extends Zend_Form
{

    public function init()
    {
	    $this->setName("settings");
        $this->setMethod('post');

        $this->addElement('text', 'host', array(
            'filters'    => array('StringTrim'),
            'label'      => 'Mysql host',
        ));

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'label'      => 'User name',
        ));

		$this->addElement('text', 'password', array(
            'filters'    => array('StringTrim'),
            'label'      => 'Password',
        ));
        $this->addElement('text', 'dbname', array(
            'filters'    => array('StringTrim'),
            'label'      => 'Database name',
        ));

        $this->addElement('text', 'mailserver', array(
            'required'   => false,
            'label'      => 'SMTP mail server',
        ));

        $this->addElement('text', 'filedir', array(
            'required'   => false,
            'label'      => 'File download directory',
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Submit',
        ));
    }

}

