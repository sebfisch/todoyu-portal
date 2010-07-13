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
 *	Ext: portal
 */

Todoyu.Ext.portal = {

	PanelWidget:	{},

	Headlet:		{},



	/**
	 * Show task
	 * If task is currently displayed in portal area:	jump to it
	 * If task is currently not displayed in portal area: jump to it in project area
	 *
	 * @param	{Number}	idTask
	 * @param	{Number}	idProject
	 */
	showTask: function(idTask, idProject) {
		var task	= 'task-' + idTask;

		if( Todoyu.Ui.isVisible(task) ) {
			$(task).scrollToElement();
			Todoyu.Ui.twinkle(task);
		} else {
			Todoyu.Ext.project.goToTaskInProject(idTask, idProject);
		}

/*
			// Task already within current view (e.g 'portal')
		if( Todoyu.exists('task-' + idTask) ) {

				// Scroll to task, open task container
			this.expandSubtaskContainers( idTask );
//			location.hash = 'task-' + idTask;
			Todoyu.Ui.scrollToElement( $('task-3524') );
			Todoyu.Ui.twinkle( $('task-3524') );

		} else {
				// task was not in view, jump to 'project' view and show the task
			Todoyu.Ext.project.goToTaskInProject(idTask, idProject);
		}
*/
	},



	/**
	 * Open sub task containers of given task
	 *
	 * @param	{Number}	idTask
	 */
	expandSubtaskContainers: function(idTask) {
		var task		= $('task-' + idTask);
		var container	= task.up('.subtasks');
		var idParts, idCTask;

		while( container ) {
			idParts	= container.id.split('-');
			idCTask	= idParts[1];

			Todoyu.Ext.project.TaskTree.openSubtasks(idCTask);

			container = $('task-' + idCTask).up('.subtasks');
		}
	},



	/**
	 * Save portal preferences
	 *
	 * @param	{String}	action
	 * @param	{String}	value
	 * @param	{Number}	idItem
	 * @param	{String}	onComplete
	 */
	savePref: function(action, value, idItem, onComplete) {
		Todoyu.Pref.save('portal', action, value, idItem, onComplete);
	}

};