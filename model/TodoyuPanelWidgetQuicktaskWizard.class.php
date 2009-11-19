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
 * Panel widget: quicktask wizard
 *
 */

class TodoyuPanelWidgetQuicktaskWizard extends TodoyuPanelWidget implements TodoyuPanelWidgetIf {

	private $expandedProjects 	= array();

	private $expandedTasks 		= array();


	/**
	 * Constructor (init widget)
	 *
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0) {
			// construct PanelWidget (init basic configuration)
		parent::__construct(
			'portal',									// ext key
			'quicktaskWizard',							// panel widget ID
			'LLL:panelwidget-quicktaskwizard.title',	// widget title text
			$config,									// widget config array
			$params,									// widget params
			$idArea										// area ID
		);

			// Add public and widget assets
		TodoyuPage::addExtAssets('portal', 'public');
		TodoyuPage::addExtAssets('portal', 'panelwidget-quicktaskwizard');

		$this->addHasIconClass();
	}


	public function renderContent() {
		$tmpl	= 'ext/portal/view/panelwidget-quicktaskwizard.tmpl';
		$data	= array(
			'id'	=> $this->getID()
		);

		$content	= render($tmpl, $data);

		$this->setContent($content);

		return $content;
	}


	/**
	 * Render
	 *
	 * @return	String
	 */
	public function render() {
		$this->renderContent();
		//$this->setContent( render('ext/portal/view/panelwidget-quicktaskwizard.tmpl', array() ) );

		return parent::render();
	}



	/**
	 * Render quicktask form
	 *
	 * @return	String
	 */
	public function renderForm()	{
			// Construct form object
		$xmlPath	= 'ext/portal/config/form/quicktask.xml';
		$form		= TodoyuFormManager::getForm($xmlPath);

			// Preset (empty) form data
		$formData	= array();
		$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, 0);

		return $form->render();
	}



	/**
	 * Handle quicktask form (Rendering, validation, saving)
	 *
	 * @param	Array	$formData
	 * @return	Obj
	 */
	public static function handleQuicktaskForm($formData)	{
		$jsonResponse = new stdClass;

		$idTask	= 0;

			// Construct form object
		$xmlPath	= 'ext/portal/config/form/quicktask.xml';
		$form		= TodoyuFormManager::getForm($xmlPath, $idTask);

			// Evoke load form data/ modification hooks
		$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, $idTask);

			// Set form data
		$form->setFormData($formData);

			// Valdiate, save workload record / re-render form
		if($form->isValid())	{
			$workloadRecord = self::createWorkloadRecord($formData);
			$data			= self::setAdditionalQuicktaskFields($formData);

			$jsonResponse->saved		= true;
			$jsonResponse->startTask	= $data['start_workload'] ? true : false;

			unset($data['start_workload']);

			$workloadRecord['id_task'] = $jsonResponse->taskID	= self::saveQuicktaskForm($data, $xmlPath);

			self::saveWorkloadRecord($workloadRecord);
		} else {
			$jsonResponse->saved		= false;
			$jsonResponse->content		= $form->render();
		}

		return $jsonResponse;
	}



	public static function saveQuicktask(array $formData) {
		$data	= array(
			'title'				=> $formData['title'],
			'description'		=> $formData['description'],
			'id_project'		=> $formData['id_project'],
			'id_worktype'		=> $formData['id_worktype'],
			'status'			=> STATUS_OPEN,
			'id_user_assigned'	=> TodoyuAuth::getUserID(),
			'id_user_owner'		=> TodoyuAuth::getUserID(),
			'date_start'		=> NOW,
			'date_end'			=> NOW + TodoyuTime::SECONDS_WEEK,
			'date_deadline'		=> NOW + TodoyuTime::SECONDS_WEEK,
			'type'				=> TASK_TYPE_TASK,
			'estimated_workload'=> TodoyuTime::SECONDS_HOUR
		);

			// If task already done
		if( intval($formData['task_done']) === 1 ) {
			$data['status'] 	= STATUS_DONE;
			$data['date_end']	= NOW;
			$data['date_finish']= NOW;
		}

			// Start tracking
		if( intval($formData['start_tracking']) === 1 ) {
			$data['status'] = STATUS_PROGRESS;
		}

			// Save task
		$idTask = TodoyuTaskManager::saveTask(0, $data);

			// If already tracked workload set
		if( intval($formData['workload_tracked']) > 0 ) {
			self::addTrackedWorkload($idTask, $formData['workload_tracked']);
		}

		return $idTask;
	}


	protected static function addTrackedWorkload($idTask, $workload) {
		$idTask		= intval($idTask);
		$workload	= intval($workload);

		$data	= array(
			'id_user'			=> TodoyuAuth::getUserID(),
			'id_task'			=> $idTask,
			'date_create'		=> NOW,
			'workload_tracked'	=> $workload
		);

		TodoyuTimetrackingManager::saveWorkloadRecord($data);
	}



	/**
	 * Save quicktask form
	 *
	 * @param	Array	$data
	 * @return unknown
	 */
	public static function saveQuicktaskForm(array $data, $xmlPath)	{
		return TodoyuTaskManager::saveTask($data, $xmlPath);
	}



	/**
	 * Save workload record
	 *
	 * @param	Array	$data
	 */
	public static function saveWorkloadRecord($data)	{
		TodoyuTimetrackingManager::saveWorkloadRecord($data);
	}



	/**
	 * Set additional fields to the task insert array
	 *
	 * @param	Array $task
	 * @return	Array
	 */
	protected function setAdditionalQuicktaskFields($task)	{
		$task['status'] = $task['start_workload'] ? STATUS_PROGRESS:STATUS_OPEN;

		if($task['is_task_done'])	{
			$task['status']			= STATUS_DONE;
			$task['date_finish']	= NOW;

			unset($task['is_task_done']);
		}

		$task['id_user_assigned']	=	$task['id_user_owner'] = userid();
		$task['date_start'] 		=	TodoyuTime::format(NOW);
		$task['date_end'] 			=	$task['date_deadline'] = TodoyuTime::format((NOW+3600));

		unset($task['workload_chargeable']);
		unset($task['workload_tracked']);

		return $task;
	}



	/**
	 * Create workload record
	 *
	 * @param	Array	$task
	 * @return	Array
	 */
	protected function createWorkloadRecord($task)	{
		$timeTrackArray = array(
			'date_update'	=> NOW,
			'id_user'		=> userid(),
			'date_create'	=> NOW,
		);

		$timeTrackArray['workload_chargeable']	= TodoyuTimetrackingManager::calculateTrackedTimeFromString($task['workload_chargeable']);
		$timeTrackArray['workload_tracked']		= TodoyuTimetrackingManager::calculateTrackedTimeFromString($task['workload_tracked']);

		return $timeTrackArray;
	}

}
?>