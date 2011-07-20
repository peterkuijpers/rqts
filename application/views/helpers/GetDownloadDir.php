<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Zend_View_Helper_GetDownloadDir extends Zend_View_Helper_Abstract
{
	public function getDownloadDir( $nc )
	{
		$rootDir = "c:/Users/Peter/rqts/";

		$ncDownloadDir = $rootDir . "nc_" . $nc->getId();
		// check whether dir already exists
		if ( ! is_dir( $ncDownloadDir) ) {
			mkdir(  $ncDownloadDir );
		}
		return $ncDownloadDir;
	}
}
?>
