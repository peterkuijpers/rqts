<?php

class Application_Form_Nc extends Zend_Form
{
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	    $this->setName("nc");
        $this->setMethod('post');

        $this->addElement('text', 'id', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 20)),
            ),
            'required'   => false,
            'label'      => 'Id',
        ));

        $this->addElement('select', 'statusid', array(
            'required'  => true,
            'label'     => 'Status',
        ));

		$this->addElement('select', 'initiatorid', array(
            'required'   => true,
            'label'      => 'Raised by',
        ));
        $this->addElement('text', 'initdate', array(
            'filters'    => array('StringTrim'),
            'validators' => array( 'date'),
            'required'   => true,
			'size'		=> 10,
            'label'      => 'Date raised',
        ));

        $this->addElement('select', 'focalid', array(
            'filters'    => array('StringTrim'),
            'validators' => array( 'Int' ),
            'required'   => false,
            'label'      => 'Assigned to',
        ));

        $this->addElement('text', 'summary', array(
            'filters'    => array('StringTrim'),
            'validators' => array(  array('StringLength', false, array(0, 100))
			),
            'required'   => true,
            'label'      => 'Summary',
			'size'		 => '100',
        ));

        $this->addElement('textarea', 'details', array(
            'required' => false,
            'label'    => 'Details',
			'cols'	   => '80',
			'rows'	   => '3',
        ));


    }
}