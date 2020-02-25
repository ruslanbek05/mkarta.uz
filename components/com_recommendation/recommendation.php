<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Recommendation
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Recommendation', JPATH_COMPONENT);
JLoader::register('RecommendationController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('Recommendation');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
