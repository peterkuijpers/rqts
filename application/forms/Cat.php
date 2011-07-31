<?php

class Application_Form_Cat extends Zend_Dojo_Form
{
    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
	    $this->setName("cat");
        $this->setMethod('post');
//		$this->setDecorators(array(
//		    'FormElements',
//			array('HtmlTag', array('tag' => 'table')),
//				'Form',
//		));

        $this->addElement('NumberTextBox', 'id', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 20)),
            ),
            'required'   => false,
            'label'      => 'Id',
			'readonly'	=> 'readonly',
        ));
/*
  		$this->id->setDecorators( array(

				'viewHelper',
				'Errors',
			    array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
				array('Label', array('tag' => 'td')),
				array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
		));
		
*/
	

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

        $this->addElement('TextBox', 'summary', array(
            'filters'    => array('StringTrim'),
            'validators' => array(  array('StringLength', false, array(0, 100))
			),
            'required'   => true,
            'label'      => 'Summary',
			'size'		 => '100',
        ));

        $this->addElement('Textarea', 'details', array(
            'required' => false,
            'label'    => 'Details',
			'style'    => 'width: 400px;height: 200px',
        ));
    }
/*
 *

	public function loadDefaultDecorators()
	{
		// -- wipe all
		$this->clearDecorators();

		// -- just add form elements
		// -- this is the default
		$this->setDecorators(array(
		   'FormElements',
			array('HtmlTag', array('tag' => 'dl')),
			'Form'
		));
	// -- form element decorators
		$this->setElementDecorators(array(
			"ViewHelper",
			array("Label"),
			array("HtmlTag", array(
				"tag"   => "div",
				"class" =>"element",
			)),
		));
		

		return $this;
	}
 *
 */
}
