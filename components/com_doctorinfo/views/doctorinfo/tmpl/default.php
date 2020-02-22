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










use \Joomla\CMS\Factory;
		$user = Factory::getUser();
		$groups = $user->get('groups');
		
			$Managermi = FALSE;
			$db_user_group_name = JFactory::getDbo();
			if($user->get('id') > 0){
				//signed in user
				
				foreach ($groups as $group)
				{
				    //echo '<p>Group = ' . $group . '</p>';
					$query_user_group_name = $db_user_group_name
					    ->getQuery(true)
					    ->select('title')
					    ->from($db_user_group_name->quoteName('#__usergroups'))
					    ->where($db_user_group_name->quoteName('id') . " = " . $db_user_group_name->quote($group));

					$db_user_group_name->setQuery($query_user_group_name);
					$result_user_group_name = $db_user_group_name->loadResult();

					if($result_user_group_name == "Manager"){
						//echo "bu doctor";die;
						$Managermi = TRUE;

					}
				}
				
			}
			
		













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
			require_once("myfunc.php");
			foreach ((array) $this->item->file as $singleFile) : 
				if (!is_array($singleFile)) : 
					$uploadPath = 'uploads' . DIRECTORY_SEPARATOR . $singleFile;
					
					$filename_constant = 'pic_ture/docinfo/' . $singleFile;
					echo '<img src="'.$filename_constant.'" alt="'.basename($filename_constant).'">';
					
					 //echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
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

<?php
//print_r($this->item->created_by);die;
 if($Managermi): 
 			$doctorga_qushilmaganmi = FALSE;
			// Get a db connection.
$db = JFactory::getDbo();

// Create a new query object.
$query = $db->getQuery(true);

// Select all articles for users who have a username which starts with 'a'.
// Order it by the created date.
// Note by putting 'a' as a second parameter will generate `#__content` AS `a`
$query
    ->select(array('a.*', 'b.title'))
    ->from($db->quoteName('#__user_usergroup_map', 'a'))
    ->join('INNER', $db->quoteName('#__usergroups', 'b') . ' ON ' . $db->quoteName('a.group_id') . ' = ' . $db->quoteName('b.id'))
    ->where($db->quoteName('a.user_id') . " = " . $this->item->created_by)
    ->where($db->quoteName('b.title') . " = 'Doctor'");

// Reset the query using our newly populated query object.
$db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$row = $db->loadAssoc();
//print_r($row['title']);die;
if($row['title'] == "Doctor"){
	$doctorga_qushilmaganmi = TRUE;
}
				
				
if(!$doctorga_qushilmaganmi):	
?>
 	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_doctorinfo&task=doctorinfo.add_to_doctor&id='.$this->item->created_by); ?>"><?php echo "add to doctor"; ?></a>

<?php endif; ?>
<?php endif; ?>

