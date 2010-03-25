/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions gmbh
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSC License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

/**
 * Panel widget: Quick task
 */
Todoyu.Ext.portal.PanelWidget.QuickTask = {

	/**
	 *	Ext shortcut
	 */
	ext:	Todoyu.Ext.portal,



	/**
	 * Open 'add quicktask' popup dialog
	 */
	add: function() {
		Todoyu.Ext.project.QuickTask.openPopup(this.onAdded.bind(this));
	},



	/**
	 * Handle quicktask having been added
	 *
	 * @param	Integer		idTask
	 * @param	Integer		idProject
	 * @param	Boolean		started
	 */
	onAdded: function(idTask, idProject, started) {
		this.ext.Tab.refresh();
	}

};