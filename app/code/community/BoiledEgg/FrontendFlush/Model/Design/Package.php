<?php
/**
 * Class BoiledEgg_FrontendFlush_Model_Design_Package
 */
class BoiledEgg_FrontendFlush_Model_Design_Package extends Mage_Core_Model_Design_Package
{
	/**
	 * Merge specified javascript files and return URL to the merged file on success
	 *
	 * @param $files
	 * @return string
	 */
	public function getMergedJsUrl($files)
	{
		$checksums = Mage::helper('frontend_flush')->contentChecksum($files);
		$targetFilename = md5(implode(',', $checksums)) . '.js';
		$targetDir = $this->_initMergerDir('js');
		if (!$targetDir) {
			return '';
		}
		if ($this->_mergeFiles($files, $targetDir . DS . $targetFilename, false, null, 'js')) {
			return Mage::getBaseUrl('media', Mage::app()->getRequest()->isSecure()) . 'js/' . $targetFilename;
		}
		return '';
	}

	/**
	 * Merge specified css files and return URL to the merged file on success
	 *
	 * @param $files
	 * @return string
	 */
	public function getMergedCssUrl($files)
	{
		// secure or unsecure
		$isSecure = Mage::app()->getRequest()->isSecure();
		$mergerDir = $isSecure ? 'css_secure' : 'css';
		$targetDir = $this->_initMergerDir($mergerDir);
		if (!$targetDir) {
			return '';
		}

		// base hostname & port
		$baseMediaUrl = Mage::getBaseUrl('media', $isSecure);
		$hostname = parse_url($baseMediaUrl, PHP_URL_HOST);
		$port = parse_url($baseMediaUrl, PHP_URL_PORT);
		if (false === $port) {
			$port = $isSecure ? 443 : 80;
		}

		// merge into target file
		$checksums = Mage::helper('frontend_flush')->contentChecksum($files);
		$targetFilename = md5(implode(',', $checksums) . "|{$hostname}|{$port}") . '.css';
		$mergeFilesResult = $this->_mergeFiles(
			$files, $targetDir . DS . $targetFilename,
			false,
			array($this, 'beforeMergeCss'),
			'css'
		);
		if ($mergeFilesResult) {
			return $baseMediaUrl . $mergerDir . '/' . $targetFilename;
		}
		return '';
	}
}