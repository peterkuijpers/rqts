<?php
class Zend_View_Helper_GetCurrentCc extends Zend_View_Helper_Abstract
{
    public function getCurrentCc( )
    {
		$cat = new Zend_Session_Namespace('cat');
		if (isset($cat->id)) {
			$cc = new Application_Model_Cc();
			$ccMapper = new Application_Model_CcMapper();
			$ccMapper->find($cat->id, $cc );
			return $cc;
		} else {
			return;
		}

	}

}
?>