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
$canCreate  = $user->authorise('core.create', 'com_user_list') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'listform.xml');
$canEdit    = $user->authorise('core.edit', 'com_user_list') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'listform.xml');
$canCheckin = $user->authorise('core.manage', 'com_user_list');
$canChange  = $user->authorise('core.edit.state', 'com_user_list');
$canDelete  = $user->authorise('core.delete', 'com_user_list');

// Import CSS
$document = Factory::getDocument();
$document->addStyleSheet(Uri::root() . 'media/com_user_list/css/list.css');













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
	<table class="table table-striped" id="listList">
		<thead>
		<tr>
			<?php if (isset($this->items[0]->state)): ?>
				
			<?php endif; ?>

							<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_USER_LIST_LISTS_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_USER_LIST_LISTS_AVATAR', 'a.avatar', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_USER_LIST_LISTS_USERNAME', 'a.username', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_USER_LIST_LISTS_YEAR_OF_BIRTH', 'a.year_of_birth', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_USER_LIST_LISTS_GENDER', 'a.gender', $listDirn, $listOrder); ?>
				</th>
				<th class=''>
				<?php echo JHtml::_('grid.sort',  'COM_USER_LIST_LISTS_VIEW_HEALTH_INFO', 'a.view_health_info', $listDirn, $listOrder); ?>
				</th>


							<?php if ($canEdit || $canDelete): ?>
					<th class="center">
				<?php echo JText::_('COM_USER_LIST_LISTS_ACTIONS'); ?>
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
		if($doctormi){
			
		
		//print_r($this->items);die;
		foreach ($this->items as $i => $item) : ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_user_list'); ?>

			
			<tr class="row<?php echo $i % 2; ?>">

				<?php if (isset($this->items[0]->state)) : ?>
					<?php $class = ($canChange) ? 'active' : 'disabled'; ?>
					
				<?php endif; ?>

								<td>

					<?php echo $item->id; ?>
				</td>
				<td>
				<?php if (isset($item->checked_out) && $item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'lists.', $canCheckin); ?>
				<?php endif; ?>
				
				<?php 
				//link to detail
				//echo JRoute::_('index.php?option=com_user_list&view=list&id='.(int) $item->id);
				require_once JPATH_PLUGINS . '/user/cmavatar/helper.php';
				$avatar = PlgUserCMAvatarHelper::getAvatar($item->id);
				if($avatar == NULL){
					//echo "nullll";die;
					echo '<img src="images/avatars/empty.png"/>';
				}else{
					echo '<img src="'.$avatar.'"/>';
				}
				
				
				//echo $avatar;die;
				//echo $this->escape($item->avatar); 
				?>
				</td>
				<td>

					<?php echo $item->username; ?>
				</td>
				<td>

					<?php echo $item->year_of_birth; ?>
				</td>
				<td>

					<?php echo $item->gender; ?>
				</td>
				<td>
					<a href="index.php/?option=com_analysis&selecteduser=<?php echo $item->id; ?>" class="btn btn-default"><?php echo JText::_('COM_USER_LIST_HEALTH_HISTORY_BUTTON'); ?></a>
					<?php //echo $item->view_health_info; ?>
				</td>


								<?php if ($canEdit || $canDelete): ?>
					<td class="center">
					</td>
				<?php endif; ?>

			</tr>
		<?php endforeach; 
		}?>
		</tbody>
	</table>
        </div>
	<?php if ($canCreate) : ?>
		<a href="<?php echo Route::_('index.php?option=com_user_list&task=listform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo Text::_('COM_USER_LIST_ADD_ITEM'); ?></a>
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

		if (!confirm("<?php echo Text::_('COM_USER_LIST_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
