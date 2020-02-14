<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Analysis
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_analysis');

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_analysis'))
{
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_EXPLANATION'); ?></th>
			<td><?php echo nl2br($this->item->explanation); ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_TYPE_OF_ANALYSIS'); ?></th>
			<td><?php echo $this->item->type_of_analysis; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_IMAGE'); ?></th>
			<td><?php echo $this->item->image; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_DATE'); ?></th>
			<td><?php echo $this->item->date; ?></td>
		</tr>

	</table>

</div>

<?php if($canEdit): ?>

	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_analysis&task=analysis.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_ANALYSIS_EDIT_ITEM"); ?></a>

<?php endif; ?>

<?php if (JFactory::getUser()->authorise('core.delete','com_analysis.analysis.'.$this->item->id)) : ?>

	<a class="btn btn-danger" href="#deleteModal" role="button" data-toggle="modal">
		<?php echo JText::_("COM_ANALYSIS_DELETE_ITEM"); ?>
	</a>

	<div id="deleteModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3><?php echo JText::_('COM_ANALYSIS_DELETE_ITEM'); ?></h3>
		</div>
		<div class="modal-body">
			<p><?php echo JText::sprintf('COM_ANALYSIS_DELETE_CONFIRM', $this->item->id); ?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Close</button>
			<a href="<?php echo JRoute::_('index.php?option=com_analysis&task=analysis.remove&id=' . $this->item->id, false, 2); ?>" class="btn btn-danger">
				<?php echo JText::_('COM_ANALYSIS_DELETE_ITEM'); ?>
			</a>
		</div>
	</div>

<?php endif; ?>