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
 * Panel widget: filter presets
 *
 * @package		Todoyu
 * @subpackage	Portal
 */

class TodoyuPanelWidgetFilterPresetList extends TodoyuPanelWidget implements TodoyuPanelWidgetIf {

	/**
	 * Initialize filter presetlist widget
	 *
	 * @param	Array		$config
	 * @param	Array		$params
	 * @param	Integer		$idArea
	 * @param	Boolean		$expanded
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0) {

		parent::__construct(
			'portal',								// ext key
			'filterpresetlist',							// panel widget ID
			'LLL:panelwidget-filterpresetlist.title',	// widget title text
			$config,								// widget config array
			$params,								// widget params
			$idArea									// area ID
		);

		$this->addHasIconClass();
	}



	/**
	 * Get filtersets of current person (includes public filtersets)
	 *
	 * @return	Array
	 */
	private function getFiltersetOptions() {
		$activeFiltersets	= self::getActiveFiltersetIDs();
		$filtersets			= TodoyuFiltersetManager::getTypeFiltersets('TASK', 0, false);

		foreach($filtersets as $index => $filterset) {
			$conditions	= TodoyuFiltersetManager::getFiltersetConditions($filterset['id']);
			$taskFilter	= new TodoyuTaskFilter($conditions);
			$taskIDs	= $taskFilter->getTaskIDs();

				// Update filterset
			$filtersets[$index]['label']	= TodoyuDiv::cropText($filtersets[$index]['title'], 46, '', false) . ' (' . sizeof($taskIDs) . ')';
			$filtersets[$index]['selected']	= in_array($filterset['id'], $activeFiltersets);
			$filtersets[$index]['value']	= $filterset['id'];
		}

		return $filtersets;
	}



	/**
	 * Get array of types of filtersets (record types with dedicated filtersets)
	 *
	 * @return	Array
	 */
	public function getFiltersetTypes() {
		$typeKeys	= TodoyuFilterManager::getFilterTypes(true);
		$types		= array();

		foreach($typeKeys as $typeKey) {
			$typeFiltersets	= TodoyuFiltersetManager::getTypeFiltersets($typeKey);
			$filterClass	= TodoyuFilterManager::getFilterTypeClass($typeKey);

			$types[$typeKey]['title'] = TodoyuFilterManager::getFilterTypeLabel($typeKey);

			if ( count($typeFiltersets) == 0 ) {
				$types[$typeKey]['title']	.= ' (' . Label('LLL:panelwidget-filterpresetlist.error.noTypeFiltersets') . ')';
			}

			foreach($typeFiltersets as $index => $typeFilterset) {
				$conditions	= TodoyuFiltersetManager::getFiltersetConditions($typeFilterset['id']);
				$typeFilter	= new $filterClass($conditions);
				$itemIDs	= $typeFilter->getItemIDs();

				$types[$typeKey]['options'][] = array(
					'label'	=> TodoyuDiv::cropText($typeFilterset['title'], 46, '', false) . ' (' . sizeof($itemIDs) . ')',
					'value'	=> $typeFilterset['id']
				);
			}
		}

		return $types;
	}



	/**
	 * Get IDs of active filtersets in list
	 *
	 * @return	Array
	 */
	private function getActiveFiltersetIDs() {
		return TodoyuPortalPreferences::getSelectionTabFiltersetIDs();
	}



	/**
	 * Render widget content
	 *
	 * @return	String
	 */
	public function renderContent() {
		$tmpl	= 'ext/portal/view/panelwidget-filterpresetlist.tmpl';

		$filtersetOptions	= $this->getFiltersetOptions();

		$data	= array(
			'id'		=> $this->getID(),
			'types'		=> $this->getFiltersetTypes(),
			'options'	=> $filtersetOptions,
			'selected'	=> array()
		);

		if( TodoyuPortalPreferences::getActiveTab() === 'selection' ) {
			$data['selected']	= self::getActiveFiltersetIDs();
		}

		$content	= render($tmpl, $data);
		$this->setContent($content);

		return $content;
	}



	/**
	 * Render filter presets panel widget
	 *
	 * @return	String
	 */
	public function render() {
		TodoyuPage::addExtAssets('portal', 'public');
		TodoyuPage::addExtAssets('portal', 'panelwidget-filterpresetlist');

		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.portal.PanelWidget.FilterPresetList.init.bind(Todoyu.Ext.portal.PanelWidget.FilterPresetList)', 100);

		$this->renderContent();

		return parent::render();
	}



	/**
	 * Check if panelwidget is allowed
	 *
	 * @return	Bool
	 */
	public static function isAllowed() {
		return allowed('portal', 'panelwidgets:filterPresetList');
	}

}

?>