<?php

class Application_Model_DbTable_Cc extends Zend_Db_Table_Abstract
{

    protected $_name = 'cc';
	protected $_dependentTables = array( 'Application_Model_DbTable_CcItem' );



}

