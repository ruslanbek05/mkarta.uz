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
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_doctorinfo') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'doctorinfoform.xml');
$canEdit    = $user->authorise('core.edit', 'com_doctorinfo') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'doctorinfoform.xml');
$canCheckin = $user->authorise('core.manage', 'com_doctorinfo');
$canChange  = $user->authorise('core.edit.state', 'com_doctorinfo');
$canDelete  = $user->authorise('core.delete', 'com_doctorinfo');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_doctorinfo/css/list.css');
?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	
        <div class="table-responsive">
	<table class="table table-striped" id="doctorinfoList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DOCTORINFO_DOCTORINFOS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DOCTORINFO_DOCTORINFOS_CREATED_BY', 'a.created_by', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DOCTORINFO_DOCTORINFOS_DATE_DATA_ADDED', 'a.date_data_added', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DOCTORINFO_DOCTORINFOS_FILE', 'a.file', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_DOCTORINFO_DOCTORINFOS_ADDITIONAL_INFORMATION', 'a.additional_information', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_DOCTORINFO_DOCTORINFOS_ACTIONS'); ?>
				</th>
				<?php endif; ?>

		</tr>
		</thead>
		<tfoot>
		<tr>
			<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_doctorinfo'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_doctorinfo')): ?>
					<?php $canEdit = JFactory::getUser()->id == $item->created_by; ?>
				<?php endif; ?>

			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>

							<?php echo JFactory::getUser($item->created_by)->name; ?>				</td>
				<td>

					<?php echo $item->date_data_added; ?>
				</td>
				<td>

					<?php
					require_once("myfunc.php");
						if (!empty($item->file)) :
							$fileArr = (array) explode(',', $item->file);
							foreach ($fileArr as $singleFile) : 
								if (!is_array($singleFile)) :
									$uploadPath = 'pic_ture' . DIRECTORY_SEPARATOR . 'docinfo' . $singleFile;
					$filename_constant = 'pic_ture/docinfo/' . $singleFile;
					$filename_thumb = 'pic_ture/thumb/' . $singleFile;
					if (!JFile::exists($filename_thumb))
					{
						//echo "<img src=\".$filename_temp.\" alt=\"error\">";
						create_file_with_dir_index_html($filename_constant);
						create_file_with_dir_index_html($filename_thumb);
						make_thumb($filename_constant, $filename_thumb);
						//echo $filename_protected;
						//echo "file exists2";
						//die;
					}else{
						//echo "file does not exists</br>";
						//echo $filename_protected;
						//die;
					}
									//echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank" title="See the file">' . $singleFile . '</a> ';
						echo '<img src="'.$filename_thumb.'" alt="'.basename($filename_thumb).'" width="100" height="100">';
								endif;
							endforeach;
						else:
							echo $item->file;
						endif; ?>				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'doctorinfos.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_doctorinfo&view=doctorinfo&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->additional_information); ?></a>
				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_doctorinfo&task=doctorinfoform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_doctorinfo&task=doctorinfoform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_doctorinfo&task=doctorinfoform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_DOCTORINFO_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo Text::_('COM_DOCTORINFO_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
