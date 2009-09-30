<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 snowflake productions gmbh
*  All rights reserved
*
*  This script is part of the todoyu project.
*  The todoyu project is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License, version 2, 
*  (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html) as published by
*  the Free Software Foundation;
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Extension main file for portal extension
 *
 * @package		Todoyu
 * @subpackage	Portal
 */

if( ! defined('TODOYU') ) die('NO ACCESS');


	// declare ext ID, path
define('EXTID_PORTAL',	111);
define('PATH_EXT_PORTAL', PATH_EXT . '/portal');

	// request configurations, dwoo plugins
require_once( PATH_EXT_PORTAL . '/config/constants.php' );
require_once( PATH_EXT_PORTAL . '/config/extension.php' );
require_once( PATH_EXT_PORTAL . '/config/panelwidgets.php' );

require_once( PATH_EXT_PORTAL . '/model/dwoo-plugins.php');

	// register localization files
TodoyuLocale::register('portal', PATH_EXT_PORTAL . '/locale/ext.xml');

	// register implied widgets' localization files
TodoyuLocale::register('panelwidget-filterpresetlist', PATH_EXT_PORTAL . '/locale/panelwidget-filterpresetlist.xml');
TodoyuLocale::register('panelwidget-quicktaskwizard', PATH_EXT_PORTAL . '/locale/panelwidget-quicktaskwizard.xml');

	// add extension assets
TodoyuPage::addExtAssets('portal', 'public');


if( TodoyuAuth::isLoggedIn() ) {
		// add menu entry
	TodoyuFrontend::setDefaultTab('portal');
	TodoyuFrontend::addMenuEntry('portal', 'LLL:portal.tab', '?ext=portal', 10);
}


?>