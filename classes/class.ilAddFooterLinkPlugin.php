<?php
include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");


/**
 * Class ilAddFooterLinkPlugin
 *
 * @author  Felix Pahlow <felix.pahlow@itz.uni-halle.de>
 * @version $Id$
 *
 */

class ilAddFooterLinkPlugin extends ilUserInterfaceHookPlugin {

	const PLUGIN_ID = 'addfooterlink';
	const PLUGIN_NAME = 'AddFooterLink';

	/**
	 * @var ilAddFooterLinkPlugin
	 */
	protected static $instance;

	function getPluginName() {
		return self::PLUGIN_NAME;
	}


	/**
	 * @return ilAddFooterLinkPlugin
	 */
	public static function getInstance() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

