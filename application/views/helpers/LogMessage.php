<?php
class Zend_View_Helper_LogMessage extends Zend_View_Helper_Abstract
{
	public function logMessage( $message )
	{
		$logger = $this->view->logger;
		$logger->setEventItem('ncid',  $message['ncid'] );
		$logger->setEventItem('user',  $message['user'] );
		$logger->setEventItem('action',  $message['action'] );
		$logger->log( $message['message'], 1);
	}
}
?>
