<?php

class TodoyuPortalExtActionController extends TodoyuActionController {

	public function defaultAction(array $params) {
			// Activate FE tab
		TodoyuFrontend::setActiveTab('portal');

			// Setup page to be rendered
		TodoyuPage::init('ext/portal/view/ext.tmpl');
		TodoyuPage::setTitle('LLL:portal.page.title');

			// Panel widgets
		$panelWidgets 	= TodoyuPortalRenderer::renderPanelWidgets();
		TodoyuPage::set('panelWidgets', $panelWidgets);

		$idActiveTab	= isset($params['tab']) ? $params['tab'] : TodoyuPortalPreferences::getActiveTab();

		TodoyuPortalPreferences::saveActiveTab($idActiveTab);

		//render Tabhead
		$portalTabs		= TodoyuPortalRenderer::renderTabHeads();
		$tabContainers	= TodoyuPortalRenderer::renderEmptyTabContainers();

		TodoyuPage::set('portalTabs', 	$portalTabs);
		TodoyuPage::set('tabContainers', $tabContainers);

		// Render active tab, tab content
		$tab			= TodoyuPortalManager::getTab($idActiveTab);
		$tabContent		= TodoyuPortalRenderer::renderTabContent($idActiveTab);

		TodoyuPage::set('tab', 			$tab);
		TodoyuPage::set('tabContent', 	$tabContent);

			// Add assets
		TodoyuPage::addExtAssets('project');
		TodoyuPage::addExtAssets('portal');

			// Context menu
		TodoyuPage::addJsInlines('Todoyu.Ext.project.ContextMenuTask.attach();');

			// Display output
		return TodoyuPage::render();
	}

}


?>