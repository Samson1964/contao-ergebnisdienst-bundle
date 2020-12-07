<?php

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['ergebnisdienst'] = '{type_legend},type,headline;{ergebnisdienst_legend},ergebnisdienst_type;{protected_legend:hide},protected;{expert_legend:hide},guest,cssID,space;{invisible_legend:hide},invisible,start,stop';

/**
 * Felder
 */

// Adressenliste anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_type'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_type'],
	'exclude'                => true,
	'options'                => $GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_funktionen'],
	'inputType'              => 'select',
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => true,
		'submitOnChange'     => false
	),
	'sql'                    => "int(2) unsigned NOT NULL default '0'"
);


/*****************************************
 * Klasse tl_content_ergebnisdienst
 *****************************************/

class tl_content_ergebnisdienst extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

}
