<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Recommendation
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;


HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');
HTMLHelper::_('behavior.keepalive');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_recommendation/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'recommendation.cancel') {
			Joomla.submitform(task, document.getElementById('recommendation-form'));
		}
		else {
			
			if (task != 'recommendation.cancel' && document.formvalidator.isValid(document.id('recommendation-form'))) {
				
				Joomla.submitform(task, document.getElementById('recommendation-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_recommendation&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="recommendation-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<input type="hidden" name="jform[date]" value="<?php echo $this->item->date; ?>" />
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'recommendation')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'recommendation', JText::_('COM_RECOMMENDATION_TAB_RECOMMENDATION', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_RECOMMENDATION_FIELDSET_RECOMMENDATION'); ?></legend>
				<?php echo $this->form->renderField('recommendation'); ?>
				<?php echo $this->form->renderField('id_analysis'); ?>
				<?php if ($this->state->params->get('save_history', 1)) : ?>
					<div class="control-group">
						<div class="control-label"><?php echo $this->form->getLabel('version_note'); ?></div>
						<div class="controls"><?php echo $this->form->getInput('version_note'); ?></div>
					</div>
				<?php endif; ?>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<input type="hidden" name="task" value=""/>
	<?php echo JHtml::_('form.token'); ?>

</form>
