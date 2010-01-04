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
 * Portal preferences
 *
 * @name 		Portal preferences
 * @package		Todoyu
 * @subpackage	Portal
 *
 */

class TodoyuPortalPreferences {

	/**
	 * Save a preference for portal
	 *
	 * @param	String		$preference
	 * @param	String		$value
	 * @param	Integer		$idItem
	 * @param	Boolean		$unique
	 * @param	Integer		$idUser
	 */
	public static function savePref($preference, $value, $idItem = 0, $unique = false, $idArea = 0, $idUser = 0) {
		TodoyuPreferenceManager::savePreference(EXTID_PORTAL, $preference, $value, $idItem, $unique, $idArea, $idUser);
	}



	/**
	 * Get a preference
	 *
	 * @param	String		$preference
	 * @param	Integer		$idItem
	 * @param	Integer		$idUser
	 * @return	String
	 */
	public static function getPref($preference, $idItem = 0, $idArea = 0, $unserialize = false, $idUser = 0) {
		$idItem	= intval($idItem);
		$idUser	= intval($idUser);

		return TodoyuPreferenceManager::getPreference(EXTID_PORTAL, $preference, $idItem, $idArea, $unserialize, $idUser);
	}



	/**
	 * Get  project preference
	 *
	 * @param	String		$preference
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Integer		$idUser
	 * @return	Array
	 */
	public static function getPrefs($preference, $idItem = 0, $idArea = 0, $idUser = 0) {
		return TodoyuPreferenceManager::getPreferences(EXTID_PORTAL, $preference, $idItem, $idArea, $idUser);
	}



	/**
	 * Delete portal preference
	 *
	 * @param	String		$preference
	 * @param	String		$value
	 * @param	Integer		$idItem
	 * @param	Integer		$idArea
	 * @param	Integer		$idUser
	 */
	public static function deletePref($preference, $value = null, $idItem = 0, $idArea = 0, $idUser = 0) {
		TodoyuPreferenceManager::deletePreference(EXTID_PORTAL, $preference, $value, $idItem, $idArea, $idUser);
	}



	/**
	 * Get currently active tab of current user
	 *
	 * @return	Integer
	 */
	public static function getActiveTab() {
		$tab = self::getPref('tab');

		$tabs	= TodoyuPortalManager::getTabs();

		if ( $tab === false ) {
			$tab = 'selection';
		}

		return $tab;
	}



	/**
	 * Save active tab of current user
	 *
	 * @param	Integer
	 */
	public static function saveActiveTab($tabKey) {
		self::savePref('tab', $tabKey, 0, true);
	}



	/**
	 * Get currently selected filtersets for selection tab
	 *
	 * @return	Array
	 */
	public static function getSelectionTabFiltersetIDs() {
		$filtersetIDs	= self::getPref('filtersets');

		if( $filtersetIDs === false || $filtersetIDs === '' ) {
			$filtersetIDs = array();
		} else {
			$filtersetIDs = explode(',', $filtersetIDs);
		}

		return $filtersetIDs;
	}



	/**
	 * Save currently selected filtersets for selection tab
	 *
	 * @param	Array		$filtersetIDs
	 */
	public static function saveSelectionTabFiltersetIDs(array $filtersetIDs) {
		$filtersetIDs	= TodoyuArray::intval($filtersetIDs, true);
		$prefValue		= implode(',', $filtersetIDs);

		self::savePref('filtersets', $prefValue, 0, true);
	}



	/**
	 * Save filtersets of tab
	 *
	 * @param	String	$filtersetIDs
	 * @param	Integer	$tabID
	 */
	public static function saveTabFiltersets($filtersetIDs, $idTab = 0) {
		$idTab			= intval($idTab);
		$filtersetIDs	= TodoyuDiv::intExplode(',', $filtersetIDs, true, true);

		if ($idTab == 0) {
				// 'Selection' tab (filtersets stored in user prefs)
			TodoyuPreferenceManager::deletePreference(EXTID_PORTAL, 'filterset', null, $idTab);

			foreach($filtersetIDs as $idFilterset) {
				TodoyuPreferenceManager::savePreference(EXTID_PORTAL, 'filterset', $idFilterset, $idTab, false);	// extID, pref, value, idItem, unique, idUser
			}
		} else {
			// Regular tab (filtersets stored in 'ext_portal_mm_tab_filterset')

		}
	}



	/**
	 * Get IDs of the expanded tasks
	 *
	 * @return	Array
	 */
	public static function getExpandedTasks() {
		$taskIDs = self::getPrefs('task-exp');

		if( $taskIDs === false ) {
			$taskIDs = array();
		}

		return $taskIDs;
	}



	/**
	 * Save expanded task status
	 *
	 * @param	Integer		$idTask			Task ID
	 * @param	Boolean		$expanded		Is task now expanded?
	 */
	public static function saveTaskExpandedStatus($idTask, $expanded = true) {
		$idTask	= intval($idTask);

		if( $expanded ) {
			self::savePref('task-exp', $idTask);
		} else {
			self::deletePref('task-exp', $idTask);
		}
	}

}

?>