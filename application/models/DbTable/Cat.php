<?php

class Application_Model_DbTable_Cat extends Zend_Db_Table_Abstract
{
    protected $_name = 'cat';
	protected $_dependentTables = array(
		'Application_Model_DbTable_Cc',
		'Application_Model_DbTable_Nc'
	);
	
}

