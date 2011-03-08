/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011, snowflake productions GmbH, Switzerland
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
 * Panel widget: Quick task
 */
Todoyu.Ext.portal.PanelWidget.QuickTask = {

	/**
	 * Ext shortcut
	 *
	 * @var	{Object}	ext
	 */
	ext:	Todoyu.Ext.portal,



	/**
	 * Open 'add quicktask' popup dialog
	 *
	 * @method	add
	 */
	add: function() {
		Todoyu.Ext.project.QuickTask.openPopup(this.onAdded.bind(this));
	},



	/**
	 * Handle quicktask having been added
	 *
	 * @method	onAdded
	 * @param	{Number}		idTask
	 * @param	{Number}		idProject
	 * @param	{Boolean}		started
	 */
	onAdded: function(idTask, idProject, started) {
		this.ext.Tab.refresh();
	}

};