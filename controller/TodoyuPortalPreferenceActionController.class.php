<?php

class TodoyuPortalPreferenceActionController extends TodoyuActionController {
	
	public function tabAction(array $params) {
		$idTab	= intval($params['value']);
		
		TodoyuPortalPreferences::saveActiveTab($idTab);
	}
	
	
	public function filtersetsAction(array $params) {
		$filtersetIDs	= explode(',', $params['value']);
		$filtersetIDs	= TodoyuArray::intval($filtersetIDs, true, true);
		
		TodoyuPortalPreferences::saveFilterTabFiltersetIDs($filtersetIDs);
	}
	
	
	public function taskOpenAction(array $params) {
		$idTask		= intval($params['item']);
		$isExpanded	= intval($params['value']) === 1;
		
		TodoyuPortalPreferences::saveTaskExpandedStatus($idTask, $isExpanded);	
	}
	
	
	public function pwidgetAction(array $params) {
		$idWidget	= intval($params['item']);
		$value		= $params['value'];
		
		TodoyuPanelWidgetManager::saveCollapsedStatus(EXTID_PORTAL, $idWidget, $value);
	}
	
	
}

?>