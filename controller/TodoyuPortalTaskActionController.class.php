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
 * Portal task action controller
 *
 * @package		Todoyu
 * @subpackage	Portal
 */
class TodoyuPortalTaskActionController extends TodoyuActionController {

	/**
	 * Get task content in portal
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function getAction(array $params) {
		$idTask	= intval($params['task']);

		return TodoyuPortalRenderer::renderTask($idTask);
	}



	/**
	 * Edit task in portal. Get form content
	 *
	 * @param	Array	$params
	 */
	public function editAction(array $params) {
		$idTask	= intval($params['task']);

		return TodoyuTaskRenderer::renderTaskEditForm($idTask);
	}



	/**
	 * Save task in portal
	 * It's nearly the same as in project, but renders a portal task at the end (and doesn't set some preferences)
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function saveAction(array $params) {
		$data			= $params['task'];
		$idTask			= intval($data['id']);
		$idParentTask	= intval($data['id_parenttask']);

			// Create a cache record for the buildform hooks
		$task = new TodoyuTask(0);
		$task->injectData($data);
		TodoyuCache::addRecord($task);

			// Initialize form for validation
		$xmlPath	= 'ext/project/config/form/task.xml';
		$form		= TodoyuFormManager::getForm($xmlPath, $idTask);

		$form->setFormData($data);

			// Check if form is valid
		if( $form->isValid() ) {
				// If form is valid, get form storage data and update task
			$storageData= $form->getStorageData();

				// Save task
			$idTaskNew	= TodoyuTaskManager::saveTask($storageData);

			TodoyuHeader::sendTodoyuHeader('idTask', $idTaskNew);
			TodoyuHeader::sendTodoyuHeader('idTaskOld', $idTask);

			return TodoyuPortalRenderer::renderTask($idTaskNew);
		} else {
			TodoyuHeader::sendTodoyuHeader('idTask', $idTask);
			TodoyuHeader::sendTodoyuErrorHeader();

			return $form->render();
		}
	}


	/**
	 * Get task detail content
	 *
	 * @param	Array		$params
	 * @return	String
	 */
	public function detailAction(array $params) {
		$idTask		= intval($params['task']);

			// Save task open
		TodoyuPortalPreferences::saveTaskExpandedStatus($idTask, true);

			// Set task acknowledged
		TodoyuTaskManager::setTaskAcknowledged($idTask);

		return TodoyuTaskRenderer::renderTaskDetail($idTask);
	}


}

?>