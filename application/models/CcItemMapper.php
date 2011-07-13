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

    public function save(Application_Model_CcItem $ccitem)
    {
        $data = array(
			'id' => $ccitem->getId(),
			'ncid' =>$ccitem->getNcid(),
			'description' => $ccitem->getDescription(),
			'owner_id' =>$ccitem->getOwnerid(),
			'duedate' =>$ccitem->getDuedate(),
            'completiondate'   => $ccitem->getCompletiondate(),
        );
		//tweeked here
		$id = $ccitem->getId();
        if (null ===  $id ) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
			echo 'update';
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_CcItem $ccitem)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ccitem->setId($row->id)
                  ->setNcid($row->ncid)
                  ->setDescription($row->description )
                  ->setOwnerid($row->owner_id )
                  ->setDuedate($row->duedate )
                  ->setCompletiondate($row->completiondate);
		return $row->toArray();
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_CcItem();
            $entry->setId($row->id)
					->setNcid( $row->ncid)
					->setDescription( $row->description)
					->setOwnerid( $row->owner_id)
					->setDuedate( $row->duedate)
					->setCompletiondate($row->completiondate);
            $entries[] = $entry;
        }
        return $entries;
    }

	public function delete( $id )
	{
        $this->getDbTable()->delete( 'id = '.$id );
	}


}

