<?php
class Zend_View_Helper_GetStatusDescription extends Zend_View_Helper_Abstract
{
	public function getStatusDescription( $id )
	{
		$arr = array( '1' => 'NC_Draft', '2' => 'NC_Submitted', '3'=>'NC_Approved', '4'=>'CC_Submitted' );
		return $arr[ $id ];
	}
}
?>