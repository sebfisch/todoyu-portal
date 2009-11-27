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

Todoyu.Ext.portal.Tab = {

	ext: Todoyu.Ext.portal,
	
	cache: {},



	/**
	 *	onSelect event handler
	 *
	 *	@param	Object	event
	 *	@param	String	tabKey
	 */
	onSelect: function(event, tabKey) {
		this.showTab(tabKey, false);
	},



	/**
	 *	Show tab
	 *
	 *	@param	String	tabKey
	 *	@param	Boolean	refresh
	 */
	showTab: function(tabKey, refresh) {
		this.moveActiveTabContentToCache();
		
		if( ! this.isTabLoaded(tabKey) || refresh === true ) {
			this.updateTabContent(tabKey);
		} else {
			this.saveActiveTab(tabKey);
		}

		this.displayActiveTab(tabKey);
	},
	
	
	moveActiveTabContentToCache: function() {
		var activeTab	= Todoyu.Tabs.getActive('portal-tabs');
		var idTab		= activeTab.id.split('-').last();
		
			// Move content to cache
		this.cache[idTab] = $('portal-tabcontent-' + idTab).innerHTML;
		
			// Clear content
		$('portal-tabcontent-' + idTab).update('');
	},



	/**
	 *	Check wether given tab is loaded yet
	 *
	 *	@param	String	tabKey
	 */
	isTabLoaded: function(tabKey) {
		return Object.isUndefined(this.cache[tabKey]) === false;
		//console.log(typeof(this.cache[tabKey]));
		//return $('portal-tabcontent-' + tabKey).empty() === false;
	},



	/**
	 *	Save tab selection
	 *
	 *	@param	String	tabKey
	 */
	saveActiveTab: function(tabKey) {
		this.ext.savePref('tab', tabKey);
	},



	/**
	 *	Display (given) active tab
	 *
	 *	@param	String	tabKey
	 */
	displayActiveTab: function(tabKey) {
			// Hide all tabs
		$$('.portal-tabcontent').invoke('hide');
			// Update new active tab with content from cache
		$('portal-tabcontent-' + tabKey).update(this.cache[tabKey]);
			// Show new active tab
		$('portal-tabcontent-' + tabKey).show();
		
		Todoyu.Ext.project.ContextMenuTask.reattach();		
	},



	/**
	 *	Uppdate content of given tab
	 *
	 *	@param	String	tabKey
	 */
	updateTabContent: function(tabKey) {
		var url		= Todoyu.getUrl('portal', 'tab');
		var options	= {
			'parameters': {
				'action':	'update',
				'tab':		tabKey
			},
			'onComplete': this.onTabContentUpdated.bind(this, tabKey)
		};
		var target	= 'portal-tabcontent-' + tabKey;

		Todoyu.Ui.update(target, url, options);
	},
	
	onTabContentUpdated: function(tabKey, response) {
		this.cache[tabKey] = response.responseText;
	}






	/**
	 *	Activate and redraw tab head
	 *
	 *	@param	Integer	idTab
	 */
	/*
	updateAndActivateTabHead: function(idTab) {
		var url 	= Todoyu.getUrl('portal', 'tab');	// ext, action
		var options = {
			'parameters': {
				'tab': idTab,
				'action': 'updateTabHead'
			}
		};
		Todoyu.Ui.replace('portal-tabhead-' + idTab, url, options);
	}
	*/
};