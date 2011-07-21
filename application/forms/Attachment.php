<?php

class Application_Form_Attachment extends Zend_Form
{

    public function init()
    {
           /* Form Elements & Other Definitions Here ... */
	    $this->setName("attachment");
        $this->setMethod('post');

        $this->addElement('file', 'filename', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 20)),
            ),
            'required'   => true,
            'label'      => 'Attachement name',
        ));

        $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Submit',
        ));

    
    }


}

