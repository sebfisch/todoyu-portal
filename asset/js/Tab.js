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
 * @module	Portal
 */

Todoyu.Ext.portal.Tab = {

	/**
	 * Reference to extension
	 *
	 * @property	ext
	 * @type		Object
	 */
	ext:	Todoyu.Ext.portal,



	/**
	 * onSelect event handler
	 *
	 * @method	onSelect
	 * @param	{Event}		event
	 * @param	{String}	tabKey
	 */
	onSelect: function(event, tabKey) {
		this.showTab(tabKey);
	},



	/**
	 * Show tab
	 *
	 * @method	showTab
	 * @param	{String}		tabKey
	 * @param	{Boolean}		activateTab
	 * @param	{Array}			extraParams
	 */
	showTab: function(tabKey, activateTab, extraParams) {
		var url		= Todoyu.getUrl('portal', 'tab');
		var options	= {
			parameters: {
				action:	'update',
				'tab':		tabKey
			},
			onComplete: this.onTabShowed.bind(this, tabKey)
		};

		var target	= 'content-body';

			// Add extra params
		if( typeof(extraParams) === 'object' ) {
			options.parameters.params = Object.toJSON(extraParams);
		}

		Todoyu.Ui.update(target, url, options);

		if( activateTab === true ) {
			Todoyu.Tabs.setActive('portal', tabKey);
		}
	},



	/**
	 * Handler when tab is showed and updated
	 *
	 * @method	onTabShowed
	 * @param	{String}				tabKey
	 * @param	{Ajax.Response}		response
	 */
	onTabShowed: function(tabKey, response) {
		var numItems	= response.getTodoyuHeader('items');

		if( numItems ) {
			this.updateNumResults(tabKey, numItems);
		}

		Todoyu.Hook.exec('portal.tab.showed', tabKey);
	},



	/**
	 * Update the label of a tab
	 *
	 * @method	setTabLabel
	 * @param	{String}		tabKey
	 * @param	{String}		newLabel
	 */
	setTabLabel: function(tabKey, newLabel) {
		$('portal-tab-' + tabKey + '-label').down('span.labeltext').update(newLabel);
	},



	/**
	 * Update the number of results in the tablabel
	 * Replace the number in the brackets
	 * @example	'Tasks (43)' => 'Tasks (33)'
	 *
	 * @method	updateNumResults
	 * @param	{String}			tabKey
	 * @param	{Number}			numResults
	 */
	updateNumResults: function(tabKey, numResults) {
		var labelEl	= $('portal-tab-' + tabKey + '-label').down('span.labeltext');
		var label	= labelEl.innerHTML;
		var pattern	= /\(\d+\)/;
		var replace	= '(' + numResults + ')';

		labelEl.update(label.replace(pattern, replace));
	},



	/**
	 * Get number of results showed in the tab (parsed from tab label)
	 *
	 * @method	getNumResults
	 * @param	{String}	tabKey
	 */
	getNumResults: function(tabKey) {
		var labelEl	= $('portal-tab-' + tabKey + '-label').down('span.labeltext');
		var pattern	= /\((\d+)\)/;
		var result	= labelEl.innerHTML.match(pattern);

		return result[1];
	},



	/**
	 * Get key of currently active tab
	 *
	 * @method	getActiveTab
	 * @return	{String}
	 */
	getActiveTab: function() {
		return Todoyu.Tabs.getActiveKey('portal');
	},



	/**
	 * Refresh tabs display
	 *
	 * @method	refresh
	 */
	refresh: function() {
		this.showTab(this.getActiveTab());
	}

};