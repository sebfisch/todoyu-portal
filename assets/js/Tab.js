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
		if( ! this.isTabLoaded(tabKey) || refresh === true ) {
			this.updateTabContent(tabKey);
		} else {
			this.saveActiveTab(tabKey);
		}

		this.displayActiveTab(tabKey);
	},



	/**
	 *	Check wether given tab is loaded yet
	 *
	 *	@param	String	tabKey
	 */
	isTabLoaded: function(tabKey) {
		return $('portal-tabcontent-' + tabKey).empty() === false;
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
		$$('.portal-tabcontent').invoke('hide');
		$('portal-tabcontent-' + tabKey).show();
		$('portal-tabcontent-' + tabKey).removeClassName('hidden');
		Todoyu.Tabs.setActive('portal-tabhead-' + tabKey);
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
				'cmd': 'update',
				'tab': tabKey
			}
		};
		var target	= 'portal-tabcontent-' + tabKey;

		Todoyu.Ui.update(target, url, options);
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
				'cmd': 'updateTabHead'
			}
		};
		Todoyu.Ui.replace('portal-tabhead-' + idTab, url, options);
	}
	*/
};