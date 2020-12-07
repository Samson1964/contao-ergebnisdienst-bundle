<?php

namespace Schachbulle\ContaoErgebnisdienstBundle\ContentElements;

class Adresse extends \ContentElement
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
	
		$this->Template->zusatz        = '';

		return;

	}
}
