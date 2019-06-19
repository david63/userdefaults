<?php
/**
*
* @package User Defaults Extension
* @copyright (c) 2016 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\userdefaults\migrations;
use \phpbb\db\migration\migration;

class version_2_1_0 extends migration
{
	public function update_data()
	{
		return array(
			array('config.add', array('ud_allow_pm', 1)),
			array('config.add', array('ud_allow_massemail', 1)),
			array('config.add', array('ud_allow_viewemail', 1)),
			array('config.add', array('ud_allow_viewonline', 0)),
			array('config.add', array('ud_date_format', 'd M Y H:i')),
			array('config.add', array('ud_moderation_queue', 0)),
			array('config.add', array('ud_notifications', 0)),
			array('config.add', array('ud_notify', 0)),
			array('config.add', array('ud_notify_pm', 1)),
			array('config.add', array('ud_options', 230271)),
			array('config.add', array('ud_post_sd', 'a')),
			array('config.add', array('ud_post_sk', 't')),
			array('config.add', array('ud_post_st', 0)),
			array('config.add', array('ud_topic_sd', 'd')),
			array('config.add', array('ud_topic_sk', 't')),
			array('config.add', array('ud_topic_st', 0)),
			array('config.add', array('ud_type_bookmark', 0)),
			array('config.add', array('ud_type_group', 0)),
			array('config.add', array('ud_type_needs_approval', 0)),
			array('config.add', array('ud_type_pm', 0)),
			array('config.add', array('ud_type_post', 1)),
			array('config.add', array('ud_type_quote', 0)),
			array('config.add', array('ud_type_report', 0)),
			array('config.add', array('ud_type_topic', 1)),

			// Add the ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'USER_DEFAULTS')),

			array('module.add', array(
				'acp', 'USER_DEFAULTS', array(
					'module_basename'	=> '\david63\userdefaults\acp\userdefaults_module',
					'modes'				=> array('manage'),
				),
			)),
		);
	}
}
