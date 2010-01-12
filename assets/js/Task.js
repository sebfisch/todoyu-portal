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

Todoyu.Ext.portal.Task = {

	/**
	 *	Ext shortcut
	 */
	ext:	Todoyu.Ext.portal,



	/**
	 *	Update task status
	 *
	 *	@param	Integer	idTask
	 *	@param	String	status
	 */
	updateStatus: function(idTask, status) {
		var url	= Todoyu.getUrl('project', 'task');
		var options	= {
			'parameters': {
				'task':		idTask,
				'action':	'setstatus',
				'status':	status
			},
			'onComplete': this.refresh.bind(this, idTask)
		};

		Todoyu.send(url, options);
	},



	/**
	 *	Refresh a task
	 *
	 *	@param	Integer	idTask
	 */
	refresh: function(idTask) {
		var target	= 'task-' + idTask;
		var url		= Todoyu.getUrl('portal', 'task');
		var options	= {
			'parameters': {
				'action':	'get',
				'task':		idTask
			},
			'onComplete': this.onRefreshed.bind(this, idTask)
		};

			// Detach menu
		this.removeContextMenu(idTask);
			// Update task
		Todoyu.Ui.replace(target, url, options);
	},
	
	
	
	/**
	 * Handler when task refreshed
	 * 
	 * @param	Integer			idTask
	 * @param	Ajax.Response	response
	 */
	onRefreshed: function(idTask, response) {
		this.addContextMenu(idTask);
	},



	/**
	 *	Add task context menu
	 *
	 *	@param	Integer	idTask
	 */
	addContextMenu: function(idTask) {
		Todoyu.Ext.project.Task.addContextMenu(idTask);
	},



	/**
	 *	Remove task context menu
	 *
	 *	@param	Integer	idTask
	 */
	removeContextMenu: function(idTask) {
		Todoyu.Ext.project.Task.removeContextMenu(idTask);
	},



	/**
	 *	Toggle display of details of given task
	 *
	 *	@param	Integer	idTask
	 */
	toggleDetails: function(idTask) {
			// If detail is loaded yet, send only a preference request
		if( Todoyu.Ext.project.Task.isDetailsLoaded(idTask) ) {
			var details = $('task-' + idTask + '-details');
				// Toggle details
			details.toggle();
				// Save preference
			this.saveTaskOpen(idTask, details.visible());
			
			this.onDetailsToggled(idTask);
		} else {
				// Details are not loaded yet
			this.loadDetails(idTask, '', this.onDetailsToggled.bind(this));
		}
	},
	
	
	
	/**
	 * Handler when details of a task have been toggled
	 * 
	 * @param	Integer			idTask
	 * @param	String			tab
	 * @param	Ajax.Response	response
	 */
	onDetailsToggled: function(idTask, tab, response) {
		Todoyu.Ext.project.Task.refreshExpandedStyle(idTask);
	},
	
	
	
	/**
	 * Load details of given task and append them to (have them shown inside) header of given task
	 *
	 *	@param	Integer	idTask
	 *	@param	String	tab
	 */
	loadDetails: function(idTask, tab, onComplete) {
		var url		= Todoyu.getUrl('portal', 'task');
		var options	= {
			'parameters': {
				'action':	'detail',
				'task':		idTask
			},
			'onComplete': Todoyu.Ext.project.Task.onDetailsLoaded.bind(this, idTask, tab, onComplete)
		};
		var target	= 'task-' + idTask + '-header';

			// Fade out the "not acknowledged" icon if its there
		Todoyu.Ext.project.Task.fadeAcknowledgeIcon.delay(1, idTask);

		Todoyu.Ui.append(target, url, options);
	},
	

	/**
	 *	Save task being opened state
	 *
	 *	@param	Integer	idTask
	 *	@param	Boolean	open
	 */
	saveTaskOpen: function(idTask, open) {
		var value = open ? 1 : 0;
		this.ext.savePref('taskOpen', value, idTask);
	},
	
	
	
	/**
	 * Save task after editing
	 * 
	 * @param	DomElement		form
	 */
	saveTask: function(form) {
		tinyMCE.triggerSave();

		$(form).request({
			'parameters': {
				'action':	'save'
			},
			'onComplete': this.onSaved.bind(this)
		});
	},
	
	
	
	/**
	 * Handler when task saved
	 * 
	 * @param	Ajax.Response		response
	 */
	onSaved: function(response) {
		Todoyu.Ext.project.Task.Edit.onSaved(response);
	},
	
	
	
	/**
	 * Cancel inline editing of the task
	 * 
	 * @param	Integer		idTask
	 */
	cancelEdit: function(idTask) {
		this.refresh(idTask);
	}
};