<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

	// Declare ext ID, path
define('EXTID_PORTAL',	111);
define('PATH_EXT_PORTAL', PATH_EXT . '/portal');

require_once( PATH_EXT_PORTAL . '/config/constants.php' );

	// Register module locales
TodoyuLabelManager::register('portal', 'portal', 'ext.xml');
TodoyuLabelManager::register('panelwidget-filterpresetlist', 'portal', 'panelwidget-filterpresetlist.xml');

	// Register context menu functions
TodoyuContextMenuManager::addFunction('Task', 'TodoyuPortalManager::getTaskContextMenuItems', 110);
	// Hooks adds portal sub menu entries in sub navigation
TodoyuHookManager::registerHook('core', 'renderPage', 'TodoyuPortalManager::hookRenderPage');

?>