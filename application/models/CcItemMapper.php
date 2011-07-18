<?php

class Application_Model_CcItemMapper
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
            $this->setDbTable('Application_Model_DbTable_CcItem');
        }
        return $this->_dbTable;
    }
	/**
	 * Save an CcItem
	 * If id = null then insert new item in  db
	 * If id = value then update item in db
	 * @param Application_Model_CcItem $ccitem
	 */
    public function save(Application_Model_CcItem $ccitem)
    {
        $data = array(
			'id' => $ccitem->getId(),
			'ccid' =>$ccitem->getCcid(),
			'description'	=> $ccitem->getDescription(),
			'ownerid'		=> $ccitem->getOwnerid(),
			'duedate'		=> $ccitem->getDuedate(),
            'completiondate'=> $ccitem->getCompletiondate(),
        );
		$id = $ccitem->getId();
        if (null ===  $id ) {
            unset($data['id']);
			$this->getDbTable()->insert($data);
        } else {
			echo 'update';
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
	/**
	 * Find a single item in the db identified by $id
	 * Updates $ccitem parameter and returns array of field-values
	 * @param <type> $id
	 * @param Application_Model_CcItem $ccitem
	 * @return <type>
	 */
    public function find($id, Application_Model_CcItem $ccitem)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ccitem->setId($row->id)
                  ->setCcid($row->ccid)
                  ->setDescription($row->description )
                  ->setOwnerid($row->ownerid )
                  ->setDuedate($row->duedate )
                  ->setCompletiondate($row->completiondate);
		return $row->toArray();
    }
	/**
	 * Fetching all CcItems
	 * @return Application_Model_CcItem
	 */
    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_CcItem();
            $entry->setId($row->id)
					->setCcid( $row->ccid)
					->setDescription( $row->description)
					->setOwnerid( $row->ownerid)
					->setDuedate( $row->duedate)
					->setCompletiondate($row->completiondate);
            $entries[] = $entry;
        }
        return $entries;
    }
	/**
	 * Delete an ccitem identified by $id
	 * @param <type> $id
	 */
	public function delete( $id )
	{
        $this->getDbTable()->delete( 'id = '.$id );
	}

	/**
	 * Fetch all items that belong to the nc indentified by ncid
	 * @param <type> $ncid 
	 * @return <type> array of CcItems
	 */
	public function fetchAllItemsForCc( $ccid )
	{
		// first get cat/nc
		$cc = new Application_Model_Cc( );
		$ccMapper = new Application_Model_CcMapper();
		$result = $ccMapper->getDbTable()->find($ccid);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();

		$rowset = $row->findDependentRowset( 'Application_Model_DbTable_CcItem' );
		$entries   = array();
        foreach ($rowset as $row) {
            $entry = new Application_Model_CcItem();
            $entry->setId($row->id)
					->setCcid( $row->ccid)
					->setDescription( $row->description)
					->setOwnerid( $row->ownerid)
					->setDuedate( $row->duedate)
					->setCompletiondate($row->completiondate);
            $entries[] = $entry;
        }
        return $entries;
	}
}

