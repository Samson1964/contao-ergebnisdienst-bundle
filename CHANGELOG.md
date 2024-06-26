# DSB-Ergebnisdienst Changelog

## Version 0.3.3 (2024-05-18)

* Fix: Warning: Undefined array key "ergebnisdienst_apis" in tl_content:22

## Version 0.3.2 (2023-11-28)

* Fix: Geänderte API bei Ausgabe der Brettspieler
* Fix: Liga-Parameter bei Rundenanzeige im Backend vergessen
* Fix: Beseitigung von PHP-8-Fehlern, z.B. nicht initialisierte Variablen

## Version 0.3.1 (2023-11-22)

* Change: README ausgebaut
* Add: Logging für die Zugriffe auf die API
* Fix: Fehler beseitigt in Zusammenhang mit PHP 8
* Fix: Ausgabe der Termine war falsch

## Version 0.3.0 (2023-11-22)

* Change: Ungetestete Freigabe für PHP 8 in composer.json

## Version 0.2.2 (2021-04-19)

* Add: CSS-Klassen für Tabellen in den Templates

## Version 0.2.1 (2021-04-19)

* Delete: Debugausgabe ContentElements/Ergebnisdienst

## Version 0.2.0 (2021-04-19)

* Delete: tl_content.space (wird in Contao 4 nicht mehr unterstützt)
* Add: Ausgabe der Termine einer Runde + Template ce_ergebnisdienst_termine

## Version 0.1.1 (2021-02-16)

* Add: Unterscheidung bei Tabellen - Rangliste und Kreuztabelle
* Add: CSS-Klassen für Tabellen hinzugefügt

## Version 0.1.0 (2021-02-16)

* Fix: Leerzeile hinter letzter Paarung entfernt (Template ce_ergebnisdienst_paarungen geändert)
* Change: Paarungsnummern bei Mannschaftsergebnissen - ohne Einzelergebnisse = fortlaufende Nummer, mit Einzelergebnissen = "Br."
* Change: Ausgabe "DWZ" in Kopfspalte entfernt, wenn es keine Einzelergebnisse gibt
* Add: Template ce_ergebnisdienst_tabelle
* Add: Funktion zur Ausgabe von Kreuztabellen

## Version 0.0.4 (2021-01-19)

* Add: Ergebnisausgabe einer Runde (alle Mannschaften oder nur eine Mannschaft) mit Einzelergebnissen

## Version 0.0.3 (2021-01-11)

* Fix: Debug-Ausgabe im Template entfernt

## Version 0.0.2 (2021-01-11)

* Add: Übersetzungen tl_settings und tl_content
* Add: Anpassung der Klasse Ergebnisdienst
* Add: API-Feld für DSOL

## Version 0.0.1 (2020-12-07)

* Initiale Version
