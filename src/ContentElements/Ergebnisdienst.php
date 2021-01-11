<?php

namespace Schachbulle\ContaoErgebnisdienstBundle\ContentElements;

class Ergebnisdienst extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_ergebnisdienst_default';

	/**
	 * Generate the module
	 */
	protected function compile()
	{
	
		$api_id = $GLOBALS['TL_CONFIG']['ergebnisdienst_api_id']; // API-Schlüssel aus den Einstellungen zuweisen

		if($this->ergebnisdienst_api && $this->ergebnisdienst_saison && $this->ergebnisdienst_liga && $this->ergebnisdienst_typ)
		{
			// Ausgabe nur wenn Schnittstelle, Saison, Liga und Anzeigetyp gesetzt sind

			// Je nach Schnittstelle den Schlüssel und die URL festlegen
			switch($this->ergebnisdienst_api)
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

			switch($this->ergebnisdienst_typ)
			{
				case '1': // Mannschaften (ohne Aufstellung) anzeigen
					$this->Template = new \FrontendTemplate('ce_ergebnisdienst_mannschaften');
					$result = file_get_contents($base_url.'/mannschaften.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga);
					$daten = json_decode($result);
					$ausgabe = array();
					if($daten->Mannschaften_Daten)
					{
						foreach($daten->Mannschaften_Daten as $item)
						{
							$oddeven = ($oddeven == 'odd') ? 'even' : 'odd';
							if($item->Mannschaft_Rueckzug == 'Nein')
							{
								$rueckzug = false;
								$class = '';
							}
							else
							{
								$rueckzug = true;
								$class = ' rueckzug';
							}

							$ausgabe[] = array
							(
								'class'      => $oddeven.$class,
								'Nummer'     => $item->Mannschaft_ID,
								'Mannschaft' => $item->Mannschaft_Name,
								'Rueckzug'   => $rueckzug
							);
						}
					}
					break;

				case '11': // Mannschaften (mit Aufstellung an Brett 1-4) anzeigen
				case '12': // Mannschaften (mit Aufstellung an Brett 1-8) anzeigen
				case '13': // Mannschaften (mit kompletter Aufstellung) anzeigen
					$this->Template = new \FrontendTemplate('ce_ergebnisdienst_mannschaften');
					$result = file_get_contents($base_url.'/mannschaften.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga);
					$daten = json_decode($result);
					$ausgabe = array();
					
					if($this->ergebnisdienst_typ == '11') $max = 4;
					elseif($this->ergebnisdienst_typ == '12') $max = 8;
					else $max = 100;

					if($daten->Mannschaften_Daten)
					{
						foreach($daten->Mannschaften_Daten as $item)
						{
							$oddeven = ($oddeven == 'odd') ? 'even' : 'odd';
							if($item->Mannschaft_Rueckzug == 'Nein')
							{
								$rueckzug = false;
								$class = '';
							}
							else
							{
								$rueckzug = true;
								$class = ' rueckzug';
							}

							// Aufstellung abfragen
							$result = file_get_contents($base_url.'/mannschaft.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga.'&m='.$item->Mannschaft_ID);
							$players = json_decode($result);

							$aufstellung = array();
							$i = 0;
							foreach($players->Spieler_Daten as $player)
							{
								$i++;
								$aufstellung[] = $player->Spieler_Vorname.' '.$player->Spieler_Nachname.($player->Spieler_Rating ? ' ('.$player->Spieler_Rating.')' : '');
								if($i == $max) break;
							}

							$ausgabe[] = array
							(
								'class'       => $oddeven.$class,
								'Nummer'      => $item->Mannschaft_ID,
								'Mannschaft'  => $item->Mannschaft_Name,
								'Aufstellung' => implode(', ', $aufstellung),
								'Rueckzug'    => $rueckzug
							);
						}
					}
					break;

				default:
			}
			
		}

		if($daten->success == 1)
		{
			// Kein Fehler aufgetreten
			$fehler = false;
		}
		else
		{
			switch($daten->success)
			{
				case 0: // allgemeiner unspezifizierter Fehler
					$fehler = 'Fehler 0 - Allgemeiner unspezifierter Fehler'; break;
				case 2: // ID für Zugriff
					$fehler = 'Fehler 2 - API-Schlüssel fehlt/falsch'; break;
				case 3: // Saison
					$fehler = 'Fehler 3 - Saison'; break;
				case 4: // Liga
					$fehler = 'Fehler 4 - Liga'; break;
				case 5: // Mannschaft
					$fehler = 'Fehler 5 - Mannschaft'; break;
				case 6: // Spieler
					$fehler = 'Fehler 6 - Spieler'; break;
				case 7: // Runde
					$fehler = 'Fehler 7 - Runde'; break;
				case 8: // Datumsbereich
					$fehler = 'Fehler 8 - Datumsbereich'; break;
				default: // Sonstiges
					$fehler = 'Fehler '.$daten->success.' - unbekannt'; break;
			}
		}
		
		$this->Template->daten = $ausgabe;
		$this->Template->error = $fehler;
		$this->Template->debug = '<pre>'.print_r($players, true).'</pre>';

		return;

	}
}
