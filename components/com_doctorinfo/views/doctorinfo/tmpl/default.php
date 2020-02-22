<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Doctorinfo
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_doctorinfo');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_doctorinfo'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_DOCTORINFO_FORM_LBL_DOCTORINFO_FILE'); ?></th>
			<td>
			<?php
			foreach ((array) $this->item->file as $singleFile) : 
				if (!is_array($singleFile)) : 
					$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . $singleFile;
					 echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_DOCTORINFO_FORM_LBL_DOCTORINFO_ADDITIONAL_INFORMATION'); ?></th>
			<td><?php echo nl2br($this->item->additional_information); ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_doctorinfo&task=doctorinfo.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_DOCTORINFO_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_doctorinfo.doctorinfo.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_DOCTORINFO_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_DOCTORINFO_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_DOCTORINFO_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_doctorinfo&task=doctorinfo.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_DOCTORINFO_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>