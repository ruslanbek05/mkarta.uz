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










		use Joomla\CMS\Factory;
		$can_see = FALSE;
		$user = Factory::getUser();
		$groups = $user->get('groups');
		$selecteduser = JRequest::getVar('selecteduser');
		//print_r($groups);die;
		
		

			//$selecteduser given. check if user a doctor
			$db_user_group_name = JFactory::getDbo();
			if($user->get('id') > 0){
				//signed in user
				$doctormi = FALSE;
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
					//print_r($result_user_group_name);die;
					//echo $result_user_group_name['title'];die;
					if($result_user_group_name == "Doctor"){
						//echo "bu doctor";die;
						$doctormi = TRUE;
						//$query->where("a.created_by = '".$db->escape($selecteduser)."'");
					}
				}
				if($doctormi){
					//doctor
					//$query->where("a.created_by = '".$db->escape($selecteduser)."'");
					$can_see = TRUE;
				}else{
					//doctor emas
					//null. only own
					if($this->item->created_by == $user->get('id')){
					$can_see = TRUE;
					}
				}
			}
			else{
				//not signed in user
				//null. only own
				//$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
				$can_see = FALSE;
			}
			














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
			<td><?php 
			if($can_see == TRUE){
				echo nl2br($this->item->explanation);
				} ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_TYPE_OF_ANALYSIS'); ?></th>
			<td><?php 
			if($can_see == TRUE){
				echo $this->item->type_of_analysis;
				}
				 ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_IMAGE'); ?></th>
			<td>
			<?php
			require_once("myfunc.php");
			
				
			if($can_see == TRUE){
			foreach ((array) $this->item->image as $singleFile) : 
				if (!is_array($singleFile)) : 
					$uploadPath = 'images' . DIRECTORY_SEPARATOR . $singleFile;
					
					$filename_protected = JPATH_ROOT . DIRECTORY_SEPARATOR . '../mkarta.uz_protected/images/' . $singleFile;
					$filename_temp = 'pic_ture/temp/' . $singleFile;
					if (!JFile::exists($filename_temp))
					{
						create_file_with_dir_index_html($filename_temp);
						JFile::copy($filename_protected, $filename_temp);
					}
					echo '<img src="'.$filename_temp.'" alt="'.basename($filename_temp).'">';
					
					 //echo '<a href="' . JRoute::_(JUri::root() . $uploadPath, false) . '" target="_blank">' . $singleFile . '</a> ';
				endif;
			endforeach;
			}
		?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_ANALYSIS_FORM_LBL_ANALYSIS_DATE'); ?></th>
			<td><?php if($can_see == TRUE){
				echo $this->item->date;
				} ?></td>
		</tr>

	</table>

</div>

<script>
jQuery(document).ready(function(){
  jQuery("button").click(function(){
  	//alert("Hello! I am an alert box!!");
    //jQuery("#div1").load("http://localhost/mkarta.uz/demo_test.txt");
    //jQuery("#div1").load("http://localhost/mkarta.uz/index.php/ru/?option=com_analysis&task=analysis.aajax");
    jQuery("#div1").load("<?php echo JUri::base();?>index.php/ru/?option=com_analysis&task=analysis.aajax");
    
  });
});
</script>

<div id="div1"><h2>Let jQuery AJAX Change This Text</h2></div>

<button>Get External Content</button>




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