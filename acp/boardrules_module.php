<?php
/**
*
* @package Board Rules Extension
* @copyright (c) 2014 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace phpbb\boardrules\acp;

class boardrules_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $request, $user;

		// Define admin controller
		$admin_controller = $phpbb_container->get('phpbb.boardrules.admin.controller');

		// Requests
		$action = $request->variable('action', '');
		$language = $request->variable('language', 0);
		$parent_id = $request->variable('parent_id', 0);
		$rule_id = $request->variable('rule_id', 0);

		// Send url to admin controller
		$admin_controller->set_page_url($this->u_action);

		switch($mode)
		{
			case 'settings':
				$this->tpl_name = 'boardrules_settings';

				$this->page_title = $user->lang('ACP_BOARDRULES_SETTINGS');

				if ($request->is_set_post('action_send_notification'))
				{
					$admin_controller->send_notification($rule_id);
				}

				$admin_controller->display_options();
			break;

			case 'manage':
				$this->tpl_name = 'boardrules_manage';

				$this->page_title = $user->lang('ACP_BOARDRULES_MANAGE');

				switch($action)
				{
					case 'add':
						$this->page_title = $user->lang('ACP_BOARDRULES_CREATE_RULE');

						$admin_controller->add_rule($language, $parent_id);

						return;
					break;

					case 'edit':
						$this->page_title = $user->lang('ACP_BOARDRULES_EDIT_RULE');

						$admin_controller->edit_rule($rule_id);

						return;
					break;

					case 'move_down':
						$admin_controller->move_rule($rule_id, 'down');
					break;

					case 'move_up':
						$admin_controller->move_rule($rule_id, 'up');
					break;

					case 'delete':
						$admin_controller->delete_rule($rule_id);
					break;
				}

				if (empty($language))
				{
					$admin_controller->display_language_selection();
				}
				else
				{
					$admin_controller->display_rules($language, $parent_id);
				}
			break;
		}
	}
}
