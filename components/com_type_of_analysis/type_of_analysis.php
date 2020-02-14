<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Type_of_analysis
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Type_of_analysis', JPATH_COMPONENT);
JLoader::register('Type_of_analysisController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Type_of_analysis');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
