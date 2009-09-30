<?php

class TodoyuPortalTabActionController extends TodoyuActionController {
	
	public function init() {
		
	}
	
	public function updateAction(array $params) {
		$idTab	= intval($params['tab']);

		TodoyuPortalPreferences::saveActiveTab($idTab);

		return TodoyuPortalRenderer::renderTabContent($idTab);
	}
	
	public function updateTabHeadAction(array $params) {
		$idTab	= intval($params['tab']);
		
		return TodoyuPortalRenderer::updateTabHead($idTab);
	}
	
	public function saveActiveAction(array $params) {
		$idTab	= intval($params['tab']);
		
		TodoyuPortalPreferences::saveActiveTab($idTab);
		
		return TodoyuPortalRenderer::renderTabContent($idTab);
	}
	
}

?>