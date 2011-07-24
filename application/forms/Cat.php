<?php

class Application_Form_Cat extends Zend_Dojo_Form
{
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	    $this->setName("cat");
        $this->setMethod('post');

        $this->addElement('text', 'id', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 20)),
            ),
            'required'   => false,
            'label'      => 'Id',
			'readonly'	=> 'readonly',
        ));

        $this->addElement('FilteringSelect', 'statusid', array(
            'required'  => true,
            'label'     => 'Status',
			'readonly'  =>'readonly',
        ));

		$this->addElement('FilteringSelect', 'initiatorid', array(
            'required'   => true,
            'label'      => 'Raised by',
			'readonly'	=> 'readonly',
        ));

		//
		$this->addElement(
			'DateTextBox',
			'initdate',
			array(
				'value' => '2008-07-05',
				'label' => 'Date raised',
				'required'  => true,
			)
		);

		$this->addElement('FilteringSelect', 'focalid', array(
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
