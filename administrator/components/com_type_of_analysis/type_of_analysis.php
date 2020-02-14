<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Type_of_analysis
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_type_of_analysis'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Type_of_analysis', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Type_of_analysisHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'type_of_analysis.php');

$controller = BaseController::getInstance('Type_of_analysis');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
