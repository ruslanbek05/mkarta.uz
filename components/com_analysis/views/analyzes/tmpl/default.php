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
HTMLHelper::_('bootstrap.tooltip');
HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');

$user       = Factory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_analysis') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'analysisform.xml');
$canEdit    = $user->authorise('core.edit', 'com_analysis') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'analysisform.xml');
$canCheckin = $user->authorise('core.manage', 'com_analysis');
$canChange  = $user->authorise('core.edit.state', 'com_analysis');
$canDelete  = $user->authorise('core.delete', 'com_analysis');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_analysis/css/list.css');












//check if user is a doctor
//$user = Factory::getUser();
$groups = $user->get('groups');

	$doctormi = FALSE;
	$db_user_group_name = JFactory::getDbo();
	if($user->get('id') > 0){

		//signed in user
		foreach ($groups as $group)
		{
			$query_user_group_name = $db_user_group_name
			    ->getQuery(true)
			    ->select('title')
			    ->from($db_user_group_name->quoteName('#__usergroups'))
			    ->where($db_user_group_name->quoteName('id') . " = " . $db_user_group_name->quote($group));

			$db_user_group_name->setQuery($query_user_group_name);
			$result_user_group_name = $db_user_group_name->loadResult();
			if($result_user_group_name == "Doctor"){
				//echo "bu doctor";die;
				$doctormi = TRUE;
			}
		}

	}











?>

<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">

	<?php echo JLayoutHelper::render('default_filter', array('view' => $this), dirname(__FILE__)); ?>
        <div class="table-responsive">
	<table class="table table-striped" id="analysisList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ANALYSIS_ANALYZES_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ANALYSIS_ANALYZES_EXPLANATION', 'a.explanation', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ANALYSIS_ANALYZES_TYPE_OF_ANALYSIS', 'a.type_of_analysis', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ANALYSIS_ANALYZES_IMAGE', 'a.image', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_ANALYSIS_ANALYZES_DATE', 'a.date', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_ANALYSIS_ANALYZES_ACTIONS'); ?>
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
		<?php 
		//print_r($this->items);//die;
		foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_analysis'); ?>

							<?php if (!$canEdit && $user->authorise('core.edit.own', 'com_analysis')): ?>
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
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'analyzes.', $canCheckin); ?>
				<?php endif; ?>
				<a href="<?php echo JRoute::_('index.php?option=com_analysis&view=analysis&id='.(int) $item->id); ?>">
				<?php echo $this->escape($item->explanation); ?></a>
				</td>
				<td>

					<?php echo $item->type_of_analysis; ?>
				</td>
				<td>

					<?php
					//require_once("../mkarta.uz_protected/image.php");
		require_once("myfunc.php");
						if (!empty($item->image)) :
							$imageArr = (array) explode(',', $item->image);
							foreach ($imageArr as $singleFile) : 
								if (!is_array($singleFile)) :
									$uploadPath = 'pic_ture' . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . $singleFile;
					$filename_protected = JPATH_ROOT . DIRECTORY_SEPARATOR . '../mkarta.uz_protected/images/' . $singleFile;
					$filename_temp = 'pic_ture/temp/' . $singleFile;
					$filename_thumb = 'pic_ture/thumb/' . $singleFile;
					if (!JFile::exists($filename_thumb))
					{
						//echo "<img src=\".$filename_temp.\" alt=\"error\">";
						create_file_with_dir_index_html($filename_thumb);
						create_file_with_dir_index_html($filename_temp);
						make_thumb($filename_protected, $filename_thumb);
						//echo $filename_protected;
						//echo "file exists2";
						//die;
					}else{
						//echo "file does not exists</br>";
						//echo $filename_protected;
						//die;
					}
					
									echo '<img src="'.$filename_thumb.'" alt="'.basename($filename_thumb).'" width="100" height="100">';
								endif;
							endforeach;
						else:
							echo $item->image;
						endif; ?>				</td>
				<td>

					<?php echo $item->date; ?>
					</br>
					<?php 
//					print_r($item);die;
					if($item->recommendation_count > 0):?><a type="button" class="btn btn-success" href="index.php/?option=com_recommendation&aim=tome&id_analysis=<?php echo $item->id; ?>">
					<?php echo $item->recommendation_count . " "; ?><?php 
					if($item->recommendation_count > 1){
						echo Text::_('COM_ANALYSIS_COUNT_OF_RECOMMENDATIONS');
					}elseif($item->recommendation_count = 1){
						echo Text::_('COM_ANALYSIS_COUNT_OF_RECOMMENDATION');
					}
					?></a>
					<?php endif; ?>
				</td>


								<?php //if ($canEdit || $canDelete): ?>
					<td class="center">
						<?php if ($canEdit): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_analysis&task=analysisform.edit&id=' . $item->id, false, 2); ?>" class="btn btn-mini" type="button"><i class="icon-edit" ></i></a>
						<?php endif; ?>
						<?php if ($canDelete): ?>
							<a href="<?php echo JRoute::_('index.php?option=com_analysis&task=analysisform.remove&id=' . $item->id, false, 2); ?>" class="btn btn-mini delete-button" type="button"><i class="icon-trash" ></i></a>
						<?php endif; ?>
						<?php 
						//echo $doctormi;
						if ($doctormi): ?>
						<a href="index.php/?option=com_recommendation&view=recommendationform&id_analysis=<?php echo $item->id; ?>" class="btn btn-default"><?php echo JText::_('COM_ANALYSIS_ADD_RECOMMENDATION'); ?></a>
						<?php endif; ?>
					</td>
				<?php //endif; ?>

			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_analysis&task=analysisform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_ANALYSIS_ADD_ITEM'); ?></a>
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

		if (!confirm("<?php echo Text::_('COM_ANALYSIS_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
