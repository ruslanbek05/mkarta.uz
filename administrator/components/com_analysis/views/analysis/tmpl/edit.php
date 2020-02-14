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
$document->addStyleSheet(Uri::root() . 'media/com_analysis/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	js('input:hidden.type_of_analysis').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('type_of_analysishidden')){
			js('#jform_type_of_analysis option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_type_of_analysis").trigger("liszt:updated");
	});

	Joomla.submitbutton = function (task) {
		if (task == 'analysis.cancel') {
			Joomla.submitform(task, document.getElementById('analysis-form'));
		}
		else {
			
			if (task != 'analysis.cancel' && document.formvalidator.isValid(document.id('analysis-form'))) {
				
				Joomla.submitform(task, document.getElementById('analysis-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_analysis&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="analysis-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'analysis')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'analysis', JText::_('COM_ANALYSIS_TAB_ANALYSIS', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_ANALYSIS_FIELDSET_ANALYSIS'); ?></legend>
				<?php echo $this->form->renderField('explanation'); ?>
				<?php echo $this->form->renderField('type_of_analysis'); ?>
				<?php
				foreach((array)$this->item->type_of_analysis as $value)
				{
					if(!is_array($value))
					{
						echo '<input type="hidden" class="type_of_analysis" name="jform[type_of_analysishidden]['.$value.']" value="'.$value.'" />';
					}
				}
				?>
				<?php echo $this->form->renderField('image'); ?>
				<?php echo $this->form->renderField('date'); ?>
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
