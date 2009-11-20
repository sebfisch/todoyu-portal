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

class TodoyuPortalTaskActionController extends TodoyuActionController {

	public function hasActionAccess($action) {
		return true;
	}

	public function getAction(array $params) {
		$idTask	= intval($params['task']);

		return TodoyuPortalRenderer::renderTask($idTask);
	}

	public function editAction(array $params) {
		$idTask	= intval($params['task']);

		echo TodoyuTaskRenderer::renderTaskEditForm($idTask);
	}

	public function getTasklistAction(array $params) {
		$prefName	= $params['storePrefs'];
		$storePrefs	= $params[$prefName];

		if( $storePrefs ) {
				// Store prefs (active tab)
			$activeTabID	= trim($params['tab']);
			$activeTabID	= (substr($activeTab, 0, 4) == 'tab_') ? substr($activeTabID, 4) : $activeTabID;

			TodoyuPortalPreferences::saveActiveTab($activeTabID);
		} else {
				// Fetch prefs (active tab, filters)
			$activeTabID	= TodoyuPortalPreferences::getActiveTab();
		}

		return TodoyuPortalRenderer::renderTabContent($activeTabID);
	}


}


?>