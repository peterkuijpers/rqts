<?php

class Application_Model_DbTable_Cat extends Zend_Db_Table_Abstract
{

    protected $_name = 'nc';
	protected $_dependentTables = 'CcItem';
	protected $_referenceMap    = array(
        'Initiator' => array(
            'columns'           => 'initiatorid',
            'refTableClass'     => 'User',
            'refColumns'        => 'id'
        ),
        'Focal' => array(
            'columns'           => 'focalid',
            'refTableClass'     => 'User',
            'refColumns'        => 'id'
        ),
        'Qa' => array(
            'columns'           => 'qaid',
            'refTableClass'     => 'User',
            'refColumns'        => 'id'
        )
    );

}

