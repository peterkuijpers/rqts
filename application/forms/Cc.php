<?php

class Application_Form_Cc extends Zend_Dojo_Form
{

    public function init()
    {
	    $this->setName("cc");
        $this->setMethod('post');

        $this->addElement('FilteringSelect', 'statusid', array(
            'required'  => true,
            'label'     => 'Status',
			'readonly'  =>'readonly',
        ));

		$this->addElement('FilteringSelect', 'assigneeid', array(
            'required'   => true,
            'label'      => 'Assign to QA',
			'readonly'	 => 'readonly',
        ));

        $this->addElement('hidden', 'id');

    }


}

