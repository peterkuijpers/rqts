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

    public function save(Application_Model_User $user)
    {
        $data = array(
			'nickname' =>$user->getNickname(),
			'password' => $user->getPassword(),
			'firstname' =>$user->getFirstname(),
			'lastname' =>$user->getLastname(),
            'email'   => $user->getEmail(),
			'adminlevel' => $user->getAdminLevel(),
			'id' => $user->getId(),
        );
		$id = $user->getId();
        if (null ===  $id ) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
			echo 'update';
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
		$shortname = substr( $row->firstname,0,1)." ".$row->lastname;
        $user->setId($row->id)
                  ->setNickname($row->nickname)
                  ->setPassword($row->password)
                  ->setFirstname($row->firstname)
                  ->setLastname($row->lastname)
                  ->setEmail($row->email)
				  ->setAdminlevel($row->adminlevel)
				  ->setShortname( $shortname);

		$rowArr = $row->toArray();
		$rowArr['shortname'] = $shortname;
		return $rowArr;
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
					->setEmail($row->email)
					->setAdminlevel($row->adminlevel)
					->setShortname( substr( $row->firstname,0,1)." ".$row->lastname);
            $entries[] = $entry;
        }
        return $entries;
    }

	/**
	 * Fetch a list of all users shortnames
	 * Mainly used in pulldown menus
	 * @return array 
	 */

	public function fetchAllShortnames()
	{
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
			$entries[ $row->id ] = substr( $row->firstname,0,1)." ".$row->lastname;
		}
		return $entries;
	}
	public function delete( $id )
	{
//		$where = $this->getDbTable()->getAdapter()->quoteInto('bug_id = ?', $id);
        $this->getDbTable()->delete( 'id = '.$id );
	}
	public function getIdForNickname( $nickname )
	{
		$str = 'nickname = "'.$nickname.'"';
        $row = $this->getDbTable()->fetchRow($str);
     	return $row->id;
	}
}

