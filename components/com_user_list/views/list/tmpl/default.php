<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_list
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_USER_LIST_FORM_LBL_LIST_AVATAR'); ?></th>
			<td><?php echo $this->item->avatar; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_USER_LIST_FORM_LBL_LIST_USERNAME'); ?></th>
			<td><?php echo $this->item->username; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_USER_LIST_FORM_LBL_LIST_YEAR_OF_BIRTH'); ?></th>
			<td><?php echo $this->item->year_of_birth; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_USER_LIST_FORM_LBL_LIST_GENDER'); ?></th>
			<td><?php echo $this->item->gender; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_USER_LIST_FORM_LBL_LIST_VIEW_HEALTH_INFO'); ?></th>
			<td><?php echo $this->item->view_health_info; ?></td>
		</tr>

	</table>

</div>

