<?php
/**
 * Class BoiledEgg_FrontendFlush_Helper_Data
 */
class BoiledEgg_FrontendFlush_Helper_Data extends Mage_Core_Helper_Abstract {

	/**
	 * Build an array of md5 checksums based on the content of files
	 *
	 * @param array $files
	 *
	 * @return array
	 */
	public function contentChecksum(array $files)
	{
		$contentChecksums = array();
		foreach ($files as $file) {
			$contentChecksums[] = md5(file_get_contents($file));
		}
		return $contentChecksums;
	}
}