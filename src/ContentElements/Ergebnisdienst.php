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

				case '2': // Termine
					$this->Template = new \FrontendTemplate('ce_ergebnisdienst_termine');
					if($this->ergebnisdienst_runde)
						$result = file_get_contents($base_url.'/ansetzungen.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga.'&r='.$this->ergebnisdienst_runde);

					$daten = json_decode($result);
					$ausgabe = array();
					echo "<pre>";
					print_r($daten);
					echo "</pre>";
					// Ansetzungen vorhanden?
					if($daten->Ansetzungen_Daten)
					{
						$i = 0;
						foreach($daten->Ansetzungen_Daten as $ansetzung)
						{
							if(!$this->ergebnisdienst_mannschaft || $this->ergebnisdienst_mannschaft == $ansetzung->Ansetzung_Heim_ID || $this->ergebnisdienst_mannschaft == $ansetzung->Ansetzung_Gast_ID)
							{
								$ausgabe[$i] = array
								(
									'datum'     => date("d.m.Y H:i",strtotime($ansetzung->Ansetzung_Termin)),
									'heim_name' => $ansetzung->Ansetzung_Heim_Name,
									'gast_name' => $ansetzung->Ansetzung_Gast_Name,
								);
								$i++;
							}
						}
					}
					break;

				case '3': // Ergebnisse/Paarungen
					$this->Template = new \FrontendTemplate('ce_ergebnisdienst_paarungen');
					if($this->ergebnisdienst_runde)
						$result = file_get_contents($base_url.'/ergebnisse.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga.'&r='.$this->ergebnisdienst_runde);
					else
						// Gibt keine Einzelergebnisse aus!
						$result = file_get_contents($base_url.'/ansetzungen.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga.'&r=all');

					$daten = json_decode($result);
					$ausgabe = array();
					// Ansetzungen vorhanden?
					if($daten->Ansetzungen_Daten)
					{
						$i = 0;
						foreach($daten->Ansetzungen_Daten as $ansetzung)
						{
							if(!$this->ergebnisdienst_mannschaft || $this->ergebnisdienst_mannschaft == $ansetzung->Ansetzung_Heim_ID || $this->ergebnisdienst_mannschaft == $ansetzung->Ansetzung_Gast_ID)
							{
								$ausgabe[$i] = array
								(
									'nummer'    => $ansetzung->Ergebnisse_Daten ? 'Br.' : ($i + 1),
									'heim_name' => $ansetzung->Ansetzung_Heim_Name,
									'gast_name' => $ansetzung->Ansetzung_Gast_Name,
									'ergebnis'  => $ansetzung->Ansetzung_Heim_BP || $ansetzung->Ansetzung_Gast_BP ? $ansetzung->Ansetzung_Heim_BP.':'.$ansetzung->Ansetzung_Gast_BP : '-',
									'bretter'   => array()
								);
								// Einzelergebnisse vorhanden?
								if($ansetzung->Ergebnisse_Daten)
								{
									$oddeven = 'odd';
									foreach($ansetzung->Ergebnisse_Daten as $brett)
									{
										$oddeven = ($oddeven == 'odd') ? 'even' : 'odd';
										$ausgabe[$i]['bretter'][] = array
										(
											'class'       => $oddeven,
											'brett'       => $brett->Ergebnis_Brett,
											'heim_name'   => trim($brett->Ergebnis_Heim_Spieler_Titel.' '.$brett->Ergebnis_Heim_Spieler_Vorname.' '.$brett->Ergebnis_Heim_Spieler_Nachname),
											'heim_rating' => $brett->Ergebnis_Heim_Spieler_Rating,
											'heim_farbe'  => $brett->Ergebnis_Heim_Farbe == 'S' ? 'black' : 'white',
											'gast_name'   => trim($brett->Ergebnis_Gast_Spieler_Titel.' '.$brett->Ergebnis_Gast_Spieler_Vorname.' '.$brett->Ergebnis_Gast_Spieler_Nachname),
											'gast_rating' => $brett->Ergebnis_Gast_Spieler_Rating,
											'gast_farbe'  => $brett->Ergebnis_Gast_Farbe == 'S' ? 'black' : 'white',
											'ergebnis'    => $brett->Ergebnis_Heim_Ergebnis || $brett->Ergebnis_Gast_Ergebnis ? $brett->Ergebnis_Heim_Ergebnis.':'.$brett->Ergebnis_Gast_Ergebnis : '-'
										);
									}
								}
								$i++;
							}
						}
					}
					break;

				case '4': // Tabelle (Rangliste)
				case '41': // Tabelle (Kreuztabelle)
					$this->Template = new \FrontendTemplate('ce_ergebnisdienst_tabelle');
					if($this->ergebnisdienst_runde)
						// Tabelle nach Runde x
						$result = file_get_contents($base_url.'/tabelle.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga.'&r='.$this->ergebnisdienst_runde);
					else
						// Tabelle nach allen Runden
						$result = file_get_contents($base_url.'/tabelle.php?i='.$apikey.'&s='.$this->ergebnisdienst_saison.'&l='.$this->ergebnisdienst_liga.'&r=all');

					$daten = json_decode($result);
					$ausgabe = array();

					// Mannschaften ausgeben
					if($daten->Tabelle_Daten)
					{

						if($this->ergebnisdienst_typ == '41')
						{
							// Leere Kreuztabelle anlegen
							$kreuztabelle = array();
							for($x = 1; $x <= $daten->Tabelle_Anzahl; $x++)
							{
								for($y = 1; $y <= $daten->Tabelle_Anzahl; $y++)
								{
									$kreuztabelle[$x][$y] = ($x == $y) ? 'x' : '';
								}
							}
						}

						$i = 0;
						foreach($daten->Tabelle_Daten as $ansetzung)
						{
							$oddeven = ($oddeven == 'odd') ? 'even' : 'odd';
							$ausgabe[$i] = array
							(
								'class'      => $oddeven,
								'Platz'      => $ansetzung->Tabelle_Platz,
								'Name'       => $ansetzung->Tabelle_Mannschaft_Name,
								'Spiele'     => $ansetzung->Tabelle_Spiele,
								'MP'         => $ansetzung->Tabelle_MP,
								'BP'         => str_replace('.', ',', sprintf('%0.1f', $ansetzung->Tabelle_BP)),
								'BW'         => $ansetzung->Tabelle_BW,
								'Rueckzug'   => $ansetzung->Tabelle_Mannschaft_Rueckzug
							);

							if($this->ergebnisdienst_typ == '41')
							{
								// Ergebnisse in Kreuztabelle eintragen
								if($ansetzung->Ansetzungen_Daten)
								{
									foreach($ansetzung->Ansetzungen_Daten as $ergebnis)
									{
										$kreuztabelle[$i+1][$ergebnis->Ansetzung_Gegner_Platz] = str_replace('.', ',', sprintf('%0.1f', $ergebnis->Ansetzung_BP));
									}
								}
							}

							$i++;
						}
					}
					$this->Template->class .= ' tabelle';
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
		if($kreuztabelle) $this->Template->kreuztabelle = $kreuztabelle;
		$this->Template->error = $fehler;
		//$this->Template->debug = '<pre>'.print_r($daten, true).print_r($ausgabe, true).'</pre>';
		//$this->Template->debug = '<pre>'.print_r($kreuztabelle, true).print_r($ausgabe, true).'</pre>';

		return;

	}
}
