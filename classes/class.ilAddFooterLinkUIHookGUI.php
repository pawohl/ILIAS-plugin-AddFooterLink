<?php
require_once('./Services/UIComponent/classes/class.ilUIHookPluginGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/AddFooterLink/classes/Link/class.footerLink.php');

class ilAddFooterLinkUIHookGUI extends ilUIHookPluginGUI {
	/**
	 * Specifies whether this Hook ran before during
	 * a request
	 * @var     bool
	 * @access  public
	 */
	static $ranBefore = false;

	/**
	 * Get html for a user interface area
	 *
	 * @param string $a_comp component
	 * @param string $a_part string that identifies the part of the UI that is handled
	 * @param string $a_par array of parameters (depend on $a_comp and $a_part)
	 *
	 * @return array Array with entries "mode" => modification mode, "html" => new HTML
	 */
	function getHTML($a_comp, $a_part, $a_par = array()) {
		global $DIC;

		if ( !self::$ranBefore
		  && $a_part === 'template_get'
		  && is_array( $a_par )
		  && $a_par['tpl_id'] === 'Services/UICore/tpl.footer.html' ) {
			// There should be no good reason to run this UI plugin more
			// than one time per request.
			// Below we are calling `$ftpl->get()` which would otherwise
			// cause (indirect) recursion.
			self::$ranBefore = true;

			// Language key for current user
			$langKey = $DIC->language()->getLangKey();
			// Links relevant to the language (ActiveRecordList)
			$links = footerLink::where([ 'lang' => $langKey ])->get();

			$pl = $this->getPluginObject();
			$this->plugin = new ilAddFooterLinkPlugin();

			if ( count( $links ) ) {
                        	// $ftpl is an instance of ilTemplate and is pre-configured
                        	// with the footer template. We checked that with `$a_par['tpl_id']`
                        	// few lines above.
                        	$ftpl = $a_par['tpl_obj'];
                        	// c.f. https://github.com/ILIAS-eLearning/ILIAS/blob/b5e252d75801c5c0d47c40e773e502eb78f136bf/Services/UICore/classes/class.ilTemplate.php#L916
                        	$ftpl->touchBlock('blank');
				$ftpl->clearCache = true;
				$ftpl->touchBlock('item_separator');
				$ftpl->setCurrentBlock('item_separator');
				$ftpl->parseCurrentBlock();
				$sepHtml = $ftpl->get('item_separator');
				$html = [];
				foreach ( $links as $link ) {
					$ftpl->setCurrentBlock('items');
					$ftpl->setVariable(
						'URL_ITEM',
						htmlspecialchars($link->getTarget()));
					$ftpl->setVariable(
						'TXT_ITEM',
						htmlspecialchars($link->getText()));
					$ftpl->parseCurrentBlock();
					$linkHtml = $ftpl->get('items');
					$html[] = $linkHtml;
				}
			}
			// Tell the Hook to append the footer link
			return [
				'mode' => count( $links ) ? ilUIHookPluginGUI::APPEND : ilUIHookPluginGUI::KEEP,
				'html' => count( $links ) ? ( $sepHtml . implode( $sepHtml, $html ) ) : '' ];
		}
	}
}

