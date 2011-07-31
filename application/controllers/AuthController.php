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
		$request = $this->getRequest();
		$form = new Application_Form_Login();
		if ($form->isValid($request->getPost())) {
			if ($this->_process($form->getValues())) {
				// logged in
				$this->_helper->flashMessenger->addMessage(array('successMsg'=>'Logged in successfully'));
				$this->_helper->redirector( 'index','index' );
			} else {
				$this->_helper->flashMessenger->addMessage(array('errorMsg'=>'Failed to login'));
			}
		}
    }

    public function getAction()
    {
		// action body
    }


    public function logoutAction()
    {
        // action body
		Zend_Auth::getInstance()->clearIdentity();
		//
		//unregister cat
		$nc = new Zend_Session_Namespace('cat');
		unset( $nc->id);
		//clear my-nc's filter
		$filter = new Zend_Session_Namespace('filter');
		$filter->type = "AllNc";


	//	unset( $nc );
	//	echo "logging out the cat/nc";
		//
        $this->_helper->redirector('index', 'index'); // back to login page
    }
   

    protected function _process($values)
    {
        // Get our authentication adapter and check credentials
        $adapter = $this->_getAuthAdapter();
        $adapter->setIdentity($values['nickname']);
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
        return $authAdapter;
    }
}
