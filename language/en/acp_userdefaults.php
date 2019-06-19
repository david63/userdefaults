<?php
/**
*
* @package User Defaults Extension
* @copyright (c) 2016 david63
* * @license GNU General Public License, version 2 (GPL-2.0)
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'APPROVE_NOTIFICATION'		=> 'Topics/posts that are approved or disapproved by a moderator',

	'CREATE_SUBSCRIBED_TOPIC'	=> 'Create a topic in a forum to which the user has subscribed',

	'DATE_FORMAT'				=> 'User’s date format',
	'DEFAULT_ADD_SIG'			=> 'Attach the user’s signature',
	'DEFAULT_ALL_POSTS' 		=> '<strong>Default = All posts</strong>',
	'DEFAULT_ALL_TOPICS' 		=> '<strong>Default = All topics</strong>',
	'DEFAULT_ASC' 				=> '<strong>Default = Ascending</strong>',
	'DEFAULT_BBCODE'			=> 'Enable BBCode',
	'DEFAULT_DATE_FORMAT'		=> '<strong>Default = %s</strong>',
	'DEFAULT_DESC' 				=> '<strong>Default = Descending</strong>',
	'DEFAULT_EMAIL'				=> '<strong>Default = Email</strong>',
	'DEFAULT_NO'				=> '<strong>Default = No</strong>',
	'DEFAULT_NOTIFICATION'		=> '<strong>Default = Notification</strong>',
	'DEFAULT_POST_TIME' 		=> '<strong>Default = Post time</strong>',
	'DEFAULT_SMILIES'			=> 'Enable smilies',
	'DEFAULT_YES'				=> '<strong>Default = Yes</strong>',
	'DISABLE_CENSORS'			=> 'Enable word censoring',
	'DISPLAY_SETTINGS'			=> 'Display options',

	'EMAIL'						=> 'Email',

	'GLOBAL_SETTINGS'			=> 'Global settings',

	'MISC_NOTIFICATIONS'		=> 'Miscellaneous notifications',
	'MODERATOR_APPROVAL'		=> 'A post or topic needs approval',
	'MODERATOR_NOTIFICATIONS'	=> 'Moderation Notifications',
	'MODERATOR_REPORT'			=> 'Someone reports a post',

	'NEW_VERSION'				=> 'New Version',
	'NEW_VERSION_EXPLAIN'		=> 'There is a newer version of this extension available.',
	'NOTIFICATION'				=> 'Notification',

	'POSTING_NOTIFICATIONS'		=> 'Posting notifications',
	'POSTING_SETTINGS'			=> 'Posting defaults',

	'QUOTE_POST'				=> 'Quotes the user in a post',

	'REQUEST_GROUP'				=> 'Someone requests to join a group you lead',
	'REPLY_BOOKMARK_TOPIC'		=> 'Replies to a topic the user has bookmarked',
	'REPLY_SUBSCRIBED_TOPIC'	=> 'Replies to a topic the user has subscribed to',

	'SEND_PM'					=> 'Someone sends the user a private message',

	'USER_ALLOW_PM'				=> 'Allow users to be sent private messages',
	'USER_ALLOW_MASSEMAIL'		=> 'Administrators can email information to the user',
	'USER_ALLOW_VIEWEMAIL'		=> 'The user can be contacted by email',
	'USER_DEFAULTS_EXPLAIN'		=> 'Set here the values that you want as the default values for <strong>new</strong> users.<br>Any changes made via this form will <strong>not</strong> affect existing users.',
	'USER_NOTIFY'				=> 'Notify the user upon replies by default',
	'USER_NOTIFY_PM'			=> 'User notify PM',

	'VERSION'					=> 'Version',
	'VIEW_AVATARS'				=> 'Display avatars',
	'VIEW_FLASH'				=> 'Display Flash animations',
	'VIEW_IMAGES'				=> 'Display images within posts',
	'VIEW_POSTS_DAYS' 			=> 'Display posts from previous days',
	'VIEW_POSTS_DIR' 			=> 'Display post order direction',
	'VIEW_POSTS_KEY' 			=> 'Display posts ordering by',
	'VIEW_SIGS'					=> 'Display signatures',
	'VIEW_SMILIES'				=> 'Display smilies as images',
	'VIEW_TOPICS_DAYS' 			=> 'Display topics from previous days',
	'VIEW_TOPICS_DIR' 			=> 'Display topic order direction',
	'VIEW_TOPICS_KEY' 			=> 'Display topics ordering by',
));

// Donate
$lang = array_merge($lang, array(
	'DONATE'					=> 'Donate',
	'DONATE_EXTENSIONS'			=> 'Donate to my extensions',
	'DONATE_EXTENSIONS_EXPLAIN'	=> 'This extension, as with all of my extensions, is totally free of charge. If you have benefited from using it then please consider making a donation by clicking the PayPal donation button opposite - I would appreciate it. I promise that there will be no spam nor requests for further donations, although they would always be welcome.',

	'PAYPAL_BUTTON'				=> 'Donate with PayPal button',
	'PAYPAL_TITLE'				=> 'PayPal - The safer, easier way to pay online!',
));
