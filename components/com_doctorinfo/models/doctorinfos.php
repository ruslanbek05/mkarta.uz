<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Doctorinfo
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Doctorinfo records.
 *
 * @since  1.6
 */
class DoctorinfoModelDoctorinfos extends \Joomla\CMS\MVC\Model\ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'created_by', 'a.created_by',
				'date_data_added', 'a.date_data_added',
				'file', 'a.file',
				'additional_information', 'a.additional_information',
			);
		}

		parent::__construct($config);
	}

        
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
            
           
            
        // List state information.

        parent::populateState("a.id", "ASC");

        $context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $context);

        // Split context into component and optional section
        $parts = FieldsHelper::extract($context);

        if ($parts)
        {
            $this->setState('filter.component', $parts[0]);
            $this->setState('filter.section', $parts[1]);
        }
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
            // Create a new query object.
            $db    = $this->getDbo();
            $query = $db->getQuery(true);

            // Select the required fields from the table.
            $query->select(
                        $this->getState(
                                'list.select', 'DISTINCT a.*'
                        )
                );

            $query->from('`#__doctorinfo` AS a');
            

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
            

            // Filter by search in title
            $search = $this->getState('filter.search');

            if (!empty($search))
            {
                if (stripos($search, 'id:') === 0)
                {
                    $query->where('a.id = ' . (int) substr($search, 3));
                }
                else
                {
                    $search = $db->Quote('%' . $db->escape($search, true) . '%');
                }
            }
            
            
            
            
            
            
            
            
            
            
 
            
            
		$user = Factory::getUser();
		$groups = $user->get('groups');
		$Managermi = FALSE;
		
		//check if user a manager
		$db_user_group_name = JFactory::getDbo();
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
					if($result_user_group_name == "Manager"){
						//echo "bu doctor";die;
						$Managermi = TRUE;
						//$query->where("a.created_by = '".$db->escape($selecteduser)."'");
					}
				}
				
		$selecteduser = JRequest::getVar('selecteduser');
		//print_r($groups);die;
		
		
		if ($selecteduser<>null) {
			//$selecteduser given.
			if($user->get('id') > 0){
				//signed in user
				

				if($Managermi){
					//doctor
					$query->where("a.created_by = '".$db->escape($selecteduser)."'");
					//echo "manager";
				}else{
					//manager emas
					//null. show selected
					//$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
					$query->where("a.created_by = '".$db->escape($selecteduser)."'");
					//echo "any user";
				}
			}
			else{
				//not signed in user
				//null. only own
				//$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
				$query->where("a.created_by = 0");	
				//echo "not signed in";
			}
		}else{
			//$selecteduser not given.
			//null. only own
			//$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
			if($Managermi){
					//doctor
					//echo "manager";
				}else{
					//manager emas
					//null. show selected
					$query->where("a.created_by = '".$db->escape($user->get('id'))."'");	
					//echo "faqat uziniki";
				}
		}
		
		
		
		            
            
            
            
            
            
            
            
            

            // Add the list ordering clause.
            $orderCol  = $this->state->get('list.ordering', "a.id");
            $orderDirn = $this->state->get('list.direction', "DESC");

            if ($orderCol && $orderDirn)
            {
                $query->order($db->escape($orderCol . ' ' . $orderDirn));
            }

            return $query;
	}

	/**
	 * Method to get an array of data items
	 *
	 * @return  mixed An array of data on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
		

		return $items;
	}

	/**
	 * Overrides the default function to check Date fields format, identified by
	 * "_dateformat" suffix, and erases the field if it's not correct.
	 *
	 * @return void
	 */
	protected function loadFormData()
	{
		$app              = Factory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && $this->isValidDate($value) == null)
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(Text::_("COM_DOCTORINFO_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in a specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Date to be checked
	 *
	 * @return bool
	 */
	private function isValidDate($date)
	{
		$date = str_replace('/', '-', $date);
		return (date_create($date)) ? Factory::getDate($date)->format("Y-m-d") : null;
	}
}
