<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_User_list
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Controller\BaseController;

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('User_list', JPATH_COMPONENT);
JLoader::register('User_listController', JPATH_COMPONENT . '/controller.php');


// Execute the task.
$controller = BaseController::getInstance('User_list');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
