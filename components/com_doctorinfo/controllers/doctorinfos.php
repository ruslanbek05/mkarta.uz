<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Doctorinfo
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2020 ruslan qodirov
 * @license    GNU General Public License версии 2 или более поздней; Смотрите LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Doctorinfos list controller class.
 *
 * @since  1.6
 */
class DoctorinfoControllerDoctorinfos extends DoctorinfoController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Doctorinfos', $prefix = 'DoctorinfoModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}
}
