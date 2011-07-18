<?php

class Application_Model_CcMapper
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
            $this->setDbTable('Application_Model_DbTable_Cc');
        }
        return $this->_dbTable;
    }
	/**
	 * Save an Cc
	 * If id = null then insert new item in  db
	 * If id = value then update item in db
	 * @param Application_Model_Cc $cc
	 */
/**
    public function save(Application_Model_Cc $cc)
    {
        $data = array(
			'id' => $cc->getId(),
			'assigneeid'	=> $cc->getAssigneeid(),
			'approvaldate'	=> $cc->getApprovaldate(),
			'verifierid'	=> $cc->getVerifierid(),
			'verifydate'		=> $cc->getVerifydate(),
			'verifystatusid'	=> $cc->getverifystatusid(),
            'verifycomments'	=> $cc->getVerifycomments(),
        );
		$id = $cc->getId();
        if (null ===  $id ) {
            unset($data['id']);
			$this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
**/
	/**
	 * Insert data into database. id is included in $cc
	 * No autoincrease because cc is linked to nc
	 * @param Application_Model_Cc $cc 
	 */
    public function insert(Application_Model_Cc $cc)
    {
        $data = array(
			'id' => $cc->getId(),
			'assigneeid'	=> $cc->getAssigneeid(),
			'approvaldate'	=> $cc->getApprovaldate(),
			'verifierid'	=> $cc->getVerifierid(),
			'verifydate'		=> $cc->getVerifydate(),
			'verifystatusid'	=> $cc->getverifystatusid(),
            'verifycomments'	=> $cc->getVerifycomments(),
        );
		$this->getDbTable()->insert($data);
    }
	
	/**
	 * Update $cc
	 */
    public function update(Application_Model_Cc $cc)
    {
        $data = array(
			'id' => $cc->getId(),
			'assigneeid'	=> $cc->getAssigneeid(),
			'approvaldate'	=> $cc->getApprovaldate(),
			'verifierid'	=> $cc->getVerifierid(),
			'verifydate'		=> $cc->getVerifydate(),
			'verifystatusid'	=> $cc->getverifystatusid(),
            'verifycomments'	=> $cc->getVerifycomments(),
        );
		$id = $cc->getId();
        $this->getDbTable()->update($data, array('id = ?' => $id));
    }

	/**
	 * Find a single item in the db identified by $id
	 * Updates $cc parameter and returns array of field-values
	 * @param <type> $id
	 * @param Application_Model_Cc $cc
	 * @return <type>
	 */
    public function find($id, Application_Model_Cc $ccitem)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $ccitem->setId($row->id)
  					->setAssigneeid( $row->assigneeid)
					->setApprovaldate( $row->approvaldate)
					->setVerifierid( $row->verifierid)
					->setVerifystatusid( $row->verifystatusid)
					->setVerifycomments($row->verifycomments);
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
					->setAssigneeid( $row->assigneeid)
					->setApprovaldate( $row->approvaldate)
					->setVerifierid( $row->verifierid)
					->setVerifystatusid( $row->verifystatusid)
					->setVerifycomments($row->verifycomments);
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

}

