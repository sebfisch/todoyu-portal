<?php
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

	// Add menu entries
if( allowed('portal', 'general:use') ) {
	TodoyuFrontend::setDefaultTab('portal');
	TodoyuFrontend::addMenuEntry('portal', 'LLL:portal.tab', '?ext=portal', 10);

	$tabsConfig	= TodoyuPortalManager::getTabsConfig();
	$pos		= 0;

	foreach($tabsConfig as $tabConfig) {
		$label	= TodoyuFunction::callUserFunction($tabConfig['labelFunc'], false);

		TodoyuFrontend::addSubmenuEntry('portal', $tabConfig['key'], $label, '?ext=portal&tab=' . $tabConfig['key'], $pos++);
	}

}

?>