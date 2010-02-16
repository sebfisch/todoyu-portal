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
	 * Get label of selection tab in portal
	 *
	 * @param	Bool		$count
	 * @return	String
	 */
	public static function getSelectionTabLabel($count = true) {

		$label	= TodoyuLanguage::getLabel('portal.tab.selection');

		if( $count ) {
			$taskIDs= TodoyuPortalManager::getSelectionTaskIDs();
			$label	= $label . ' (' . sizeof($taskIDs) . ')';
		}

		return $label;
	}



	/**
	 * Get content of selection tab in portal
	 * Tasklist based on the selected filters in filterpreset list panelwidget
	 *
	 * @return	String
	 */
	public static function renderSelectionTabContent(array $params = array()) {
		if( isset($params['filtersets']) ) {
			$filtersetIDs	= TodoyuArray::intval($params['filtersets'], true, true);
			TodoyuPortalPreferences::saveSelectionTabFiltersetIDs($filtersetIDs);
		}

		$taskIDs	= TodoyuPortalManager::getSelectionTaskIDs();

		TodoyuHeader::sendTodoyuHeader('selection', sizeof($taskIDs));

		return self::renderTaskList($taskIDs);
	}



	/**
	 * Get label of todo tab in portal
	 *
	 * @param	Bool		$count
	 * @return	String
	 */
	public static function getTodoTabLabel($count = true) {
		$label		= TodoyuLanguage::getLabel('portal.tab.todos');

		if( $count ) {
			$numTasks	= sizeof(TodoyuPortalManager::getTodoTaskIDs());
			$label		=  $label . ' (' . $numTasks . ')';
		}

		return $label;
	}



	/**
	 * Get content of todo tab in portal
	 *
	 * @return	String
	 */
	public static function renderTodoTabContent(array $params = array()) {
		$taskIDs= TodoyuPortalManager::getTodoTaskIDs();

		return self::renderTaskList($taskIDs);
	}



	/**
	 * Render task list
	 *
	 * @param	Array	$taskIDs
	 * @return	String
	 */
	public static function renderTaskList(array $taskIDs) {
		$content= '';

		foreach($taskIDs as $idTask) {
			$content .= self::renderTask($idTask);
		}

		$tmpl	= 'ext/portal/view/tasklist.tmpl';
		$data	= array(
			'tasks'	=> $content
		);

		if( TodoyuRequest::isAjaxRequest() ) {
			$data['javascript'] = 'Todoyu.Ext.project.ContextMenuTask.reattach();';
		} else {
			TodoyuHookManager::callHook('project', 'renderTasks');
		}

		return render($tmpl, $data);
	}







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

		if( TodoyuDiv::isFunctionReference($tab['contentFunc']) ) {
			return TodoyuDiv::callUserFunction($tab['contentFunc'], $params);
		} else {
			Todoyu::log('Missing render function for tab "' . $tabKey . '"', LOG_LEVEL_ERROR);
			return 'Found no render function for this tab';
		}
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
		$tmpl	= 'ext/portal/view/task-header.tmpl';
		$data 	= array(
			'task'				=> $taskData,
			'isExpanded'		=> $isExpanded,
			'subtasks'			=> '',
			'taskIcons'			=> TodoyuTaskManager::getAllTaskIcons($idTask),
		);

			// Render details if task is expanded
		if( $isExpanded ) {
			$activeTab		= TodoyuProjectPreferences::getActiveTaskTab($idTask, AREA);
			$data['details']= TodoyuTaskRenderer::renderTaskDetail($idTask, $activeTab);
			$data['task']['class'] .= ' expanded';
		}

		$data	= TodoyuHookManager::callHookDataModifier('project', 'taskDataBeforeRendering', $data, array($idTask));

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

}

?>