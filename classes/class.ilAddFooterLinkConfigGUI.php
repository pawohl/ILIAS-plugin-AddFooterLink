<?php

require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Utilities/classes/class.ilConfirmationGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/AddFooterLink/classes/Link/class.footerLinkFormGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/AddFooterLink/classes/Link/class.footerLink.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/AddFooterLink/classes/Link/class.footerLinkTableGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/AddFooterLink/classes/class.ilAddFooterLinkPlugin.php');


/**
 * Class ilAddFooterLinkConfigGUI
 *
 * @author  Felix Pahlow <felix.pahlow@itz.uni-halle.de>
 */

class ilAddFooterLinkConfigGUI extends \ilPluginConfigGUI {

	const ADDFOOTER_LINK_ID = 'addfooter_link_id';
	const CMD_ADD = 'add';
	const CMD_CANCEL = 'cancel';
	const CMD_CONFIGURE = 'configure';
	const CMD_EDIT = 'edit';
	const CMD_CONFIRM_DELETE = 'confirmDelete';
	const CMD_DELETE = 'delete';
	const CMD_SAVE = 'save';
	const CMD_UPDATE = 'update';
	const CMD_UPDATE_AND_STAY = 'updateAndStay';

	/**
	 * @var footerLink
	 */
	protected $footerLink;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilToolbarGUI
	 */
	protected $toolbar;
	/**
	 * @var ilLanguage
	 */
	protected $lng;
	/**
	 * @var ilTemplate
	 */
	protected $tpl;
	/**
	 * @var ilAddFooterLinkPlugin
	 */
	protected $pl;
	
	public function __construct() { 
		global $DIC;
		/**
		 * @var $tpl    ilTemplate
		 * @var $ctrl ilCtrl
		 */
		$this->ctrl = $DIC->ctrl();
		$this->toolbar = $DIC->toolbar();
		$this->lng = $DIC->language();
		$this->tpl = $DIC->ui()->mainTemplate();
		$this->pl = ilAddFooterLinkPlugin::getInstance();
		if (!$this->pl->isActive()) {
			$this->ctrl->redirectByClass('');
		}
		$this->ctrl->setParameter(
			$this,
			self::ADDFOOTER_LINK_ID,
			$_REQUEST[self::ADDFOOTER_LINK_ID]);
		$this->footerLink = footerLink::find(
			$_GET[self::ADDFOOTER_LINK_ID]);
	}

	public function performCommand($cmd) {
		switch ($cmd) {
			case self::CMD_CONFIGURE:
			case self::CMD_CONFIRM_DELETE:
			case self::CMD_SAVE:
			case self::CMD_UPDATE:
			case self::CMD_UPDATE_AND_STAY:
			case self::CMD_ADD:
			case self::CMD_EDIT:
			case self::CMD_CANCEL:
			case self::CMD_DELETE:
				$this->{$cmd}();
				break;
		}
	}

	public function configure() {
		$button = ilLinkButton::getInstance();
		$button->setCaption($this->pl->txt('common_add_link'), false);
		$button->setUrl($this->ctrl->getLinkTarget($this, self::CMD_ADD));
		$this->toolbar->addButtonInstance($button);
		$tableGUI = new footerLinkTableGUI($this, self::CMD_CONFIGURE);
		$this->tpl->setContent($tableGUI->getHTML());
	}

	protected function add() {
		$formGUI = new footerLinkFormGUI($this, new footerLink());
		$this->tpl->setContent($formGUI->getHTML());
	}


	protected function save() {
		$formGUI = new footerLinkFormGUI($this, new footerLink());
		$formGUI->setValuesByPost();
		if ($formGUI->saveObject()) {
			ilUtil::sendInfo($this->pl->txt('link_success'), true);
			$this->ctrl->redirect($this, self::CMD_CONFIGURE);
		}
		$this->tpl->setContent($formGUI->getHTML());
	}


	protected function cancel() {
		$this->ctrl->setParameter($this, self::ADDFOOTER_LINK_ID, NULL);
		$this->ctrl->redirect($this, self::CMD_CONFIGURE);
	}


	protected function edit() {
		$formGUI = new footerLinkFormGUI($this, $this->footerLink);
		$formGUI->fillForm();
		$this->tpl->setContent($formGUI->getHTML());
	}


	protected function update() {
		$formGUI = new footerLinkFormGUI($this, $this->footerLink);
		$formGUI->setValuesByPost();
		if ($formGUI->saveObject()) {
			ilUtil::sendInfo($this->pl->txt('link_success'), true);
			$this->ctrl->redirect($this, self::CMD_CONFIGURE);
		}
		$this->tpl->setContent($formGUI->getHTML());
	}


	protected function updateAndStay() {
		$formGUI = new footerLinkFormGUI($this, $this->footerLink);
		$formGUI->setValuesByPost();
		if ($formGUI->saveObject()) {
			ilUtil::sendInfo($this->pl->txt('link_success'));
		}
		$this->tpl->setContent($formGUI->getHTML());
	}


	protected function confirmDelete() {
		$ilConfirmationGUI = new ilConfirmationGUI();
		$ilConfirmationGUI->setFormAction($this->ctrl->getFormAction($this));
		$ilConfirmationGUI->addItem(
			self::ADDFOOTER_LINK_ID,
			$this->footerLink->getId(),
			$this->footerLink->getText());
		$ilConfirmationGUI->setCancel($this->pl->txt('form_button_cancel'), self::CMD_CANCEL);
		$ilConfirmationGUI->setConfirm($this->pl->txt('form_button_delete'), self::CMD_DELETE);

		$this->tpl->setContent($ilConfirmationGUI->getHTML());
	}


	protected function delete() {
		$this->footerLink->delete();
		ilUtil::sendInfo($this->pl->txt('link_success'), true);
		$this->cancel();
	}
}

