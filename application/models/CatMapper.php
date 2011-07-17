<?php


/**
 * CatMapper
 * Mapping of database functions to Cat class/object
 */
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
			'statusid' =>$cat->getStatusid()
        );
		$id = $cat->getId();
        if (null ===  $id ) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
			echo 'update';
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Cat $cat)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $cat->setId($row->id)
                  ->setStatusid($row->statusid);
		return $row->toArray();
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Cat();
            $entry->setId($row->id)
					->setStatusid( $row->statusid);
            $entries[] = $entry;
        }
        return $entries;
    }

	public function delete( $id )
	{
        $this->getDbTable()->delete( 'id = '.$id );
	}

}

