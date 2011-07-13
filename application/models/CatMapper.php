<?php

class Application_Model_CatMapper
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
            $this->setDbTable('Application_Model_DbTable_Cat');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Cat $cat)
    {
        $data = array(
			'id' => $user->getId(),
			'initiatorid' =>$user->getInitiatorid(),
			'initdate' =>$user->getInitdate(),
			'focalid' => $user->getFocalid(),
			'qaid' =>$user->getQaid(),
			'statusid' =>$user->getStatusid(),
			'summary' =>$user->getSummary(),
            'details'   => $user->getDetails(),
			
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
        $user->setId($row->id)
                  ->setInitiatorid($row->initiatorid )
                  ->setInitdate($row->initdate )
                  ->setFocalid($row->focalid)
                  ->setQaid($row->qaid)
                  ->setStatusid($row->statusid)
                  ->setSummary($row->summary)
				  ->setDetails( $row->details );
		return $row->toArray();
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_User();
            $entry->setId($row->id)
					->setInitiatorid( $row->Initiatorid)
					->setInitdate( $row->Initdate)
					->setFocalid( $row->focalid)
					->setQaid( $row->qaid)
					->setStatusid( $row->statusid)
					->setSummary($row->summary)
					->setDetails($row->details);
            $entries[] = $entry;
        }
        return $entries;
    }

	public function delete( $id )
	{
		Zend_Debug::dump( $id );
        $this->getDbTable()->delete( 'id = '.$id );
	}

}

