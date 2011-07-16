<?php

class Zend_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
	// use css styles
	// errorMsg , successMsg and generalMsg
    public function flashMessages()
    {
 //       $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages();
 //       $messages = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getCurrentMessages();
	
		$messages = array_merge(
			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages(),
			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getCurrentMessages()
		);

		Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->clearCurrentMessages();
//		 $this->_helper->flashMessenger->getCurrentMessages()

//		$this->_helper->flashMessenger->clearCurrentMessages();

        $output = '';

        if (!empty($messages)) {
            $output .= '<ul id="messages">';
            foreach ($messages as $message) {
                $output .= '<li class="' . key($message) . '">' . current($message) . '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }
}