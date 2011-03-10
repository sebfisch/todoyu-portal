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

/**
 * Panel widget: filter preset list
 */
Todoyu.Ext.portal.PanelWidget.FilterPresetList = {

	/**
	 * Reference to extension
	 *
	 * @property	ext
	 * @type		Object
	 */
	ext:	Todoyu.Ext.portal,

	/**
	 * List of all select items with filtersets
	 *
	 * @property	lists
	 * @type		Object
	 */
	lists: {},



	/**
	 * Init
	 *
	 * @method	init
	 */
	init: function() {
			// Find all lists
		var lists = $('panelwidget-filterpresetlist-content').select('select');

			// Add lists to internal storage and install an onchange observer
		lists.each(function(list){
			var type = list.id.split('-').last();
			this.lists[type] = list;

			list.observe('change', this.onSelectionChange.bindAsEventListener(this, type));
		}.bind(this));
	},



	/**
	 * Handler when selection in one of the lists is changed
	 *
	 * @method	onSelectionChange
	 * @param	{Event}			event
	 * @param	{String}		type		List type
	 */
	onSelectionChange: function(event, type) {
			// Unselect all other option groups
		this.unselectOtherTypes(type);

			// Add params for tab refresh
		var params	= {
			'filtersets': 	this.getFiltersets(),
			'type':			type
		};

			// Refresh tab content
		this.ext.Tab.showTab('selection', true, params);
	},



	/**
	 * Get selected filterset IDs
	 *
	 * @method	getFiltersets
	 * @return	{Array}
	 */
	getFiltersets: function() {
		return $H(this.lists).collect(function(pair){
			return $F(pair.value);
		}).flatten();
	},



	/**
	 * Deselect all options in the other lists, because only one type can be active
	 *
	 * @method	unselectOtherTypes
	 * @param	{String}		type
	 */
	unselectOtherTypes: function(type) {
		$H(this.lists).each(function(type, pair){
			if( pair.key !== type ) {
				pair.value.select('option').each(function(option){
					option.selected = false;
				});
			}
		}.bind(this, type));
	},



	/**
	 * Manage filtersets
	 *
	 * @method	manageFiltersets
	 */
	manageFiltersets: function() {
		Todoyu.goTo('search', 'ext');
	}

};