<?php
require_once('./Services/ActiveRecord/class.ActiveRecord.php');


/**
 * Class footerLink
 *
 * @author Felix Pahlow <felix.pahlow@itz.uni-halle.de>
 * @version 1.0.0
 */

class footerLink extends ActiveRecord {

	const TABLE_NAME = 'addfooterlink_links';

	/**
	 * @return string
	 * @description Return the Name of your Database Table
	 * @deprecated
	 */
	static function returnDbTableName() {
		return self::TABLE_NAME;
	}


	/**
	 * @return string
	 */
	public function getConnectorContainerName() {
		return self::TABLE_NAME;
	}


	/**
	 * @var int
	 *
	 * @con_is_primary true
	 * @con_sequence   true
	 * @con_is_unique  true
	 * @con_has_field  true
	 * @con_fieldtype  integer
	 * @con_length     8
	 */
	protected $id;
	/**
	 * @var string
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     200
	 */
	protected $text = '';
	/**
	 * @var string
	 *
	 * @con_has_field  true
	 * @con_fieldtype  text
	 * @con_length     255
	 */
	protected $target = '';
        /**
         * @var string
         *
         * @con_has_field  true
         * @con_fieldtype  text
         * @con_length     10
         */
        protected $lang = '';

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}
	/**
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}
	/**
         * @return string
         */
        public function getTarget() {
		return $this->target;
	}
	/**
         * @param string $target
         */
        public function setTarget($target) {
                $this->target = $target;
        }
        /**
         * @return string
         */
        public function getLang() {
                return $this->lang;
        }
        /**
         * @param string $lang
         */
        public function setLang($lang) {
                $this->lang = $lang;
        }


}

