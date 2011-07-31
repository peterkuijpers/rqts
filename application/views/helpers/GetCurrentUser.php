<?php
/* 
 * 
 */
class Zend_View_Helper_GetCurrentUser extends Zend_View_Helper_Abstract
{
	/*
	 * Get current user or return null if no current user
	 * Use this to check if anybody is logged in and who
	 * @return User or null
	 */
	public function getCurrentUser()
	{
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$id = $auth->getIdentity()->id;
			//get username
			$user = new Application_Model_User();
			$userMapper = new Application_Model_UserMapper();
			$userMapper->find( $id, $user);
			return $user;
		}
	}
}
?>
