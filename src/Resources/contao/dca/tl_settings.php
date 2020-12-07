<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{ergebnisdienst_legend:hide},ergebnisdienst_api_id';

/**
 * fields
 */

$GLOBALS['TL_DCA']['tl_settings']['fields']['ergebnisdienst_api_id'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['ergebnisdienst_api_id'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('tl_class'=>'w50'),
	'sql'                     => "varchar(255) NOT NULL default ''"
);
