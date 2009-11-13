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



	// Declare ext ID, path
define('EXTID_PORTAL',	111);
define('PATH_EXT_PORTAL', PATH_EXT . '/portal');

	// Register module locales
TodoyuLocale::register('portal', PATH_EXT_PORTAL . '/locale/ext.xml');
TodoyuLocale::register('panelwidget-filterpresetlist', PATH_EXT_PORTAL . '/locale/panelwidget-filterpresetlist.xml');
TodoyuLocale::register('panelwidget-quicktaskwizard', PATH_EXT_PORTAL . '/locale/panelwidget-quicktaskwizard.xml');

	// Request configurations
require_once( PATH_EXT_PORTAL . '/config/constants.php' );
require_once( PATH_EXT_PORTAL . '/config/extension.php' );
require_once( PATH_EXT_PORTAL . '/config/panelwidgets.php' );
require_once( PATH_EXT_PORTAL . '/model/dwoo-plugins.php');

	// Add assets
TodoyuPage::addExtAssets('portal', 'public');

	// Add menu entries
if( TodoyuAuth::isLoggedIn() ) {
	TodoyuFrontend::setDefaultTab('portal');
	TodoyuFrontend::addMenuEntry('portal', 'LLL:portal.tab', '?ext=portal', 10);
	TodoyuFrontend::addSubmenuEntry('portal', 'portal', 'LLL:portal.submenuentry.selection', '?ext=portal&tab=' . PORTAL_TABID_SELECTION, 50);
	TodoyuFrontend::addSubmenuEntry('portal', 'portal', 'LLL:portal.submenuentry.todos', '?ext=portal&tab=' . PORTAL_TABID_TODOS, 55);
	TodoyuFrontend::addSubmenuEntry('portal', 'portal', 'LLL:portal.submenuentry.feedbacks', '?ext=portal&tab=' . PORTAL_TABID_FEEDBACKS, 60);
	TodoyuFrontend::addSubmenuEntry('portal', 'portal', 'LLL:portal.submenuentry.appointments', '?ext=portal&tab=' . PORTAL_TABID_APPOINTMENTS, 65);
}

?>