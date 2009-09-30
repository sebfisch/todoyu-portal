<?php

class TodoyuPortalTaskActionController extends TodoyuActionController {

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