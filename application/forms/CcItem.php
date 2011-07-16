<?php

class Application_Form_CcItem extends Zend_Form
{
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	    $this->setName("ccitem");
        $this->setMethod('post');


        $this->addElement('text', 'description', array(
            'required' => true,
            'label'    => 'Description',
			'size'	   => '80',
        ));

		$this->addElement('select', 'ownerid', array(
            'required'   => true,
            'label'      => 'Owner',
        ));

        $this->addElement('text', 'duedate', array(
            'filters'    => array('StringTrim'),
            'validators' => array( 'date'),
            'required'   => true,
			'size'		=> 10,
            'label'      => 'Due date',
        ));

		$this->addElement('text', 'completiondate', array(
            'filters'    => array('StringTrim'),
            'validators' => array( 'date'),
            'required'   => false,
			'size'		=> 10,
            'label'      => 'Comnpletion date',
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Submit',
        ));

        $this->addElement('hidden', 'id');


    }
}
