<?php

class Application_Model_DbTable_CcItem extends Zend_Db_Table_Abstract
{

    protected $_name = 'cc_item';
	protected $_referenceMap    = array(
        'Owner' => array(
            'columns'           => 'ownerid',
            'refTableClass'     => 'Application_Model_DbTable_User',
            'refColumns'        => 'id'
        ),
        'Cc' => array(
            'columns'           => 'ccid',
            'refTableClass'     => 'Application_Model_DbTable_Cc',
            'refColumns'        => 'id'
        )
    );
}

