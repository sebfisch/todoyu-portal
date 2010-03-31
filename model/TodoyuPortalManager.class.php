<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
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
 * Manager for Portal
 *
 * @package		Todoyu
 * @subpackage	Portal
 */
class TodoyuPortalManager {

	/**
	 * Add a tab to portal view
	 *
	 * @param	String		$key			Key of the tab
	 * @param	String		$labelFunc		Function which renders the label
	 * @param	String		$contentFunc	Function which renders the content
	 * @param	Integer		$position		Tab position (left to right)
	 * @param	Array		$assets			Assets to load. List with format: portal/public, calendar/public, ...
	 */
	public static function addTab($key, $labelFunc, $contentFunc, $position = 100, array $assets = array()) {
		Todoyu::$CONFIG['EXT']['portal']['tabs'][$key] = array(
			'key'			=> $key,
			'labelFunc'		=> $labelFunc,
			'contentFunc'	=> $contentFunc,
			'position'		=> intval($position),
			'assets'		=> $assets
		);
	}



	/**
	 * Get config of all added tabs sorted by position
	 *
	 * @return	Array
	 */
	public static function getTabsConfig() {
		return TodoyuArray::sortByLabel(Todoyu::$CONFIG['EXT']['portal']['tabs'], 'position');
	}



	/**
	 * Get config of a tab
	 *
	 * @param	String		$tabKey
	 * @return	Array
	 */
	public static function getTabConfig($tabKey) {
		return Todoyu::$CONFIG['EXT']['portal']['tabs'][$tabKey];
	}



	/**
	 * Get tabs config
	 *
	 * @return	Array
	 */
	public static function getTabs() {
		$tabs	= self::getTabsConfig();

			// Get label, content list counter, 'active' or not-state
		foreach($tabs as $index => $tab) {
			$tabs[$index]['id']		= $tab['key'];
			$tabs[$index]['label']	= TodoyuFunction::callUserFunction($tab['labelFunc'], true);
		}

		return $tabs;
	}



	/**
	 * Add items to task context menu
	 *
	 * @param	Integer		$idTask
	 * @param	Array		$items
	 * @return	Array
	 */
	public static function getTaskContextMenuItems($idTask, array $items) {
		$idTask	= intval($idTask);

			// Only show it in portal
		if( AREA === EXTID_PORTAL ) {
				// Add special portal items
			$ownItems	= Todoyu::$CONFIG['EXT']['portal']['ContextMenu']['Task'];
			$items		= array_merge_recursive($items, $ownItems);

				// Remove clone function
			unset($items['actions']['submenu']['clone']);

				// Remove add function for task and container
			unset($items['add']['submenu']['task']);
			unset($items['add']['submenu']['container']);
		}

		return $items;
	}



	/**
	 * Get number of result items for selection tab with currently selected filtersets
	 *
	 * @return	Integer
	 */
	public static function getSelectionCount() {
		$filtersetIDs	= TodoyuPortalPreferences::getSelectionTabFiltersetIDs();
		$numResults		= TodoyuFiltersetManager::getFiltersetsCount($filtersetIDs);

		return $numResults;
	}



	/**
	 * Get type of currently selected filtersets
	 *
	 * @return	String
	 */
	public static function getSelectionType() {
// @todo	check $filtersetIDs needed? method used still?
		$idFilterset	= 0; //intval($filtersetIDs[0]);

		return TodoyuFiltersetManager::getFiltersetType($idFilterset);
	}

}

?>