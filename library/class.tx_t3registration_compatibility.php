<?php


class tx_t3registration_compatibility  implements t3lib_Singleton {
	/**
	 * @var boolean
	 */
	protected $isVersion6 = FALSE;

	/**
	 * @var t3lib_l10n_parser_Llxml
	 */
	protected $llxmlParser;

	/**
	 * @return tx_ttnews_compatibility
	 */
	public static function getInstance() {
		return t3lib_div::makeInstance('tx_t3registration_compatibility');
	}

	/**
	 * Creates this object.
	 */
	public function __construct() {
		if (class_exists('t3lib_utility_VersionNumber')) {
			if (t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version) >= 6000000) {
				$this->isVersion6 = TRUE;
			}
		}
	}

	/**
	 * Returns an integer from a three part version number, eg '4.12.3' -> 4012003
	 *
	 * @param string $verNumberStr Version number on format x.x.x
	 * @return integer Integer version of version number (where each part can count to 999)
	 * @deprecated to be removed in TYPO3 6.1.0
	 */
	public function int_from_ver($verNumberStr) {
		if ($this->isVersion6) {
			return t3lib_utility_VersionNumber::convertVersionNumberToInteger($verNumberStr);
		} else {
			return t3lib_div::int_from_ver($verNumberStr);
		}
	}

	/**
	 * Includes a locallang-xml file and returns the $LOCAL_LANG array
	 * Works only when the frontend or backend has been initialized with a charset conversion object. See first code lines.
	 *
	 * @param string $fileRef Absolute reference to locallang-XML file
	 * @param string $langKey TYPO3 language key, eg. "dk" or "de" or "default"
	 * @param string $charset Character set (optional)
	 * @return array LOCAL_LANG array in return.
	 * @deprecated since TYPO3 4.6, will be removed in TYPO3 6.0 - use t3lib_l10n_parser_Llxml::getParsedData() from now on
	 */
	public function readLLXMLfile($fileRef, $langKey, $charset = '') {
		if ($this->isVersion6) {
			return $this->getLlxmlParser()->getParsedData($fileRef, $langKey, $charset);
		} else {
			return t3lib_div::readLLXMLfile($fileRef, $langKey, $charset);
		}
	}

	/**
	 * @return t3lib_l10n_parser_Llxml
	 */
	protected function getLlxmlParser() {
		if (!isset($this->llxmlParser)) {
			$this->llxmlParser = t3lib_div::makeInstance('t3lib_l10n_parser_Llxml');
		}
		return $this->llxmlParser;
	}

}
