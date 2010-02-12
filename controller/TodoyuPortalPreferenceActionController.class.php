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

class TodoyuPortalPreferenceActionController extends TodoyuActionController {

	/**
	 * @todo	comment
	 * @param	Array	$params
	 */
	public function tabAction(array $params) {
		$idTab	= intval($params['value']);

		TodoyuPortalPreferences::saveActiveTab($idTab);
	}



	/**
	 * @todo	comment
	 * @param	Array	$params
	 */
	public function filtersetsAction(array $params) {
		$filtersetIDs	= explode(',', $params['value']);
		$filtersetIDs	= TodoyuArray::intval($filtersetIDs, true, true);

		TodoyuPortalPreferences::saveSelectionTabFiltersetIDs($filtersetIDs);
	}



	/**
	 * Save task being expanded / collapsed.
	 * $params contains task ID and expand-state.
	 * 		task id:	$params['item'],
	 * 		expanded:	$params['value'] === 1
	 *
	 * @param array $params
	 */
	public function taskOpenAction(array $params) {
		$idTask		= intval($params['item']);
		$isExpanded	= intval($params['value']) === 1;

		TodoyuPortalPreferences::saveTaskExpandedStatus($idTask, $isExpanded);
	}



	/**
	 * General panelWidget action, saves collapse status
	 *
	 * @param	Array	$params
	 */
	public function pwidgetAction(array $params) {
		$idWidget	= $params['item'];
		$value		= $params['value'];

		TodoyuPanelWidgetManager::saveCollapsedStatus(EXTID_PORTAL, $idWidget, $value);
	}

}

?>