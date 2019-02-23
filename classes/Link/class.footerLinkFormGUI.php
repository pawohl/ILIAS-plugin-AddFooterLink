<?php
require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Form/classes/class.ilMultiSelectInputGUI.php');

/**
 * Class footerLinkFormGUI
 *
 * @author  Felix Pahlow <felix.pahlow@itz.uni-halle.de>
 */
class footerLinkFormGUI extends ilPropertyFormGUI {

	
	const F_TEXT = 'text';
	const F_TARGET = 'target';
	const F_LANG = 'lang';

	/**
	 * @var footerLink
	 */
	protected $footerLink;
	/**
	 * @var ilAddFooterLinkPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var bool
	 */
	protected $is_new;
        /**
         * @var ilLanguage
         */
        protected $lng;

	/**
	 * @param            $parent_gui
	 * @param footerLink $footerLink
	 */
	public function __construct($parent_gui, footerLink $footerLink) {
		parent::__construct();
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->lng = $DIC->language();
		$this->footerLink = $footerLink;
		$this->pl = ilAddFooterLinkPlugin::getInstance();
		$this->is_new = $footerLink->getId() == 0;
		$this->setFormAction($this->ctrl->getFormAction($parent_gui));
		$this->initForm();
	}

	/**
	 * @param $var
	 *
	 * @return string
	 */
	protected function txt($var) {
		return $this->pl->txt('link_' . $var);
	}


	/**
	 * @param $var
	 *
	 * @return string
	 */
	protected function infoTxt($var) {
		return $this->pl->txt('link_' . $var . '_info');
	}

	public function initForm() {
		$this->setTitle($this->txt('form_title'));

		$text = new ilTextInputGUI($this->txt(self::F_TEXT), self::F_TEXT);
		$text->setSize(15);
		$text->setMaxLength(50);
		$text->setRequired(true);
		$this->addItem($text);

                $target = new ilTextInputGUI($this->txt(self::F_TARGET), self::F_TARGET);
                $target->setSize(250);
                $target->setMaxLength(250);
                $target->setRequired('required');
                $this->addItem($target);

		$lang = new ilSelectInputGUI($this->txt(self::F_LANG), self::F_LANG);
		$installedLangs = $this->lng->getInstalledLanguages();
		$this->lng->loadLanguageModule('meta');
		$opts = [];
		foreach ($installedLangs as $l) {
			$opts[$l] = $this->lng->txt('meta_l_' . $l);
		}
		$lang->setOptions($opts);
		$lang->setRequired('required');
		$this->addItem($lang);

		$this->addButtons();
	}

	public function fillForm() {
		$values = [
			self::F_TEXT => $this->footerLink->getText(),
			self::F_TARGET => $this->footerLink->getTarget(),
			self::F_LANG => $this->footerLink->getLang(),
		];
		$this->setValuesByArray($values);
	}

	/**
	 * @return bool
	 */
	protected function fillObject() {
		if (!$this->checkInput()) {
			return false;
		}
		$this->footerLink->setText($this->getInput(self::F_TEXT));
		$this->footerLink->setTarget($this->getInput(self::F_TARGET));
		$this->footerLink->setLang($this->getInput(self::F_LANG));
		return true;
	}



	/**
	 * @return bool false when unsuccessful or int request_id when successful
	 */
	public function saveObject() {
		if (!$this->fillObject()) {
			return false;
		}
		if ($this->footerLink->getId() > 0) {
			$this->footerLink->update();
		} else {
			$this->footerLink->create();
		}

		return $this->footerLink->getId();
	}



	protected function addButtons() {
		if ($this->is_new) {
			$this->addCommandButton(
				ilAddFooterLinkConfigGUI::CMD_SAVE,
				$this->txt('form_button_' . ilAddFooterLinkConfigGUI::CMD_SAVE));
		} else {
			$this->addCommandButton(
				ilAddFooterLinkConfigGUI::CMD_UPDATE,
				$this->txt('form_button_' . ilAddFooterLinkConfigGUI::CMD_UPDATE));
			$this->addCommandButton(
				ilAddFooterLinkConfigGUI::CMD_UPDATE_AND_STAY,
				$this->txt('form_button_' . ilAddFooterLinkConfigGUI::CMD_UPDATE_AND_STAY));
		}
		$this->addCommandButton(
			ilAddFooterLinkConfigGUI::CMD_CANCEL,
			$this->txt('form_button_' . ilAddFooterLinkConfigGUI::CMD_CANCEL));
	}
}

