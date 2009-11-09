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
 * Panel widget: Quick task wizard
 */
Todoyu.Ext.portal.PanelWidget.QuicktaskWizard = {

	ext: Todoyu.Ext.portal,

	fieldStart: 'quicktask-field-start-tracking',

	fieldDone: 'quicktask-field-task-done',


	/**
	 *	Toggle quick task popup
	 */
	openPopup: function() {
		var url		= Todoyu.getUrl('portal', 'quicktaskwizard');
		var options	= {
			'parameters': {
				'cmd': 'popup'
			}
		};

		Todoyu.Popup.openWindow('popupCreateEvent', 'Quicktask wizard', 420, 390, url, options);
	},



	/**
	 *	Save (quick-) task
	 *
	 *	@param	String	form
	 */
	save: function(form)	{
		tinyMCE.triggerSave();

		$(form).request({
			'parameters': {
				'cmd': 'save'
			},
			'onComplete': this.onSaved.bind(this, form)
		});

		return false;
	},

	onSaved: function(form, response) {
		var error = response.getTodoyuHeader('error') == 1;
		var start = response.getTodoyuHeader('start') == 1;

		if( error ) {
			$(form).replace(response.responseText);
		} else {
			Todoyu.Popup.close('popupCreateEvent');

			if( start ) {
				var idTask= response.getTodoyuHeader('idTask');
				Todoyu.Ext.timetracking.Task.start(idTask);
			}
		}
	},

	preventStartDone: function(key, field) {
		if( key === 'start' ) {
			if( $(this.fieldDone).checked ) {
				$(this.fieldDone).checked = false;
			}
		}
		if( key === 'done' ) {
			if( $(this.fieldStart).checked ) {
				$(this.fieldStart).checked = false;
			}
		}
	},

	/**
	 *	Disable checkbox 'task done'
	 *
	 *	@param	Element	input
	 */
	disableCheckboxTaskDone: function(input)	{
		if(input.getValue() == 1)	{
			$('quicktask-0-field-is-task-done').disabled = true;
		} else {
			$('quicktask-0-field-is-task-done').disabled = false;
		}
	},



	/**
	 *	Disable checkbox 'start workload'
	 *
	 *	@param	Element	input
	 */
	disableCheckboxStartWorkload: function(input)	{
		if(input.getValue() == 1)	{
			$('quicktask-0-field-start-workload').disabled = true;
		} else {
			$('quicktask-0-field-start-workload').disabled = false;
		}
	}
};