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

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_analysis', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_analysis/js/form.js');

$user    = Factory::getUser();
$canEdit = AnalysisHelpersAnalysis::canUserEdit($this->item, $user);


$boshqa_odamniki = JRequest::getVar('boshqa_odamniki');
//echo $boshqa_odamniki;die;

?>

<div class="analysis-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(Text::_('COM_ANALYSIS_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo Text::sprintf('COM_ANALYSIS_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo Text::_('COM_ANALYSIS_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-analysis"
			  action="<?php 
			  if($boshqa_odamniki == 1){
			  		echo Route::_('index.php?option=com_analysis&task=analysis.save&boshqa_odamniki=1');
			  	}else{
					echo Route::_('index.php?option=com_analysis&task=analysis.save');
				}
			  	 ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
			
	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
	
	<?php 
	if($boshqa_odamniki == 1){
		//echo $boshqa_odamniki;die;
		echo $this->form->renderField('user_picked'); 
	}
	
	?>
	
	<?php echo $this->form->renderField('explanation'); ?>

	<?php echo $this->form->renderField('type_of_analysis'); ?>

	<?php foreach((array)$this->item->type_of_analysis as $value): ?>
		<?php if(!is_array($value)): ?>
			<input type="hidden" class="type_of_analysis" name="jform[type_of_analysishidden][<?php echo $value; ?>]" value="<?php echo $value; ?>" />
		<?php endif; ?>
	<?php endforeach; ?>
	<?php echo $this->form->renderField('image'); ?>

				<?php if (!empty($this->item->image)) : ?>
					<?php $imageFiles = array(); ?>
					<?php foreach ((array)$this->item->image as $fileSingle) : ?>
						<?php if (!is_array($fileSingle)) : ?>
							<a href="<?php echo JRoute::_(JUri::root() . 'images' . DIRECTORY_SEPARATOR . $fileSingle, false);?>"><?php echo $fileSingle; ?></a> | 
							<?php $imageFiles[] = $fileSingle; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<input type="hidden" name="jform[image_hidden]" id="jform_image_hidden" value="<?php echo implode(',', $imageFiles); ?>" />
				<?php endif; ?>
	<?php echo $this->form->renderField('date'); ?>

	

			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary" id="submit_button">
							<?php echo Text::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo Route::_('index.php?option=com_analysis&task=analysisform.cancel'); ?>"
					   title="<?php echo Text::_('JCANCEL'); ?>">
						<?php echo Text::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_analysis"/>
			<input type="hidden" name="task"
				   value="analysisform.save"/>
			<?php echo HTMLHelper::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
<script>
		jQuery(document).ready(function () {
			
let searchParams = new URLSearchParams(window.location.search);

if(searchParams.has('boshqa_odamniki')){
	//alert("boshqa_odamniki bor ");

			
			
let optionsLength = jQuery(this).children("option:selected").length;
//alert("You have selected the country - " + optionsLength);

var selectedCountry = jQuery(this).children("option:selected").val();
//alert("You have selected the country - " + selectedCountry);

if(optionsLength == 0){
	//alert("There are no options ");
	jQuery("#submit_button").attr("disabled", "disabled");
}
			
		
	jQuery("select").change(function(){
        var selectedCountry = jQuery(this).children("option:selected").val();
        let optionsLength = jQuery(this).children("option:selected").length;
        //alert("You have selected the country - " + selectedCountry);
        if(optionsLength > 0){
			//alert("There are no options ");
			jQuery("#submit_button").removeAttr("disabled");
		}

		if(selectedCountry == 0){
			//alert("There are no options ");
			jQuery("#submit_button").attr("disabled", "disabled");
		}
    });
    
			
			//if(jQuery("#jform_user_picked_chzn > a > span").text()=="")
			
			//alert("Text: " + jQuery("#jform_user_picked_chzn > a > span").text());
		
		//jQuery("#jform_user_picked").change(function(){
  		//alert("Text: " + jQuery("#jform_user_picked_chzn > a > span").text());
//});


}else{
	//alert("boshqa_odamniki bor yoq");
}
		
	});
		
</script>