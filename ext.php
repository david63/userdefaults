<?php
/**
*
* @package User Defaults Extension
* @copyright (c) 2016 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\userdefaults;

use phpbb\extension\base;

class ext extends base
{
	/**
	* Enable extension if phpBB version requirement is met
	*
	* @var string Require 3.2.1-RC1 due to updated 3.2 syntax and required event
	*
	* @return bool
	* @access public
	*/
	public function is_enableable()
	{
		// Requires phpBB 3.2.0 or newer.
		$is_enableable = phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=');

		// Display a custom warning message if requirement fails.
		if (!$is_enableable)
		{
			// Need to cater for 3.1 and 3.2
			if (phpbb_version_compare(PHPBB_VERSION, '3.2.1-RC1', '>='))
			{
				$this->container->get('language')->add_lang('ext_enable_error', 'david63/userdefaults');
			}
			else
			{
				$this->container->get('user')->add_lang_ext('david63/userdefaults', 'ext_enable_error');
			}
		}

		return $is_enableable;
	}
}
