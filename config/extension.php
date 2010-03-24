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
 * General configuration for portal extension
 *
 * @package		Todoyu
 * @subpackage	Admin
 */

	// Add portal tab: 'selection'
TodoyuPortalManager::addTab('selection', 'TodoyuPortalRenderer::getSelectionTabLabel', 'TodoyuPortalRenderer::renderSelectionTabContent', 10, array('portal/public'));



	// Register context menu functions
TodoyuContextMenuManager::registerFunction('Task', 'TodoyuPortalManager::getTaskContextMenuItems', 110);



/*
Todoyu::$CONFIG['EXT']['portal']['renderer']['panel'][]		= 'TodoyuPortalRenderer::renderPanelWidgets';
Todoyu::$CONFIG['EXT']['portal']['renderer']['tabs'][]		= 'TodoyuPortalRenderer::renderTabs';
Todoyu::$CONFIG['EXT']['portal']['renderer']['tasklist'][]	= 'TodoyuPortalRenderer::renderTaskList';

Todoyu::$CONFIG['EXT']['portal']['typetab']['task']			= 'filtered';
Todoyu::$CONFIG['EXT']['portal']['typerenderer']['task']	= 'TodoyuPortalRenderer::renderTaskTab';
Todoyu::$CONFIG['EXT']['portal']['entriescounter']['task']	= 'TodoyuPortalManager::getTasksAmount';

Todoyu::$CONFIG['EXT']['portal']['tabfiltered'] = array(
	'id'		=> 0,
	'type'		=> 'task',
	'id_person'	=> 0,
	'class'		=> 'filtered',
	'title'		=> 'LLL:portal.tab.selection',
	'is_or'		=> 0,
	'sorting'	=> 0
);
*/

?>