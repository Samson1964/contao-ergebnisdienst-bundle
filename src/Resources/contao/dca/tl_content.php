<?php

/**
 * Globale Callbacks
 */
$GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('tl_content_ergebnisdienst', 'getErgebnisdienst');

/**
 * Paletten
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['ergebnisdienst'] = '{type_legend},type,headline;{ergebnisdienst_main_legend},ergebnisdienst_api,ergebnisdienst_saison,ergebnisdienst_liga;{ergebnisdienst_options_legend},ergebnisdienst_typ,ergebnisdienst_runde,ergebnisdienst_mannschaft;{protected_legend:hide},protected;{expert_legend:hide},guest,cssID;{invisible_legend:hide},invisible,start,stop';

/**
 * Felder
 */

// Saisons anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_api'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_api'],
	'exclude'                => true,
	'options'                => $GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_apis'],
	'inputType'              => 'select',
	//'save_callback'          => array('tl_content_ergebnisdienst', 'resetApis'),
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => false,
		'submitOnChange'     => true,
		'alwaysSave'         => true,
		'tl_class'           => 'long'
	),
	'sql'                    => "varchar(1) NOT NULL default ''"
);

// Saisons anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_saison'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_saison'],
	'exclude'                => true,
	'options_callback'       => array('tl_content_ergebnisdienst', 'getSaisons'),
	'inputType'              => 'select',
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => true,
		'submitOnChange'     => true,
		'alwaysSave'         => true,
		'tl_class'           => 'long'
	),
	'sql'                    => "varchar(4) NOT NULL default ''"
);

// Ligen anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_liga'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_liga'],
	'exclude'                => true,
	'options_callback'       => array('tl_content_ergebnisdienst', 'getLigen'),
	'inputType'              => 'select',
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => true,
		'submitOnChange'     => true,
		'alwaysSave'         => true,
		'tl_class'           => 'long'
	),
	'sql'                    => "varchar(4) NOT NULL default ''"
);

// Mannschaften anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_mannschaft'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_mannschaft'],
	'exclude'                => true,
	'options_callback'       => array('tl_content_ergebnisdienst', 'getMannschaften'),
	'inputType'              => 'select',
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => true,
		'submitOnChange'     => false,
		'tl_class'           => 'w50'
	),
	'sql'                    => "varchar(3) NOT NULL default ''"
);

// Typen anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_typ'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_typ'],
	'exclude'                => true,
	'options_callback'       => array('tl_content_ergebnisdienst', 'getTypen'),
	'inputType'              => 'select',
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => true,
		'submitOnChange'     => false,
		'alwaysSave'         => false,
		'tl_class'           => 'w50'
	),
	'sql'                    => "varchar(2) NOT NULL default ''"
);

// Runden anzeigen
$GLOBALS['TL_DCA']['tl_content']['fields']['ergebnisdienst_runde'] = array
(
	'label'                  => &$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_runde'],
	'exclude'                => true,
	'options_callback'       => array('tl_content_ergebnisdienst', 'getRunden'),
	'inputType'              => 'select',
	'eval'                   => array
	(
		'includeBlankOption' => true,
		'mandatory'          => false,
		'multiple'           => false,
		'chosen'             => true,
		'submitOnChange'     => false,
		'tl_class'           => 'w50'
	),
	'sql'                    => "varchar(3) NOT NULL default ''"
);

/*****************************************
 * Klasse tl_content_ergebnisdienst
 *****************************************/

class tl_content_ergebnisdienst extends \Backend
{

	var $saisons = array();
	var $ligen = array();
	var $mannschaften = array();
	var $runden = array();

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');

		$this->saisons = array(); // Saison-Array initialisieren

	}

	public function getErgebnisdienst(DataContainer $dc)
	{

		if(isset($dc->activeRecord->ergebnisdienst_api))
		{
			switch($dc->activeRecord->ergebnisdienst_api)
			{
				case '1':
					$base_url = 'https://ergebnisdienst.schachbund.de/json/';
					$apikey = $GLOBALS['TL_CONFIG']['ergebnisdienst_apikey_bl'];
					break;
				case '2':
					$base_url = 'https://dsol.schachbund.de/json/';
					$apikey = $GLOBALS['TL_CONFIG']['ergebnisdienst_apikey_dsol'];
					break;
			}

			$result = file_get_contents($base_url.'saison.php?i='.$apikey);
			$daten = json_decode($result);

			// Saison-Array füllen
			foreach($daten->Saison_Daten as $item)
			{
				$this->saisons[$item->Saison_ID] = $item->Saison_Name;
			}

			// Liga-Array und ggfs. Mannschaften-Array füllen
			if($dc->activeRecord->ergebnisdienst_saison)
			{
				// Ligen laden
				$result = file_get_contents($base_url.'ligen.php?i='.$apikey.'&s='.$dc->activeRecord->ergebnisdienst_saison);
				$daten = json_decode($result);
				if($daten->Ligen_Daten)
				{
					foreach($daten->Ligen_Daten as $item)
					{
						$this->ligen[$item->Liga_ID] = $item->Liga_Name;
					}
				}

				// Runden laden
				$result = file_get_contents($base_url.'termine.php?i='.$apikey.'&s='.$dc->activeRecord->ergebnisdienst_saison);
				$daten = json_decode($result);
				if(isset($daten->Termine_Daten))
				{
					foreach($daten->Termine_Daten as $item)
					{
						$this->runden[$item->Termin_Runde] = $item->Termin_Name;
					}
				}

				// Mannschaften laden, wenn Liga gewählt ist
				if($dc->activeRecord->ergebnisdienst_liga)
				{
					$result = file_get_contents($base_url.'mannschaften.php?i='.$apikey.'&s='.$dc->activeRecord->ergebnisdienst_saison.'&l='.$dc->activeRecord->ergebnisdienst_liga);
					$daten = json_decode($result);
					if($daten->Mannschaften_Daten)
					{
						foreach($daten->Mannschaften_Daten as $item)
						{
							$this->mannschaften[$item->Mannschaft_ID] = $item->Mannschaft_Name;
						}
					}
				}
			}

		}

		return;
	}

	public function getSaisons(DataContainer $dc)
	{
		static $Aufrufe = 0;
		//echo "getSaisons - Aufrufe $Aufrufe<br>";
		if(!$this->saisons) self::getErgebnisdienst($dc);
		return $this->saisons;
	}

	public function getLigen(DataContainer $dc)
	{
		return $this->ligen;
	}

	public function getMannschaften(DataContainer $dc)
	{
		return $this->mannschaften;
	}

	public function getRunden(DataContainer $dc)
	{
		return $this->runden;
	}

	public function getTypen(DataContainer $dc)
	{
		$optionen = array();
		if($dc->activeRecord->ergebnisdienst_liga)
		{
			$optionen = $GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_anzeigearten'];
		}
		return $optionen;
	}

	public function resetApis($varValue, DataContainer $dc)
	{
		$dc->activeRecord->ergebnisdienst_saison = '';
		$dc->activeRecord->ergebnisdienst_liga = '';
		// do somethig ...
		return $varValue;
	} 

}
