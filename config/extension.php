<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
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

/**
 * General configuration for portal extension
 *
 * @package		Todoyu
 * @subpackage	Admin
 */

	// Register context menu functions
TodoyuContextMenuManager::addFunction('Task', 'TodoyuPortalManager::getTaskContextMenuItems', 110);



/*
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