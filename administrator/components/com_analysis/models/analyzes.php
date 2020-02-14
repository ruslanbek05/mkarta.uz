<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Analysis
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Analysis records.
 *
 * @since  1.6
 */
class AnalysisModelAnalyzes extends \Joomla\CMS\MVC\Model\ListModel
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
				'id', 'a.`id`',
				'created_by', 'a.`created_by`',
				'explanation', 'a.`explanation`',
				'type_of_analysis', 'a.`type_of_analysis`',
				'image', 'a.`image`',
				'date', 'a.`date`',
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
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
                    return parent::getStoreId($id);
                
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
		$query->from('`#__analysis` AS a');
                

		// Join over the user field 'created_by'
		$query->select('`created_by`.name AS `created_by`');
		$query->join('LEFT', '#__users AS `created_by` ON `created_by`.id = a.`created_by`');
		// Join over the foreign key 'type_of_analysis'
		$query->select('`#__type_of_analysis_3387938`.`type_of_analysis_ru` AS #__type_of_analysis_fk_value_3387938');
		$query->join('LEFT', '#__type_of_analysis AS #__type_of_analysis_3387938 ON #__type_of_analysis_3387938.`id` = a.`type_of_analysis`');
                

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
				$query->where('( a.explanation LIKE ' . $search . '  OR #__type_of_analysis_3387938.type_of_analysis_ru LIKE ' . $search . ' )');
			}
		}
                

		// Filtering type_of_analysis
		$filter_type_of_analysis = $this->state->get("filter.type_of_analysis");

		if ($filter_type_of_analysis !== null && !empty($filter_type_of_analysis))
		{
			$query->where("a.`type_of_analysis` = '".$db->escape($filter_type_of_analysis)."'");
		}

		// Filtering date
		$filter_date_from = $this->state->get("filter.date.from");

		if ($filter_date_from !== null && !empty($filter_date_from))
		{
			$query->where("a.`date` >= '".$db->escape($filter_date_from)."'");
		}
		$filter_date_to = $this->state->get("filter.date.to");

		if ($filter_date_to !== null  && !empty($filter_date_to))
		{
			$query->where("a.`date` <= '".$db->escape($filter_date_to)."'");
		}
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', "a.id");
		$orderDirn = $this->state->get('list.direction', "ASC");

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
                
		foreach ($items as $oneItem)
		{

			if (isset($oneItem->type_of_analysis))
			{
				$values    = explode(',', $oneItem->type_of_analysis);
				$textValue = array();

				foreach ($values as $value)
				{
					$db    = JFactory::getDbo();
					$query = $db->getQuery(true);
					$query
						->select('`#__type_of_analysis_3387938`.`type_of_analysis_ru`')
						->from($db->quoteName('#__type_of_analysis', '#__type_of_analysis_3387938'))
						->where($db->quoteName('#__type_of_analysis_3387938.id') . ' = '. $db->quote($db->escape($value)));

					$db->setQuery($query);
					$results = $db->loadObject();

					if ($results)
					{
						$textValue[] = $results->type_of_analysis_ru;
					}
				}

				$oneItem->type_of_analysis = !empty($textValue) ? implode(', ', $textValue) : $oneItem->type_of_analysis;
			}
		}

		return $items;
	}
}
