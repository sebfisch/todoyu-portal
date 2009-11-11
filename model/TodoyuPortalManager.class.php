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
 * @name 		Portal Manager
 * @package		Todoyu
 * @subpackage	Portal
 *
 */

class TodoyuPortalManager {

	/**
	 * Get tab record
	 *
	 * @param	Integer		$idTab
	 * @return	Array
	 */
	public static function getTab($idTab) {
		$idTab	= intval($idTab);

		if( $idTab === 0 ) {
			$tab	= self::getFilterTabConfig();
		} else {
			$table	= 'ext_portal_tab';
			$tab	= Todoyu::db()->getRecord($table, $idTab);
		}

		return $tab;
	}



	/**
	 * Get configuration array of the filter tab
	 * The filter tab is defined in config, not in database
	 *
	 * @return	Array
	 */
	public static function getFilterTabConfig() {
		return $GLOBALS['CONFIG']['EXT']['portal']['tabfiltered'];
	}



	/**
	 * Get the type of a tab
	 *
	 * @param	Integer		$idTab
	 * @return	String
	 */
	public static function getTabType($idTab) {
		$idTab	= intval($idTab);
		$tab	= self::getTab($idTab);

		return $tab['type'];
	}



	/**
	 * Get the render function for a tab
	 * The render function is based on the tab type
	 *
	 * @param	Integer		$idTab
	 * @return	String		Function reference
	 */
	public static function getTabRenderer($idTab) {
		$idTab	= intval($idTab);
		$tabType= self::getTabType($idTab);

		return self::getTypeRenderer($tabType);
	}


	/**
	 * Get render function for a tab type
	 *
	 * @param	String		$type
	 * @return	String		Function reference
	 */
	public static function getTypeRenderer($type) {
		return $GLOBALS['CONFIG']['EXT']['portal']['typerenderer'][$type];
	}



	/**
	 * Get filterset IDs which are linked to a tab
	 *
	 * @param	Integer		$idTab
	 * @return	Array
	 */
	public static function getTabFiltersetIDs($idTab) {
		$idTab	= intval($idTab);

			// Get filterset IDs from mm table for normal tabs or from preferences for filtered tab
		if( $idTab === 0 ) {
			$filtersetIDs	= TodoyuPortalPreferences::getFilterTabFiltersetIDs();
		} else {
			$field	= 'id_filterset';
			$table	= 'ext_portal_mm_tab_filterset';
			$where	= 'id_tab = ' . $idTab;

			$filtersetIDs	= Todoyu::db()->getColumn($field, $table, $where);
		}

		return $filtersetIDs;
	}



	/**
	 * Get task IDs for a tab. The tab must have the type task
	 *
	 * @param	Integer		$idTab
	 * @return	Array
	 */
	public static function getTabTaskIDs($idTab) {
		$idTab				= intval($idTab);
		$filtersetIDs		= TodoyuPortalManager::getTabFiltersetIDs($idTab);
		$filtersetTaskIDs	= array();

			// Get conditions for each filterset and search for the tasks
		foreach($filtersetIDs as $idFilterset) {
			$conditions			= TodoyuFiltersetManager::getFiltersetConditions($idFilterset);
			$taskFilter			= new TodoyuTaskFilter($conditions);
			$filtersetTaskIDs[]	= $taskFilter->getTaskIDs();
		}

			// Depending on how much filtersets are linked to the task, combine the results by conjuction config
		if( sizeof($filtersetTaskIDs) === 0 ) {
			$taskIDs	= array();
		} elseif( sizeof($filtersetTaskIDs) === 1 ) {
			$taskIDs	= array_pop($filtersetTaskIDs);
		} else {
			$conjunction= TodoyuPortalManager::getTabConjunction($idTab);

			if( $conjunction === 'OR' ) {
				$taskIDs	= TodoyuArray::mergeSubArrays($filtersetTaskIDs);
			} else {
				$taskIDs	= TodoyuArray::intersectSubArrays($filtersetTaskIDs);
			}
		}

		return $taskIDs;
	}



	/**
	 * Get logical conjunction of given tab
	 *
	 * @param	Integer		$idTab
	 * @return	String		OR / AND
	 */
	public static function getTabConjunction($idTab) {
		$idTab = intval($idTab);

		if( $idTab === 0 ) {
			$conjunction = 'OR';
		} else {
			$tab 		= self::getTab($idTab);
			$conjunction= intval($tab['is_or']) === 1 ? 'OR' : 'AND';
		}

		return $conjunction;
	}



	/**
	 * Get portal tabs (and resp. data from 'ext_portal_tab') configured to be available/ displayed for current user.
	 *
	 * @return	Array
	 */
	public static function getTabs() {
		$idUser		= TodoyuAuth::getUserID();

			// Get user tabs from database
		$fields	= '*';
		$table	= 'ext_portal_tab';
		$where	= '	deleted = 0 AND
					(id_user = ' . $idUser . ' OR id_user = 0)';
		$group	= '';
		$order	= 'sorting ASC';

		$tabs	= Todoyu::db()->getArray($fields, $table, $where, $group, $order);

			// Get filter tab config
		$filterTab = self::getFilterTabConfig();
			// Prepend filtered tab
		array_unshift($tabs, $filterTab);

			// Get label, content list counter, 'active' or not-state
		foreach($tabs as $index => $tab) {
			$tabs[$index]['htmlId'] 		= 'portal-tabhead-' . $tab['id'];
			$tabs[$index]['label']			= TodoyuDiv::getLabel($tab['title']);
			$tabs[$index]['elementAmount']	= TodoyuPortalManager::getTabContentListedAmount($tab['id']);
			$tabs[$index]['classKey'] 		= $tab['id'];
			$tabs[$index]['hasIcon'] 		= true;
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
			$ownItems	= $GLOBALS['CONFIG']['EXT']['portal']['ContextMenu']['Task'];
			$items		= array_merge_recursive($items, $ownItems);

				// Remove clone function
			unset($items['clone']);

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
	 * Get amount of listed entries (tasks, appointments, etc) in tab context
	 *
	 * @param	Integer		$tabID
	 * @return	Integer
	 *
	 */
	public static function getTabContentListedAmount( $tabID ) {
		$tabID		= intval( $tabID );

		$tab		= TodoyuPortalManager::getTab($tabID );
		$funcRef	= explode('::', $GLOBALS['CONFIG']['EXT']['portal']['entriescounter'][ $tab['type'] ]);
		$funcRefLen	= strlen( implode('', $funcRef) );

		$amount = ( is_array($funcRef) && $funcRefLen > 0 ) ? call_user_func($funcRef, $tabID) : 0;

		return $amount;
	}




	/**
	 * Get rendered task list for a tab based on its filtersets
	 *
	 * @param	Integer		$tabID
	 * @return	String
	 *
	 */
	public static function getTasksAmount( $tabID ) {
		$tabID		= intval($tabID);
		$taskIDs	= self::getTaskIDs( $tabID );

		return count( $taskIDs );
	}



		/**
	 * Get task ids matching to given filterset.
	 * Multiple filtersets are grouped (by 'id_set'), their condition groups are joined by OR-conjunction(s).
	 *
	 * @param	Integer		$tabID
	 * @return	Array
	 */
	public static function getTaskIDs( $tabID ) {
		$tabID	= intval($tabID);
		$taskIDs= array();

		$filtersets		= TodoyuPortalManager::getTabFiltersetIDs( $tabID );

			// Get conditions of sets
		$conditionSets		= array();
		foreach($filtersets as $setID) {
			$conditionSets[$setID] = TodoyuFiltersetManager::getFiltersetConditions( $setID );
		}

			// Per set: init filters from set and fetch task ids
		$taskIDsOfSets	= array();
		foreach($conditionSets as $setID => $conditions) {
			$filter					= new TodoyuTaskFilter( $conditions );
			$taskIDsOfSets[$setID]	= $filter->getTaskIDs();
		}

		if( sizeof($taskIDsOfSets) > 0 ) {
				// Combine task ids of sets with logical conjunction
			$conjunction = TodoyuPortalManager::getTabConjunction( $tabID );

			if ($conjunction == 'OR') {
					// OR conjunction
				$taskIDs	= TodoyuArray::mergeSubArrays($taskIDsOfSets);
			} else {
					// AND conjunction
				$taskIDs	= TodoyuArray::intersectSubArrays($taskIDsOfSets);
			}
		}

		return $taskIDs;
	}


}

?>