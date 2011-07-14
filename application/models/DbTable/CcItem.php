<?php

class Application_Model_DbTable_CcItem extends Zend_Db_Table_Abstract
{

    protected $_name = 'cc_item';
	protected $_referenceMap    = array(
        'Owner' => array(
            'columns'           => 'ownerid',
            'refTableClass'     => 'User',
            'refColumns'        => 'id'
        ),
        'Nc' => array(
            'columns'           => 'ncid',
            'refTableClass'     => 'Cat',
            'refColumns'        => 'id'
        )
    );
}

