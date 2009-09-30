<?php

class TodoyuPortalExtActionController extends TodoyuActionController {

	public function defaultAction(array $params) {
			// activate FE tab
		TodoyuFrontend::setActiveTab('portal');
		
			// Setup page to be rendered
		TodoyuPage::init('ext/portal/view/ext.tmpl');
		TodoyuPage::setTitle('LLL:portal.pagetitle');
		
			// Panel widgets
		$panelWidgets 	= TodoyuPortalRenderer::renderPanelWidgets();
		$portalTabs		= TodoyuPortalRenderer::renderTabHeads();
		$tabContainers	= TodoyuPortalRenderer::renderEmptyTabContainers();
		$idTab			= TodoyuPortalPreferences::getActiveTab();
		$tab			= TodoyuPortalManager::getTab($idTab);
		$tabContent		= TodoyuPortalRenderer::renderTabContent($idTab);
				
		TodoyuPage::set('panelWidgets', 	$panelWidgets);
		TodoyuPage::set('portalTabs', 	$portalTabs);
		TodoyuPage::set('tabContainers', 	$tabContainers);
		TodoyuPage::set('tab', 			$tab);
		TodoyuPage::set('tabContent', 	$tabContent);
		
		
		TodoyuPage::addExtAssets('project');
		TodoyuPage::addExtAssets('portal');
		
		TodoyuPage::addJsInlines('Todoyu.Ext.project.ContextMenuTask.attach();');
		
			// Display output
		return TodoyuPage::render();
	}
	
	
	
}


?>