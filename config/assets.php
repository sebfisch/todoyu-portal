<?php
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
 * Assets (JS, CSS, SWF, etc.) requirements for portal extension
 *
 * @package		Todoyu
 * @subpackage	Portal
 */

$CONFIG['EXT']['portal']['assets'] = array(
		// default assets: loaded all over the installation always
	'default' => array(
		'js' => array(

		),
		'css'	=> array(
			array(
				'file' 		=> 'ext/portal/assets/css/global.css',
			)
		)
	),

		// public assets: basis assets for this extension
	'public' => array(
		'js' => array(
			array(
				'file' 		=> 'ext/portal/assets/js/Ext.js',
				'position'	=> 100
			),
			array(
				'file' 		=> 'ext/portal/assets/js/Task.js',
				'position'	=> 101
			),
			array(
				'file' 		=> 'ext/portal/assets/js/Tab.js',
				'position'	=> 102
			)
		),
		'css' => array(
			array(
				'file' 		=> 'ext/portal/assets/css/ext.css',
				'position'	=> 100
			)
		)
	),


	// panel widgets assets


		// filter preset list
	'panelwidget-filterpresetlist' => array(
		'js' => array(
			array(
				'file' => 'ext/portal/assets/js/PanelWidgetFilterPresetList.js',
				'position' => 110,
			)
		),
		'css' => array(
			array(
				'file' => 'ext/portal/assets/css/panelwidget-filterpresetlist.css',
				'position' => 110,
			)
		)
	)

);


?>