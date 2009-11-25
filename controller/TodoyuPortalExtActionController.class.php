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

class TodoyuPortalExtActionController extends TodoyuActionController {

	public function defaultAction(array $params) {
		restrict('portal', 'use');

			// Activate FE tab
		TodoyuFrontend::setActiveTab('portal');

			// Add assets
		TodoyuPage::addExtAssets('portal', 'public');

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
		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.project.ContextMenuTask.attach.bind(Todoyu.Ext.project.ContextMenuTask)');

			// Display output
		return TodoyuPage::render();
	}

}


?>