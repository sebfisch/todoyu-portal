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
 * Portal renderer
 *
 * @name 		Portal renderer
 * @package		Todoyu
 * @subpackage	Portal
 */

class TodoyuPortalRenderer {

	/**
	 * Expanded task IDs
	 *
	 * @var	Array
	 */
	private static $expandedTaskIDs = null;


	/**
	 * Render content of a portal tab (call registered render function)
	 *
	 * @param	Integer		$idTab
	 * @return	String
	 */
	public static function renderTabContent($idTab) {
		$idTab		= intval($idTab);
		$renderFunc	= TodoyuPortalManager::getTabRenderer($idTab);

		return TodoyuDiv::callUserFunction($renderFunc, $idTab);
	}



	/**
	 * Render function for task tabs
	 *
	 * @param	Integer		$idTab
	 * @return	String
	 */
	public static function renderTaskTab($idTab) {
		$idTab	= intval($idTab);
		$taskIDs= TodoyuPortalManager::getTabTaskIDs($idTab);

		$content= '';

		foreach($taskIDs as $idTask) {
			$content .= self::renderTask($idTask);
		}

		$tmpl	= 'ext/portal/view/tasklist.tmpl';
		$data	= array(
			'taskHTML'	=> $content
		);

		if( TodoyuRequest::isAjaxRequest() ) {
			$data['contextMenuJsInit'] = 'Todoyu.Ext.project.ContextMenuTask.reattach();';
		}

		return render($tmpl, $data);
	}



	/**
	 * Get empty div containers for all not active tasks.
	 * So they are available if tab is switched
	 *
	 * @return	String
	 */
	public static function renderEmptyTabContainers() {
		$tabs	= TodoyuPortalManager::getTabs();
		$active	= TodoyuPortalPreferences::getActiveTab();

		foreach($tabs as $index => $tab) {
			if( $tab['id'] == $active ) {
				unset( $tabs[$index] );
				break;
			}
		}

		return render( 'ext/portal/view/tab-containers.tmpl', array('tabs'	=> $tabs) );
	}




	/**
	 * Render task list
	 *
	 * @param	Array	$taskIDs
	 * @return	String
	 */
	public static function renderTaskList(array $taskIDs = array()) {
		$tasksHtml = '';

		foreach($taskIDs as $idTask) {
			$tasksHtml .= TodoyuPortalRenderer::renderTask($idTask);
		}

		$tmpl	= 'ext/portal/view/tasklist.tmpl';
		$data	= array(
			'tasks'	=> $tasksHtml,
		);

		return render($tmpl, $data);
	}



	/**
	 * Check if task is expanded
	 *
	 * @param	Integer		$idTask
	 * @return	Boolean
	 */
	public static function isTaskExpanded($idTask) {
		$idTask	= intval($idTask);

		if( is_null(self::$expandedTaskIDs) ) {
			//TodoyuProjectPreferences::get
			self::$expandedTaskIDs = TodoyuPortalPreferences::getExpandedTasks();
		}

		return in_array($idTask, self::$expandedTaskIDs);
	}



	/**
	 * Render task
	 *
	 * @param	Integer		$idTask
	 * @return	String
	 */
	public static function renderTask($idTask) {
		$idTask		= intval($idTask);

				// Get some task information
		$isExpanded	= self::isTaskExpanded($idTask);
		$taskData	= TodoyuTaskManager::getTaskInfoArray($idTask, 3);

			// Prepare data array for template
		$data = array(
			'task'			=> $taskData,
			'isExpanded'	=> $isExpanded
		);

			// Render details if task is expanded
		if( $isExpanded ) {
			$activeTab		= TodoyuProjectPreferences::getActiveTaskTab($idTask, AREA);
			$data['details']= TodoyuTaskRenderer::renderTaskDetail($idTask, $activeTab);
			$data['task']['class'] .= ' expanded';
		}

		$data	= TodoyuHookManager::callHookDataModifier('project', 'taskDataBeforeRendering', $data, array($idTask));
		$tmpl	= 'ext/portal/view/task-header.tmpl';

		return render($tmpl, $data);
	}



	/**
	 * Render panel widgets
	 *
	 * @return	String
	 */
	public static function renderPanelWidgets() {
		$params	= array();

		return TodoyuPanelWidgetRenderer::renderPanelWidgets('portal', $params);
	}



	/**
	 * Render tab headers for portal
	 *
	 * @return	String
	 */
	public static function renderTabHeads($activeTab = '') {
		$listID		= 'portal-tabs';
		$class		= 'tabs';
		$jsHandler	= 'Todoyu.Ext.portal.Tab.onSelect.bind(Todoyu.Ext.portal.Tab)';
		$tabs		= TodoyuPortalManager::getTabs();

		if ( empty($activeTab) ) {
			$activeTab 	= TodoyuPortalPreferences::getActiveTab();
		}

		return TodoyuTabheadRenderer::renderTabs($listID, $class, $jsHandler, $tabs, $activeTab);
	}



	/**
	 * Update tab head
	 *
	 * @param	Integer	$idTab
	 * @return	String
	 */
	public static function updateTabHead($idTab) {
		$tabConfig = TodoyuPortalManager::getTab($idTab);
		$tabConfig['id'] 			= 'portal-tabhead-' . $idTab;
//		$tabConfig['classKey']		= $idTab;
		$tabTasks 					= Portal::getTaskIDs($idTab);
		$tabConfig['tasksamount']	= count($tabTasks);
		$tabConfig['hasIcon']		= 1;
		$tabConfig['tasksamount']	= TodoyuPortalManager::getTasksAmount($idTab);

		return TodoyuTabheadRenderer::renderTab($tabConfig['id'], $tabConfig['key'], $tabConfig['class'], $tabConfig['classKey'], $idTab, TodoyuLocale::getLabel($tabConfig['label']), $tabConfig['position'], $tabConfig['hasIcon'], $tabConfig['tasksamount']);
	}

}

?>