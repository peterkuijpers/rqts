<?php

class Application_Model_DbTable_Nc extends Zend_Db_Table_Abstract
{

    protected $_name = 'nc';

	protected $_referenceMap    = array(
        'Initiator' => array(
            'columns'           => 'initiatorid',
            'refTableClass'     => 'Application_Model_DbTable_User',
            'refColumns'        => 'id'
        ),
        'Focal' => array(
            'columns'           => 'focalid',
            'refTableClass'     => 'Application_Model_DbTable_User',
            'refColumns'        => 'id'
        ),
        'Qa' => array(
            'columns'           => 'qaid',
            'refTableClass'     => 'Application_Model_DbTable_User',
            'refColumns'        => 'id'
        )
    );


}

