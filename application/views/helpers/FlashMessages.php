<?php

class Zend_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{
    public function flashMessages()
    {
	
		$messages = array_merge(
			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getMessages(),
			Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->getCurrentMessages()
		);

		Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger')->clearCurrentMessages();

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