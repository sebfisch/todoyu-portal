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
	 * Get filtersets of the user (includes public filtersets)
	 *
	 * @return	Array
	 */
	private function getFiltersets() {
		$activeFiltersets	= self::getActiveFiltersetIDs();
		$filtersets			= TodoyuFiltersetManager::getTaskFiltersets(0, false);

			// Add amount information to each filterset
		foreach($filtersets as $index => $filterset) {
			$conditions	= TodoyuFiltersetManager::getFiltersetConditions($filterset['id']);
			$taskFilter	= new TodoyuTaskFilter($conditions);
			$taskIDs	= $taskFilter->getTaskIDs();

				// Update filterset
			$filtersets[$index]['amount']	= sizeof($taskIDs);
			$filtersets[$index]['selected']	= in_array($filterset['id'], $activeFiltersets);
		}

		return $filtersets;
	}



	/**
	 * Get IDs of the active filtersets in the list
	 *
	 * @return	Array
	 */
	private function getActiveFiltersetIDs() {
		return TodoyuPortalPreferences::getFilterTabFiltersetIDs();
	}



	/**
	 * Render widget content
	 *
	 * @return	String
	 */
	public function renderContent() {
		$filtersets	= $this->getFiltersets();

		$tmpl	= 'ext/portal/view/panelwidget-filterpresetlist.tmpl';
		$data	= array(
			'id'			=> $this->getID(),
			'filtersets'	=> $filtersets
		);

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

		TodoyuPage::addJsOnloadedFunction('Todoyu.Ext.portal.PanelWidget.FilterPresetList.init.bind(Todoyu.Ext.portal.PanelWidget.FilterPresetList)');

		$this->renderContent();

		return parent::render();
	}


	public static function isAllowed() {
		return allowed('portal', 'panelwidget:filterPresetList');
	}

}

?>