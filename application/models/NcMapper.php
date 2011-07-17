<?php

class Application_Model_NcMapper
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
            $this->setDbTable('Application_Model_DbTable_Nc');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Nc $nc)
    {
        $data = array(
			'id' => $nc->getId(),
			'initiatorid' =>$nc->getInitiatorid(),
			'initdate'	=> $nc->getInitdate(),
			'focalid'	=> $nc->getFocalid(),
			'qaid'		=>$nc->getQaid(),
			'statusid'	=>$nc->getStatusid(),
			'summary'	=>$nc->getSummary(),
            'details'	=> $nc->getDetails(),

        );
		$id = $nc->getId();
        if (null ===  $id ) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
			echo 'update';
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Nc $nc)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $nc->setId($row->id)
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
            $entry = new Application_Model_Nc();
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

	public function approve( $id )
	{
		$this->getDbTable()->update( array('statusid' => 3), array('id = ?' => $id) );
	}

	public function delete( $id )
	{
        $this->getDbTable()->delete( 'id = '.$id );
	}

}


