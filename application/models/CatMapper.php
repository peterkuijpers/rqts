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
			'id' => $cat->getId(),
			'initiatorid' =>$cat->getInitiatorid(),
			'initdate' => $cat->getInitdate(),
			'focalid' => $cat->getFocalid(),
			'qaid' =>$cat->getQaid(),
			'statusid' =>$cat->getStatusid(),
			'summary' =>$cat->getSummary(),
            'details'   => $cat->getDetails(),
			
        );
		$id = $cat->getId();
        if (null ===  $id ) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);

        } else {
			echo 'update';
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
		return $id;
    }

    public function find($id, Application_Model_Cat $cat)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $cat->setId($row->id)
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
            $entry = new Application_Model_Cat();
            $entry->setId($row->id)
					->setInitiatorid( $row->initiatorid)
					->setInitdate( $row->initdate)
					->setFocalid( $row->focalid)
					->setQaid( $row->qaid)
					->setStatusid( $row->statusid)
					->setSummary($row->summary)
					->setDetails($row->details);
            $entries[] = $entry;
        }
        return $entries;
    }

	public function fetchAllMy( $id ) {
		$str = 'initiatorid = '.$id.' or focalid='.$id;
		$resultSet = $this->getDbTable()->fetchAll( $str  );
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Cat();
            $entry->setId($row->id)
					->setInitiatorid( $row->initiatorid)
					->setInitdate( $row->initdate)
					->setFocalid( $row->focalid)
					->setQaid( $row->qaid)
					->setStatusid( $row->statusid)
					->setSummary($row->summary)
					->setDetails($row->details);
            $entries[] = $entry;
        }
        return $entries;


	}
	/*
	 * Count the number of initiated NC's for a cetain usesr
	 * @return integer
	 */
	public function countInitiators( $initiatorId )
	{
		$select = $this->getDbTable()->select();
		$select->from($this->getDbTable(),
              array('COUNT(id) as count'))
			->where('initiatorid = ?', $initiatorId);
		$row = $this->getDbTable()->fetchRow($select);
		return $row->count;
	}
	public function countOwners( $ownerId )
	{
		$select = $this->getDbTable()->select();
		$select->from($this->getDbTable(),
              array('COUNT(id) as count'))
			->where('focalid = ?', $ownerId);
		$row = $this->getDbTable()->fetchRow($select);
		return $row->count;
	}

	/*
	 * update the status for the nc
	 * @params nc id, new status id
	 */

	public function delete( $id )
	{
        $this->getDbTable()->delete( 'id = '.$id );
	}

}

