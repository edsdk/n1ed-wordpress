<?php
/**
 * Plugin Name: N1ED
 * Plugin URI:  https://n1ed.com
 * Description: #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework
 * Version:     1.1.0
 * Author:      EdSDK
 * Author URI:  https://n1ed.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: n1ed
 * Domain Path: /languages
 */

/**
 * N1ED — #1 editor for your content. Create and edit in WYSIWYG style responsive content based on Bootstrap framework.
 * @encoding     UTF-8
 * @version      1.1.0
 * @license      GPLv2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @copyright    Copyright (c) 2019 EdSDK (https://n1ed.com/). All rights reserved.
 * @support      support@n1ed.zendesk.com
 **/

class ServletConfig implements IConfig {

    protected $m_conf;
    protected $m_testConf = [];

    public function __construct($m_conf) {
        $this->m_conf = $m_conf;
    }

    public function setTestConfig($testConf) {
        $this->m_testConf = (array)$testConf;
    }

    protected function getParameter($name, $defaultValue, $doAddTrailingSlash) {
        if (array_key_exists($name, $this->m_testConf))
            return $this->addTrailingSlash($this->m_testConf[$name], $doAddTrailingSlash);
        else {
            if (array_key_exists($name, $this->m_conf))
                return $this->addTrailingSlash($this->m_conf[$name], $doAddTrailingSlash);
			return $defaultValue;
		}
    }

    protected function addTrailingSlash($value, $doAddTrailingSlash) {
        if ($value != null && $doAddTrailingSlash && (strlen($value) == 0 || !substr($value, strlen($value)-1) == "/"))
            $value .= "/";
        return $value;
    }

    protected function getParameterStr($name, $defaultValue) {
        return $this->getParameter($name, $defaultValue, false);
    }

    protected function getParameterInt($name, $defaultValue) {
        $value = $this->getParameter($name, $defaultValue, false);
        if (is_int($value) !== false) {
            return $value;
        } else {
            error_log("Incorrect '" . $name . "' parameter integer value");
            return $defaultValue;
        }
    }

    protected function getParameterBool($name, $defaultValue) {
        $value = $this->getParameter($name, $defaultValue, false);
        if (is_bool($value) !== false) {
            return $value;
        } else {
            error_log("Incorrect '" . $name . "' parameter boolean value");
            return $defaultValue;
        }
    }

    public function getBaseDir() {
        $dir = $this->getParameter("dirFiles", ROOTPATH . "/files", true);
        if (!file_exists($dir))
            if (!mkdir($dir, 0777, true))
                throw new Exception("Unable to create files directory '" . $dir . "''");
        return UtilsPHP::normalizeNoEndSeparator($dir);
    }

    public function getTmpDir() {
        $dir = $this->getParameter("dirTmp", $this->getBaseDir() . "/tmp", true);
        if (!file_exists($dir))
            if (!mkdir($dir))
                throw new Exception("Unable to create temporary files directory '" . $dir . "''");
        return UtilsPHP::normalizeNoEndSeparator($dir);
    }

    public function getMaxUploadFileSize() {
        return $this->getParameterInt("maxUploadFileSize", 0);
    }

    public function getAllowedExtensions() {
        $value = $this->getParameterStr("allowedExtensions", null);
		if ($value === null)
            return [];
		$exts = explode(",", $value);
		for ($i=0; $i<count($exts); $i++)
			$exts[$i] = strtolower($exts[$i]);
		return $exts;
	}

	public function getJpegQuality() {
        return $this->getParameterInt("jpegQuality", 95);
    }

    public function getMaxImageResizeWidth() { return $this->getParameterInt("maxImageResizeWidth", 5000); }
    public function getMaxImageResizeHeight() { return $this->getParameterInt("maxImageResizeHeight", 5000); }
	public function getCrossDomainUrl() { return $this->getParameterStr("crossDomainUrl", null); }
	public function doKeepUploads() { return $this->getParameterBool("keepUploads", false); }

	public function isTestAllowed() { return $this->getParameterBool("isTestAllowed", false); }

    public function getRelocateFromHosts() {
        $hostsStr = $this->getParameterStr("relocateFromHosts", "");
		$hostsFound = explode(",",$hostsStr);
		$hosts = [];
		for ($i = count($hostsFound)-1; $i >= 0; $i--) {
		    $host = strtolower(trim($hostsFound[$i]));
		    if (strlen($host) > 0)
		        $hosts[] = $host;
        }
        return $hosts;
    }

}