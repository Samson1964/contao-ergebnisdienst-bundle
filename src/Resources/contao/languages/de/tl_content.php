<?php 

$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_main_legend'] = 'Schnittstelle, Saison und Liga';
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_api'] = array('Schritt 1: Schnittstelle wählen', 'Bitte wählen Sie eine Schnittstelle aus. Die API-Schlüssel für die Schnittstellen müssen Sie in den Systemeinstellungen eintragen.');
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_saison'] = array('Schritt 2: Saison wählen', 'Bitte wählen Sie eine Saison aus.');
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_liga'] = array('Schritt 3: Liga wählen', 'Bitte wählen Sie eine Liga aus.');

$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_options_legend'] = 'Anzeigeart und Mannschaft';
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_typ'] = array('Anzeige', 'Bitte wählen Sie die Anzeige aus.');
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_runde'] = array('Runde (optional)', 'Bitte wählen Sie die Runde aus.');
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_mannschaft'] = array('Mannschaft (optional)', 'Bitte wählen Sie eine Mannschaft aus (optional).');

// Mögliche APIs festlegen
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_apis'] = array();
if($GLOBALS['TL_CONFIG']['ergebnisdienst_apikey_bl']) $GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_apis']['1'] = 'Bundesliga';
if($GLOBALS['TL_CONFIG']['ergebnisdienst_apikey_dsol']) $GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_apis']['2'] = 'Deutsche Schach-Online-Liga';

// Anzeigearten festlegen
$GLOBALS['TL_LANG']['tl_content']['ergebnisdienst_anzeigearten'] = array
(
	'1'  => 'Mannschaften (ohne Aufstellung)',
	'11' => 'Mannschaften (mit Aufstellung an Brett 1-4)',
	'12' => 'Mannschaften (mit Aufstellung an Brett 1-8)',
	'13' => 'Mannschaften (mit kompletter Aufstellung)',
	'2'  => 'Termine',
	'3'  => 'Paarungen/Ergebnisse',
	'4'  => 'Tabelle (Rangliste)',
	'41' => 'Tabelle (Kreuztabelle)'
);
