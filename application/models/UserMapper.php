<?php

class Application_Model_UserMapper
{
	protected $_dbTable;

	public function setDbTable( $dbTable )
	{
		if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
	}

	public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_User');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Userbook $user)
    {
        $data = array(
			'nickname' =>$user->getNickname(),
			'firstname' =>$user->getFirstname(),
			'lastname' =>$user->getLastname(),
            'email'   => $user->getEmail(),
        );

        if (null === ($id = $user->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_User $user)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $user->setId($row->id)
                  ->setNickname($row->firstname)
                  ->setPassword($row->password)
                  ->setFirstname($row->firstname)
                  ->setLastname($row->lastname)
                  ->setEmail($row->email);
		return $row;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_User();
            $entry->setId($row->id)
					->setNickname( $row->nickname)
					->setPassword( $row->password)
					->setFirstname( $row->firstname)
					->setLastname( $row->lastname)
					->setEmail($row->email);
            $entries[] = $entry;
        }
        return $entries;
    }
}

