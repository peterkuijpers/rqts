<?php
class Zend_View_Helper_LoggedInAs extends Zend_View_Helper_Abstract
{
    public function loggedInAs ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $id = $auth->getIdentity()->id;
            $logoutUrl = $this->view->url(array('controller'=>'auth', 'action'=>'logout'), null, true);
			//get username
			$user = new Application_Model_User();
			$userMapper = new Application_Model_UserMapper();
			$userMapper->find( $id, $user);
			
            return 'Welcome ' . $user->getShortname() .  ' <a href="'.$logoutUrl.'">Logout</a>';
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if($controller == 'auth' && $action == 'index') {
            return '';
        }
        $loginUrl = $this->view->url(array('controller'=>'auth', 'action'=>'index'));
        return '<a href="'.$loginUrl.'">Login</a>';
    }
}
?>