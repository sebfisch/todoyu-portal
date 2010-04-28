<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Portal renderer
 *
 * @name 		Portal renderer
 * @package		Todoyu
 * @subpackage	Portal
 */
class TodoyuPortalRenderer {

	/**
	 * Extension key
	 */
	const EXTKEY = 'portal';



	/**
	 * Render tab headers for portal
	 *
	 * @return	String
	 */
	public static function renderTabHeads($activeTab = '') {
		$name		= 'portal';
		$jsHandler	= 'Todoyu.Ext.portal.Tab.onSelect.bind(Todoyu.Ext.portal.Tab)';
		$tabs		= TodoyuPortalManager::getTabs();

		if( empty($activeTab) ) {
			$activeTab 	= TodoyuPortalPreferences::getActiveTab();
		}

		return TodoyuTabheadRenderer::renderTabs($name, $tabs, $jsHandler, $activeTab);
	}



	/**
	 * Render content of a portal tab (call registered render function)
	 *
	 * @param	Integer		$idTab
	 * @return	String
	 */
	public static function renderTabContent($tabKey, array $params = array()) {
		$tab	= TodoyuPortalManager::getTabConfig($tabKey);

		if( TodoyuFunction::isFunctionReference($tab['contentFunc']) ) {
			return TodoyuFunction::callUserFunction($tab['contentFunc'], $params);
		} else {
			Todoyu::log('Missing render function for tab "' . $tabKey . '"', LOG_LEVEL_ERROR);
			return 'Found no render function for this tab';
		}
	}



	/**
	 * Get label of selection tab in portal
	 *
	 * @param	Boolean		$count
	 * @return	String
	 */
	public static function getSelectionTabLabel($count = true) {
		$label	= TodoyuLanguage::getLabel('portal.tab.selection');

		if( $count ) {
			$numResults	= TodoyuPortalManager::getSelectionCount();
			$label		= $label . ' (' . $numResults . ')';
		}

		return $label;
	}



	/**
	 * Get content of selection tab in portal
	 *
	 * Task-list based on the selected filters in filterPresetList panelWidget
	 *
	 * @return	String
	 */
	public static function renderSelectionTabContent(array $params = array()) {
			// Check whether filterSets are available as parameters
		if( isset($params['filtersets']) ) {
			$filtersetIDs	= TodoyuArray::intval($params['filtersets'], true, true);
			TodoyuPortalPreferences::saveSelectionTabFiltersetIDs($filtersetIDs);
		} else {
			$filtersetIDs	= TodoyuPortalPreferences::getSelectionTabFiltersetIDs();
		}

			// Check if type is available as parameter
		if( ! isset($params['type']) ) {
			if( sizeof($filtersetIDs) > 0 ) {
				$type	= TodoyuFiltersetManager::getFiltersetType($filtersetIDs[0]);
			} else {
				$type	= 'task';
			}
		} else {
			$type	= trim($params['type']);
		}


		if( sizeof($filtersetIDs) === 0 ) {
			return self::renderNoSelectionMessage();
		} else {
			$resultItemIDs	= TodoyuFiltersetManager::getFiltersetsResultItemIDs($filtersetIDs);

			TodoyuHeader::sendTodoyuHeader('selection', sizeof($resultItemIDs));

			return TodoyuSearchRenderer::renderResultsListing($type, $resultItemIDs);
		}
	}



	/**
	 * Render message if no filterset is selected
	 *
	 * @return	String
	 */
	private static function renderNoSelectionMessage() {
		$tmpl	= 'ext/portal/view/selection-nofilterset.tmpl';

		return render($tmpl);
	}



	/**
	 * Render panel widgets
	 *
	 * @return	String
	 */
	public static function renderPanelWidgets() {
		return TodoyuPanelWidgetRenderer::renderPanelWidgets(self::EXTKEY);
	}

}

?>