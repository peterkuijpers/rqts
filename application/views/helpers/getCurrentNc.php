<?php
class Zend_View_Helper_GetCurrentNc extends Zend_View_Helper_Abstract
{
    public function getCurrentNc( )
    {
		$cat = new Zend_Session_Namespace('cat');
		if (isset($cat->id)) {
			$nc = new Application_Model_Cat();
			$ncMapper = new Application_Model_CatMapper();
			$ncMapper->find($cat->id, $nc);
			return $nc;
		} else {
			echo 'no id';
			return;
		}

	}
}
?>