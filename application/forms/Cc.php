<?php

class Application_Form_Cc extends Zend_Form
{

    public function init()
    {
	    $this->setName("cc");
        $this->setMethod('post');

		$this->addElement('select', 'assigneeid', array(
            'required'   => true,
            'label'      => 'Assign to QA',
        ));

        $this->addElement('hidden', 'id');

    }


}

