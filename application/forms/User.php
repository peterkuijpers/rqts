<?php

class Application_Form_User extends Zend_Dojo_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	    $this->setName("user");
        $this->setMethod('post');

        $this->addElement('text', 'nickname', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 20)),
            ),
            'required'   => true,
            'label'      => 'Nick name',
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'Password',
        ));
        $this->addElement('text', 'firstname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'First name',
        ));

        $this->addElement('text', 'lastname', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
            'label'      => 'Last name',
        ));

        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim'),
            'validators' => array( 'EmailAddress' ),
            'required'   => true,
            'label'      => 'Email',
        ));

        $this->addElement('checkbox', 'initiator', array(
            'label'      => 'Initiator',
			'checked'    => 'checked',
        ));

        $this->addElement('checkbox', 'focal', array(
            'label'      => 'Focal',
        ));

        $this->addElement('checkbox', 'qa', array(
            'label'      => 'Quality Asssurer',
        ));

        $this->addElement('checkbox', 'adminlevel', array(
            'label'      => 'Administrator',
        ));



        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Submit',
        ));

        $this->addElement('hidden', 'id');

    }
}

