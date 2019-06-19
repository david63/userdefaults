<?php
/**
*
* @package User Defaults Extension
* @copyright (c) 2016 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\userdefaults\controller;

use phpbb\config\config;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\log\log;
use phpbb\language\language;
use phpbb\db\driver\driver_interface;
use david63\userdefaults\core\functions;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\log */
	protected $log;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \david63\userdefaults\core\functions */
	protected $functions;

	/** @var string phpBB tables */
	protected $tables;

	/** @var string custom constants */
	protected $udconstants;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\config\config					$config			Config object
	* @param \phpbb\request\request					$request		Request object
	* @param \phpbb\template\template				$template		Template object
	* @param \phpbb\user							$user			User object
	* @param \phpbb\log\log							$log			phpBB log
	* @param \phpbb\language\language				$language		Language object
	* @param \phpbb\db\driver\driver_interface		$db				The db connection
	* @param \david63\userdefaults\core\functions	$functions		Functions for the extension
	* @param array									$tables			phpBB db tables
	*
	* @access public
	*/
	public function __construct(config $config, request $request, template $template, user $user, log $log, language $language, driver_interface $db, functions $functions, $tables, $udconstants)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->log			= $log;
		$this->language		= $language;
		$this->db  			= $db;
		$this->functions	= $functions;
		$this->tables		= $tables;
		$this->constants	= $udconstants;
	}

	/**
	* Display the ouptions for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Add the language file
		$this->language->add_lang('acp_userdefaults', $this->functions->get_ext_namespace());

		add_form_key($this->constants['form_key']);

		$back = false;

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key($this->constants['form_key']))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'USER_DEFAULTS_LOG');
			trigger_error($this->user->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Create the date format list
		$dateformat_options = '<select name="ud_date_format" id="ud_date_format">';

		foreach ($this->language->lang_raw('dateformats') as $format => $null)
		{
			$dateformat_options .= '<option value="' . $format . '"' . (($format == $this->config['ud_date_format']) ? ' selected="selected"' : '') . '>';
			$dateformat_options .= $this->user->format_date(time(), $format, false) . ((strpos($format, '|') !== false) ? $this->language->lang('VARIANT_DATE_SEPARATOR') . $this->user->format_date(time(), $format, true) : '');
			$dateformat_options .= '</option>';
		}

		$dateformat_options .= '</select>';

		// Create the topic & post display options
		$sort_dir_text = array('a' => $this->language->lang('ASCENDING'), 'd' => $this->language->lang('DESCENDING'));

		// Topic ordering options
		$limit_topic_days = array(0 => $this->language->lang('ALL_TOPICS'), 1 => $this->language->lang('1_DAY'), 7 => $this->language->lang('7_DAYS'), 14 => $this->language->lang('2_WEEKS'), 30 => $this->language->lang('1_MONTH'), 90 => $this->language->lang('3_MONTHS'), 180 => $this->language->lang('6_MONTHS'), 365 => $this->language->lang('1_YEAR'));

		$sort_by_topic_text = array('a' => $this->language->lang('AUTHOR'), 't' => $this->language->lang('POST_TIME'), 'r' => $this->language->lang('REPLIES'), 's' => $this->language->lang('SUBJECT'), 'v' => $this->language->lang('VIEWS'));

		// Post ordering options
		$limit_post_days = array(0 => $this->language->lang('ALL_POSTS'), 1 => $this->language->lang('1_DAY'), 7 => $this->language->lang('7_DAYS'), 14 => $this->language->lang('2_WEEKS'), 30 => $this->language->lang('1_MONTH'), 90 => $this->language->lang('3_MONTHS'), 180 => $this->language->lang('6_MONTHS'), 365 => $this->language->lang('1_YEAR'));

		$sort_by_post_text = array('a' => $this->language->lang('AUTHOR'), 't' => $this->language->lang('POST_TIME'), 's' => $this->language->lang('SUBJECT'));

		$_options = array('topic', 'post');
		foreach ($_options as $sort_option)
		{
			${'s_limit_' . $sort_option . '_days'} = '<select name="' . $sort_option . '_st">';
			foreach (${'limit_' . $sort_option . '_days'} as $day => $text)
			{
				$selected = ($this->config['ud_' . $sort_option . '_st'] == $day) ? ' selected="selected"' : '';
				${'s_limit_' . $sort_option . '_days'} .= '<option value="' . $day . '"' . $selected . '>' . $text . '</option>';
			}
			${'s_limit_' . $sort_option . '_days'} .= '</select>';

			${'s_sort_' . $sort_option . '_key'} = '<select name="' . $sort_option . '_sk">';
			foreach (${'sort_by_' . $sort_option . '_text'} as $key => $text)
			{
				$selected = ($this->config['ud_' . $sort_option . '_sk'] == $key) ? ' selected="selected"' : '';
				${'s_sort_' . $sort_option . '_key'} .= '<option value="' . $key . '"' . $selected . '>' . $text . '</option>';
			}
			${'s_sort_' . $sort_option . '_key'} .= '</select>';

			${'s_sort_' . $sort_option . '_dir'} = '<select name="' . $sort_option . '_sd">';
			foreach ($sort_dir_text as $key => $value)
			{
				$selected = ($this->config['ud_' . $sort_option . '_sd'] == $key) ? ' selected="selected"' : '';
				${'s_sort_' . $sort_option . '_dir'} .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			${'s_sort_' . $sort_option . '_dir'} .= '</select>';
		}

		// Get the default date format from the users table
		$sql = 'SELECT DEFAULT(user_dateformat) AS default_dateformat
			 FROM ' . $this->tables['users'];
		$result = $this->db->sql_query($sql);

		$default_dateformat = $this->db->sql_fetchfield('default_dateformat');
		$this->db->sql_freeresult($result);

		// Template vars for header panel
		$this->template->assign_vars(array(
			'HEAD_TITLE'		=> $this->language->lang('USER_DEFAULTS'),
			'HEAD_DESCRIPTION'	=> $this->language->lang('USER_DEFAULTS_EXPLAIN'),

			'NAMESPACE'			=> $this->functions->get_ext_namespace('twig'),

			'S_BACK'			=> $back,
			'S_VERSION_CHECK'	=> $this->functions->version_check(),

			'VERSION_NUMBER'	=> $this->functions->get_this_version(),
		));

		$this->template->assign_vars(array(
			'DEFAULT_DATE_FORMAT' 		=> $this->language->lang('DEFAULT_DATE_FORMAT', date($default_dateformat, time())),
			'NOTIFY_BOOKMARK'			=> isset($this->config['ud_type_bookmark']) ? $this->config['ud_type_bookmark'] : 0,
			'NOTIFY_GROUP'				=> isset($this->config['ud_type_group']) ? $this->config['ud_type_group'] : 0,
			'NOTIFY_MODERATOR_APPROVAL'	=> isset($this->config['ud_type_needs_approval']) ? $this->config['ud_type_needs_approval'] : 0,
			'NOTIFY_MODERATION_QUEUE'	=> isset($this->config['ud_moderation_queue']) ? $this->config['ud_moderation_queue'] : 0,
			'NOTIFY_PM'			  		=> isset($this->config['ud_type_pm']) ? $this->config['ud_type_pm'] : 0,
			'NOTIFY_QUOTE'				=> isset($this->config['ud_type_quote']) ? $this->config['ud_type_quote'] : 0,
			'NOTIFY_REPORT'				=> isset($this->config['ud_type_report']) ? $this->config['ud_type_report'] : 0,
			'NOTIFY_SUBSCRIBED'			=> isset($this->config['ud_type_post']) ? $this->config['ud_type_post'] : 1,
			'NOTIFY_TOPIC'				=> isset($this->config['ud_type_topic']) ? $this->config['ud_type_topic'] : 1,

			'S_AVATARS'					=> phpbb_optionget($this->constants['viewavatars'], $this->config['ud_options']),
			'S_BBCODE'					=> phpbb_optionget($this->constants['bbcode'], $this->config['ud_options']),
			'S_DISABLE_CENSORS'			=> phpbb_optionget($this->constants['viewcensors'], $this->config['ud_options']),
			'S_FLASH'					=> phpbb_optionget($this->constants['viewflash'], $this->config['ud_options']),
			'S_IMAGES'					=> phpbb_optionget($this->constants['viewimg'], $this->config['ud_options']),
			'S_SMILIES'					=> phpbb_optionget($this->constants['smilies'], $this->config['ud_options']),
			'S_SIG'						=> phpbb_optionget($this->constants['attachsig'], $this->config['ud_options']),
			'S_SIGS'					=> phpbb_optionget($this->constants['viewsigs'], $this->config['ud_options']),
			'S_VSMILIES'				=> phpbb_optionget($this->constants['viewsmilies'], $this->config['ud_options']),

			'S_POST_SORT_DAYS'			=> $s_limit_post_days,
			'S_POST_SORT_DIR'			=> $s_sort_post_dir,
			'S_POST_SORT_KEY'			=> $s_sort_post_key,
			'S_TOPIC_SORT_DAYS'			=> $s_limit_topic_days,
			'S_TOPIC_SORT_DIR'			=> $s_sort_topic_dir,
			'S_TOPIC_SORT_KEY'			=> $s_sort_topic_key,

			'USER_ALLOW_MASSEMAIL'		=> isset($this->config['ud_allow_massemail']) ? $this->config['ud_allow_massemail'] : 1,
			'USER_ALLOW_PM'				=> isset($this->config['ud_allow_pm']) ? $this->config['ud_allow_pm'] : 1,
			'USER_ALLOW_VIEWEMAIL'		=> isset($this->config['ud_allow_viewemail']) ? $this->config['ud_allow_viewemail'] : 1,
			'USER_DATE_FORMAT'			=> $dateformat_options,
			'USER_NOTIFY'				=> isset($this->config['ud_notify']) ? $this->config['ud_notify'] : 0,
			'USER_NOTIFY_PM'			=> isset($this->config['ud_notify_pm']) ? $this->config['ud_notify_pm'] : 1,

			'U_ACTION'					=> $this->u_action,
		));
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('ud_allow_massemail', $this->request->variable('ud_allow_massemail', 0));
		$this->config->set('ud_allow_pm', $this->request->variable('ud_allow_pm', 0));
		$this->config->set('ud_allow_viewemail', $this->request->variable('ud_allow_viewemail', 0));
		$this->config->set('ud_date_format', $this->request->variable('ud_date_format', ''));
		$this->config->set('ud_moderation_queue', $this->request->variable('ud_moderation_queue', 0));
		$this->config->set('ud_notify', $this->request->variable('ud_notify', ''));
		$this->config->set('ud_notify_pm', $this->request->variable('ud_notify_pm', ''));
		$this->config->set('ud_options', phpbb_optionset(constants::ATTACHSIG, $this->request->variable('ud_sig', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::BBCODE, $this->request->variable('ud_bbcode', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::SMILIES, $this->request->variable('ud1_smilies', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::VIEWAVATARS, $this->request->variable('ud_avatars', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::VIEWCENSORS, $this->request->variable('ud_wordcensor', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::VIEWFLASH, $this->request->variable('ud_flash', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::VIEWIMG, $this->request->variable('ud_images', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::VIEWSIGS, $this->request->variable('ud_sigs', 0), $this->config['ud_options']));
		$this->config->set('ud_options', phpbb_optionset(constants::VIEWSMILIES, $this->request->variable('ud_smilies', 0), $this->config['ud_options']));
		$this->config->set('ud_post_sd', $this->request->variable('post_sd', 'a'));
		$this->config->set('ud_post_sk', $this->request->variable('post_sk', 't'));
		$this->config->set('ud_post_st', $this->request->variable('post_st', 0));
		$this->config->set('ud_topic_sd', $this->request->variable('topic_sd', 'd'));
		$this->config->set('ud_topic_sk', $this->request->variable('topic_sk', 't'));
		$this->config->set('ud_topic_st', $this->request->variable('topic_st', 0));
		$this->config->set('ud_type_bookmark', $this->request->variable('ud_type_bookmark', 0));
		$this->config->set('ud_type_group', $this->request->variable('ud_type_group', 0));
		$this->config->set('ud_type_needs_approval', $this->request->variable('ud_type_needs_approval', 0));
		$this->config->set('ud_type_pm', $this->request->variable('ud_type_pm', 0));
		$this->config->set('ud_type_post', $this->request->variable('ud_type_post', 1));
		$this->config->set('ud_type_quote', $this->request->variable('ud_type_quote', 0));
		$this->config->set('ud_type_report', $this->request->variable('ud_type_report', 0));
		$this->config->set('ud_type_topic', $this->request->variable('ud_type_topic', 1));
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
