<?php

class AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
		$form = new Application_Form_Login();
		$this->view->form = $form;
    }

    public function getAction()
    {
		// action body
    }

    public function postAction()
    {
        // action body
		  // action body
		$request = $this->getRequest();
		$form = new Application_Form_Login();
		if ($form->isValid($request->getPost())) {
			if ($this->_process($form->getValues())) {
				echo 'logged-in';
				$this->_helper->redirector( 'index','index' );
			} else {
				echo 'failed';
			}

		}
		$this->view->form = $form;
    }

    protected function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['username']);
        $adapter->setCredential($values['password']);

        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);
        if ($result->isValid()) {
            $user = $adapter->getResultRowObject();
            $auth->getStorage()->write($user);
            return true;
        }
        return false;
    }

    protected function _getAuthAdapter()
    {

        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);

        $authAdapter->setTableName('user')
            ->setIdentityColumn('nickname')
            ->setCredentialColumn('password')
            ->setCredentialTreatment( 'md5( ? )');
//            ->setCredentialTreatment('SHA1(CONCAT(?,password_salt))');

        return $authAdapter;
    }

    public function logoutAction()
    {
        // action body
		Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('index', 'index'); // back to login page
    }


}







