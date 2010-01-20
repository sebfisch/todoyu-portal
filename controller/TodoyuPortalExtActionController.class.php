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
 * Default portal action controller
 *
 * @package		Todoyu
 * @subpackage	Portal
 */
class TodoyuPortalExtActionController extends TodoyuActionController {

	/**
	 * Portal default action: render portal view
	 *
	 *	@param	Array	$params
	 *	@return	String
	 */
	public function defaultAction(array $params) {
		restrict('portal', 'general:use');

			// Activate FE tab
		TodoyuFrontend::setActiveTab('portal');

			// Add assets
		TodoyuPage::addExtAssets('portal', 'public');

			// Setup page to be rendered
		TodoyuPage::init('ext/portal/view/ext.tmpl');
		TodoyuPage::setTitle('LLL:portal.page.title');

			// Get active tab
		$activeTab	= $params['tab'];
		if( ! empty($activeTab) ) {
			TodoyuPortalPreferences::saveActiveTab($activeTab);
		} else {
			$activeTab = TodoyuPortalPreferences::getActiveTab();
		}

			// Panel widgets
		$panelWidgets 	= TodoyuPortalRenderer::renderPanelWidgets();
			// Tabheads
		$tabHeads		= TodoyuPortalRenderer::renderTabHeads($activeTab);
			// Render active tab, tab content
		$activeTabContent	= TodoyuPortalRenderer::renderTabContent($activeTab);

		TodoyuPage::set('panelWidgets', $panelWidgets);
		TodoyuPage::set('tabHeads', $tabHeads);
		TodoyuPage::set('activeTabContent', $activeTabContent);

		TodoyuPortalManager::addTabAssetsToPage();

		TodoyuDebug::printHtml(TodoyuLocaleManager::getLocaleOptions());

			// Context menu
		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.project.ContextMenuTask.attach.bind(Todoyu.Ext.project.ContextMenuTask)');

			// Display output
		return TodoyuPage::render();
	}

}

?>