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
 * Panel widget: filter preset list
 */
Todoyu.Ext.portal.PanelWidget.FilterPresetList = {

	ext: Todoyu.Ext.portal,

	list: 'panelwidget-filterpresetlist-list',



	/**
	 *	Init
	 */
	init: function() {
		this.installObserver();
	},



	/**
	 *	Install observer
	 */
	installObserver: function() {
		$(this.list).observe('change', this.onSelectionChange.bindAsEventListener(this));
	},



	/**
	 *	onSelectionChange event handler
	 *
	 *	@param	Object	event
	 */
	onSelectionChange: function(event) {
		var onComplete = this.showRefreshedFilterTab.bind(this);

		this.saveFiltersets(onComplete);
	},



	/**
	 *	Get filtersets
	 */
	getFiltersets: function() {
		return $F(this.list);
	},



	/**
	 *	Manage filtersets
	 */
	manageFiltersets: function() {
		Todoyu.goTo('search', 'ext');
	},



	/**
	 *	Save filtersets
	 *
	 *	@param	String	onComplete
	 */
	saveFiltersets: function(onComplete) {
		var value 		= this.getFiltersets().join(',');

		Todoyu.Pref.save('portal', 'filtersets', value, 0, onComplete);
	},



	/**
	 *	Show refreshed filter tab
	 */
	showRefreshedFilterTab: function() {
		this.ext.Tab.showTab(0, true);
	},



	/**
	 *	Evoked by onchange of filter presets panel widget, does:
	 *  -Switch (store) active filters
	 *  -Redraw and activate (if not yet) "Selection" tab
	 *
	 *	@param	Element	elFilterSelector
	 */
	changeAndActivateSelectionFiltersets: function(elFilterSelector) {
		var filtersets = this.getSelectedFiltersets(elFilterSelector);
		filtersets = filtersets.join(',');

		var url 	= Todoyu.getUrl('portal', 'preference');	// ext, action
		var options = {
			'parameters': {
				'name': 'tabFiltersets',
				'value': filtersets
			},
			'onComplete': function(response) {
					// Refresh task list of 'Selection' tab and have the tab refreshed/ activated
				Todoyu.Ext.portal.Tab.activateAndUpdateContent(0);
			}
		};

		Todoyu.send(url, options);
	}
};