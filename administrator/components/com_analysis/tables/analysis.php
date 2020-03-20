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

use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Access\Access;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * analysis Table class
 *
 * @since  1.6
 */
class AnalysisTableanalysis extends \Joomla\CMS\Table\Table
{
	
	/**
	 * Constructor
	 *
	 * @param   JDatabase  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		JObserverMapper::addObserverClassToClass('JTableObserverContenthistory', 'AnalysisTableanalysis', array('typeAlias' => 'com_analysis.analysis'));
		parent::__construct('#__analysis', 'id', $db);
        $this->setColumnAlias('published', 'state');
    }

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  Optional array or list of parameters to ignore
	 *
	 * @return  null|string  null is operation was satisfactory, otherwise returns an error
	 *
	 * @see     JTable:bind
	 * @since   1.5
     * @throws Exception
	 */
	public function bind($array, $ignore = '')
	{
	    $date = Factory::getDate();
		$task = Factory::getApplication()->input->get('task');
	    

		if ($array['id'] == 0 && empty($array['created_by']))
		{
			$array['created_by'] = JFactory::getUser()->id;
		}

		// Support for multiple or not foreign key field: type_of_analysis
			if(!empty($array['type_of_analysis']))
			{
				if(is_array($array['type_of_analysis'])){
					$array['type_of_analysis'] = implode(',',$array['type_of_analysis']);
				}
				else if(strrpos($array['type_of_analysis'], ',') != false){
					$array['type_of_analysis'] = explode(',',$array['type_of_analysis']);
				}
			}
			else {
				$array['type_of_analysis'] = '';
			}
		// Support for multi file field: image
		if (!empty($array['image']))
		{
			if (is_array($array['image']))
			{
				$array['image'] = implode(',', $array['image']);
			}
			elseif (strpos($array['image'], ',') != false)
			{
				$array['image'] = explode(',', $array['image']);
			}
		}
		else
		{
			$array['image'] = '';
		}


		// Support for empty date field: date
		if($array['date'] == '0000-00-00' )
		{
			$array['date'] = '';
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		if (!Factory::getUser()->authorise('core.admin', 'com_analysis.analysis.' . $array['id']))
		{
			$actions         = Access::getActionsFromFile(
				JPATH_ADMINISTRATOR . '/components/com_analysis/access.xml',
				"/access/section[@name='analysis']/"
			);
			$default_actions = Access::getAssetRules('com_analysis.analysis.' . $array['id'])->getData();
			$array_jaccess   = array();

			foreach ($actions as $action)
			{
                if (key_exists($action->name, $default_actions))
                {
                    $array_jaccess[$action->name] = $default_actions[$action->name];
                }
			}

			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		// Bind the rules for ACL where supported.
		if (isset($array['rules']) && is_array($array['rules']))
		{
			$this->setRules($array['rules']);
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 *
	 * @param   array  $jaccessrules  An array of JAccessRule objects.
	 *
	 * @return  array
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();

		foreach ($jaccessrules as $action => $jaccess)
		{
			$actions = array();

			if ($jaccess)
			{
				foreach ($jaccess->getData() as $group => $allow)
				{
					$actions[$group] = ((bool)$allow);
				}
			}

			$rules[$action] = $actions;
		}

		return $rules;
	}

	/**
	 * Overloaded check function
	 *
	 * @return bool
	 */
	public function check()
	{
		$data = Factory::getApplication()->input->get('jform', array(), 'array');
		
		        if(array_key_exists('user_picked', $data)) {
        	$boshqa_odamniki = JRequest::getVar('boshqa_odamniki');
	        	if($boshqa_odamniki == 1){
	        		//print_r($data);die;
	        	//if($data["user_picked"] > 1){
				//boshqa_odamniki=1
				//diagnostic center is adding
				
				$this->adder_id = $this->created_by;
				$this->created_by = $data["user_picked"];
				//print_r($this);die;
				
				//echo "boshqa_odamniki";die;
			}
        }else{
			//user adding
			
			///echo "111";die;
		}
		//die;
		
		
		//$this->created_by = 55;
		//print_r($data);die;
		
		
		//require_once("../mkarta.uz_protected/image.php");
		require_once("myfunc.php");
		
		
		
		
		
		// If there is an ordering column and this is a new row then get the next ordering value
		if (property_exists($this, 'ordering') && $this->id == 0)
		{
			$this->ordering = self::getNextOrder();
		}
		
		
		// Support multi file field: image
		$app = JFactory::getApplication();
		$files = $app->input->files->get('jform', array(), 'raw');
		$array = $app->input->get('jform', array(), 'ARRAY');
		
		$user = JFactory::getUser();
		$my_user_id = $user->id;
		

		if ($files['image'][0]['size'] > 0)
		{
			// Deleting existing files
			$oldFiles = AnalysisHelper::getFiles($this->id, $this->_tbl, 'image');

			foreach ($oldFiles as $f)
			{
				$f = '/images/' . $my_yil . '/' . $my_oy . '/' . $my_kun . '/' . $my_user_id . '/' . $my_timestamp . '_' . $f;
				$oldFile = JPATH_ROOT . '/images/' . $f;

				if (file_exists($oldFile) && !is_dir($oldFile))
				{
					unlink($oldFile);
				}
			}

			$this->image = "";

			foreach ($files['image'] as $singleFile )
			{
				jimport('joomla.filesystem.file');

				// Check if the server found any error.
				$fileError = $singleFile['error'];
				$message = '';

				if ($fileError > 0 && $fileError != 4)
				{
					switch ($fileError)
					{
						case 1:
							$message = JText::_('File size exceeds allowed by the server');
							break;
						case 2:
							$message = JText::_('File size exceeds allowed by the html form');
							break;
						case 3:
							$message = JText::_('Partial upload error');
							break;
					}

					if ($message != '')
					{
						$app->enqueueMessage($message, 'warning');

						return false;
					}
				}
				elseif ($fileError == 4)
				{
					if (isset($array['image']))
					{
						$this->image = $array['image'];
					}
				}
				else
				{

					// Replace any special characters in the filename
					jimport('joomla.filesystem.file');
					$filename = JFile::stripExt($singleFile['name']);
					$extension = JFile::getExt($singleFile['name']);
					$filename = preg_replace("/[^A-Za-z0-9]/i", "-", $filename);
					$filename = $filename . '.' . $extension;
					$filename = $my_yil . '/' . $my_oy . '/' . $my_kun . '/' . $my_user_id . '/' . $my_timestamp . '_' . $filename;
					
					//$papka = JPATH_ROOT . DIRECTORY_SEPARATOR . '../mkarta.uz_protected/images/';
					$filename_protected = JPATH_ROOT . DIRECTORY_SEPARATOR . '../mkarta.uz_protected/images/' . $filename;
					$filename_temp = JPATH_ROOT . '/pic_ture/temp/' . $filename;
					$filename_thumb = JPATH_ROOT . '/pic_ture/thumb/' . $singleFile;
					
					$uploadPath = JPATH_ROOT . '/images/' . $filename;
					$fileTemp = $singleFile['tmp_name'];

					if (!JFile::exists($filename_protected))
					{
						if (!JFile::upload($fileTemp, $filename_protected))
						{
							$app->enqueueMessage('Error moving file', 'warning');

							return false;
						}
						//create thumb
						if (!JFile::exists($filename_protected))
					{
						create_file_with_dir_index_html($filename_thumb);
						create_file_with_dir_index_html($filename_temp);
						make_thumb($filename_protected, $filename_thumb);
						//echo "file exists";
						//die;
					}else{
						//make_thumb($filename_protected, $filename_protected);
						//echo "file does not exists</br>";
						//echo $filename_protected;
						//die;
					}
						
						//encode
						//encryptFile($uploadPath, $key, $uploadPath . '.enc');
						//if(JFile::delete($uploadPath)){};
						//$filename = $filename . '.enc';
					}

					$this->image .= (!empty($this->image)) ? "," : "";
					$this->image .= $filename;
				}
			}
		}
		else
		{
			$this->image .= $array['image_hidden'];
		}

		return parent::check();
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.  The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update.  If not
	 *                            set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user id of the user performing the operation.
	 *
	 * @return   boolean  True on success.
	 *
	 * @since    1.0.4
	 *
	 * @throws Exception
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		ArrayHelper::toInteger($pks);
		$userId = (int) $userId;
		$state  = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k)
			{
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else
			{
				throw new Exception(500, Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time'))
		{
			$checkin = '';
		}
		else
		{
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}

		// Update the publishing state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE `' . $this->_tbl . '`' .
			' SET `state` = ' . (int) $state .
			' WHERE (' . $where . ')' .
			$checkin
		);
		$this->_db->execute();

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows()))
		{
			// Checkin each row.
			foreach ($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks))
		{
			$this->state = $state;
		}

		return true;
	}

	/**
	 * Define a namespaced asset name for inclusion in the #__assets table
	 *
	 * @return string The asset name
	 *
	 * @see Table::_getAssetName
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;

		return 'com_analysis.analysis.' . (int) $this->$k;
	}

	/**
	 * Returns the parent asset's id. If you have a tree structure, retrieve the parent's id using the external key field
	 *
	 * @param   JTable   $table  Table name
	 * @param   integer  $id     Id
	 *
	 * @see Table::_getAssetParentId
	 *
	 * @return mixed The id on success, false on failure.
	 */
	protected function _getAssetParentId(JTable $table = null, $id = null)
	{
		// We will retrieve the parent-asset from the Asset-table
		$assetParent = Table::getInstance('Asset');

		// Default: if no asset-parent can be found we take the global asset
		$assetParentId = $assetParent->getRootId();

		// The item has the component as asset-parent
		$assetParent->loadByName('com_analysis');

		// Return the found asset-parent-id
		if ($assetParent->id)
		{
			$assetParentId = $assetParent->id;
		}

		return $assetParentId;
	}

	/**
	 * Delete a record by id
	 *
	 * @param   mixed  $pk  Primary key value to delete. Optional
	 *
	 * @return bool
	 */
	public function delete($pk = null)
	{
		$this->load($pk);
		$result = parent::delete($pk);
		
		if ($result)
		{
			jimport('joomla.filesystem.file');

			$checkImageVariableType = gettype($this->image);

			switch ($checkImageVariableType)
			{
			case 'string':
				JFile::delete(JPATH_ROOT . '/images/' . $this->image);
			break;
			default:
			foreach ($this->image as $imageFile)
			{
				JFile::delete(JPATH_ROOT . '/images/' . $imageFile);
			}
			}
		}

		return $result;
	}
}
