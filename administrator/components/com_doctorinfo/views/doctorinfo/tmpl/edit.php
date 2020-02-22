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
$document->addStyleSheet(Uri::root() . 'media/com_doctorinfo/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'doctorinfo.cancel') {
			Joomla.submitform(task, document.getElementById('doctorinfo-form'));
		}
		else {
			
			if (task != 'doctorinfo.cancel' && document.formvalidator.isValid(document.id('doctorinfo-form'))) {
				
				Joomla.submitform(task, document.getElementById('doctorinfo-form'));
			}
			else {
				alert('<?php echo $this->escape(Text::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_doctorinfo&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="doctorinfo-form" class="form-validate form-horizontal">

	
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<?php echo $this->form->renderField('created_by'); ?>
	<input type="hidden" name="jform[date_data_added]" value="<?php echo $this->item->date_data_added; ?>" />
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'doctorinfo')); ?>
	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'doctorinfo', JText::_('COM_DOCTORINFO_TAB_DOCTORINFO', true)); ?>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_DOCTORINFO_FIELDSET_DOCTORINFO'); ?></legend>
				<?php echo $this->form->renderField('file'); ?>
				<?php if (!empty($this->item->file)) : ?>
					<?php $fileFiles = array(); ?>
					<?php foreach ((array)$this->item->file as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'uploads' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $fileFiles[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<input type="hidden" name="jform[file_hidden]" id="jform_file_hidden" value="<?php echo implode(',', $fileFiles); ?>" />
				<?php endif; ?>
				<?php echo $this->form->renderField('additional_information'); ?>
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
