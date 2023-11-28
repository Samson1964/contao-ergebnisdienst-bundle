<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   fen
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

/**
 * palettes
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{ergebnisdienst_legend:hide},ergebnisdienst_apikey_bl,ergebnisdienst_apikey_dsol';

/**
 * fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['ergebnisdienst_apikey_bl'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ergebnisdienst_apikey_bl'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['ergebnisdienst_apikey_dsol'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ergebnisdienst_apikey_dsol'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
