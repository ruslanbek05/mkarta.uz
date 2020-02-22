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

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_doctorinfo'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Doctorinfo', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('DoctorinfoHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'doctorinfo.php');

$controller = BaseController::getInstance('Doctorinfo');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
