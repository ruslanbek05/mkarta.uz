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

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_doctorinfo', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_doctorinfo/js/form.js');

$user    = Factory::getUser();
$canEdit = DoctorinfoHelpersDoctorinfo::canUserEdit($this->item, $user);


?>

<div class="doctorinfo-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_DOCTORINFO_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_DOCTORINFO_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_DOCTORINFO_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-doctorinfo"
			  action="<?php echo Route::_('index.php?option=com_doctorinfo&task=doctorinfo.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
	<input type="hidden" name="jform[date_data_added]" value="<?php echo $this->item->date_data_added; ?>" />

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

			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo Route::_('index.php?option=com_doctorinfo&task=doctorinfoform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_doctorinfo"/>
			<input type="hidden" name="task"
				   value="doctorinfoform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
