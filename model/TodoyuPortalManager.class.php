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
		$GLOBALS['CONFIG']['EXT']['portal']['tabs'][$key] = array(
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
		return TodoyuArray::sortByLabel($GLOBALS['CONFIG']['EXT']['portal']['tabs'], 'position');
	}



	/**
	 * Get config of a tab
	 *
	 * @param	String		$tabKey
	 * @return	Array
	 */
	public static function getTabConfig($tabKey) {
		return $GLOBALS['CONFIG']['EXT']['portal']['tabs'][$tabKey];
	}



	/**
	 * Add the assets of the tabs to the page
	 *
	 */
	public static function addTabAssetsToPage() {
		$tabs	= self::getTabsConfig();
		$assets	= array();

		foreach($tabs as $tab) {
			$assets	= array_merge($assets, $tab['assets']);
		}

		$assets	= array_unique($assets);

		foreach($assets as $asset) {
			$config	= explode('/', $asset);
			TodoyuPage::addExtAssets($config[0], $config[1]);
		}

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
			$tabs[$index]['id']				= $tab['key'];
			$tabs[$index]['htmlId'] 		= 'portal-tabhead-' . $tab['key'];
			$tabs[$index]['label']			= TodoyuDiv::callUserFunction($tab['labelFunc']);
			$tabs[$index]['classKey'] 		= $tab['key'];
			$tabs[$index]['hasIcon'] 		= true;
		}

		return $tabs;
	}



	/**
	 * Get task IDs which match the selected filters
	 * Conjunction is OR
	 *
	 * @return	Array
	 */
	public static function getSelectionTaskIDs() {
		$filtersetIDs		= TodoyuPortalPreferences::getSelectionTabFiltersetIDs();
		$filtersetTaskIDs	= array();
		$sorting			= '	ext_project_task.date_deadline,
								ext_project_task.date_end';

			// Get conditions for each filterset and search for the tasks
		foreach($filtersetIDs as $idFilterset) {
			$conditions			= TodoyuFiltersetManager::getFiltersetConditions($idFilterset);
			$taskFilter			= new TodoyuTaskFilter($conditions);
			$filtersetTaskIDs[]	= $taskFilter->getTaskIDs($sorting);
		}

			// Depending on how much filtersets are linked to the task, combine the results by conjuction config
		if( sizeof($filtersetTaskIDs) === 0 ) {
			$taskIDs= array();
		} elseif( sizeof($filtersetTaskIDs) === 1 ) {
			$taskIDs= array_pop($filtersetTaskIDs);
		} else {
			$taskIDs= array_unique(TodoyuArray::mergeSubArrays($filtersetTaskIDs));
		}

		return $taskIDs;
	}



	/**
	 * Get task IDs for todo tab
	 *
	 * @return	Array
	 */
	public static function getTodoTaskIDs() {
		$conditions	= $GLOBALS['CONFIG']['EXT']['portal']['todoTabFilters'];
		$taskFilter	= new TodoyuTaskFilter($conditions);
		$taskIDs	= $taskFilter->getTaskIDs();

		return $taskIDs;
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
			$ownItems	= $GLOBALS['CONFIG']['EXT']['portal']['ContextMenu']['Task'];
			$items		= array_merge_recursive($items, $ownItems);

				// Remove clone function
			unset($items['actions']['submenu']['clone']);

				// Remove add function for task and container
			unset($items['add']['submenu']['task']);
			unset($items['add']['submenu']['container']);


				// Change behavour of change status to project js
			if( array_key_exists('status', $items) ) {
				foreach($items['status']['submenu'] as $statusName => $subItemConfig) {
					$items['status']['submenu'][$statusName]['jsAction'] = str_replace('Ext.project.Task', 'Ext.portal.Task', $subItemConfig['jsAction']);
				}
			}
		}

		return $items;
	}



	/**
	 * Remove "add" parent menu, if no subitems are in there
	 *
	 * @param	Integer		$idTask
	 * @param	Array		$items
	 * @return	Array
	 */
	public static function removeAddMenuIfEmpty($idTask, array $items) {
		if( is_array($items['add']['submenu']) ) {
			if( sizeof($items['add']['submenu']) === 0 ) {
				unset($items['add']);
			}
		}

		return $items;
	}



	/**
	 * Modify task form if task is edited in portal
	 * Change form action and button actions
	 *
	 * @param	TodoyuForm		$form
	 * @param	Integer			$idTask
	 */
	public static function modifyTaskForm(TodoyuForm $form, $idTask) {
		if( AREA === EXTID_PORTAL ) {
			$formUrl = TodoyuDiv::buildUrl(array(
				'ext'		=> 'portal',
				'controller'=> 'task'
			));

			$form->setAttribute('action', $formUrl);
			$form->getField('save')->setAttribute('onclick', 'Todoyu.Ext.portal.Task.saveTask(this.form)');
			$form->getField('cancel')->setAttribute('onclick', 'Todoyu.Ext.portal.Task.cancelEdit(' . $idTask . ')');
		}
	}

}

?>