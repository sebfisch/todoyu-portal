<?php

class TodoyuPortalPreferenceActionController extends TodoyuActionController {

	protected function hasActionAccess($action) {
		return $this->hasControllerAccess();
	}

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



	/**
	 *	General panelWidget action, saves collapse status
	 *
	 *	@param	Array	$params
	 */
	public function pwidgetAction(array $params) {
		$idWidget	= $params['item'];
		$value		= $params['value'];

		TodoyuPanelWidgetManager::saveCollapsedStatus(EXTID_PORTAL, $idWidget, $value);
	}


}

?>