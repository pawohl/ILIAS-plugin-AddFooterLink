<?php
require_once('./Services/Table/classes/class.ilTable2GUI.php');
require_once('class.footerLink.php');
require_once('./Services/UIComponent/AdvancedSelectionList/classes/class.ilAdvancedSelectionListGUI.php');

/**
 * Class footerLinkTableGUI
 * @author Felix Pahlow <felix.pahlow@itz.uni-halle.de>
 **/


class footerLinkTableGUI extends ilTable2GUI {

	/**
	 * @var ilAddFooterLinkPlugin
	 */
	protected $pl;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;

	/**
	 * @param ilAddFooterLinkConfigGUI $a_parent_obj
	 * @param string $a_parent_cmd
	 */
	public function __construct(ilAddFooterLinkConfigGUI $a_parent_obj, $a_parent_cmd) {
		global $DIC;
		$this->ctrl = $DIC->ctrl();
		$this->pl = ilAddFooterLinkPlugin::getInstance();
		$this->setId('footer_links_table');
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->setRowTemplate('tpl.row.html', $this->pl->getDirectory());
		$this->setTitle($this->pl->txt('link_table_title'));
		$this->setFormAction($this->ctrl->getFormAction($this->parent_obj));
		//
		// Columns
		$this->addColumn($this->pl->txt('link_lang'));
		$this->addColumn($this->pl->txt('link_text'));
		$this->addColumn($this->pl->txt('link_target'));
		$this->addColumn($this->pl->txt('common_actions'));
		// ...
		$this->initData();
	}

	protected function initData() {
		$footerLinkList = footerLink::getCollection();
		$this->setData($footerLinkList->getArray());
	}

	protected function fillRow($a_set) {
		$footerLink = footerLink::find($a_set['id']);
		$this->tpl->setVariable('LANG', $footerLink->getLang());
		$this->tpl->setVariable('TEXT', $footerLink->getText());
		$this->tpl->setVariable('TARGET', $footerLink->getTarget());

		$this->ctrl->setParameter(
			$this->parent_obj,
			ilAddFooterLinkConfigGUI::ADDFOOTER_LINK_ID,
			$footerLink->getId());
		$actions = new ilAdvancedSelectionListGUI();
		$actions->setListTitle($this->pl->txt('common_actions'));
		$actions->setId('link_' . $footerLink->getId());
		$actions->addItem(
			$this->lng->txt('edit'),
			'',
			$this->ctrl->getLinkTarget(
				$this->parent_obj, ilAddFooterLinkConfigGUI::CMD_EDIT));
		$actions->addItem(
                        $this->lng->txt('delete'),
                        '',
                        $this->ctrl->getLinkTarget(
                                $this->parent_obj, ilAddFooterLinkConfigGUI::CMD_CONFIRM_DELETE));
		$this->tpl->setVariable('ACTIONS', $actions->getHTML());
	}
}

