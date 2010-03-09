<?php

	// Add menu entries
if( allowed('portal', 'general:area') ) {
	TodoyuFrontend::setDefaultTab('portal');
	TodoyuFrontend::addMenuEntry('portal', 'LLL:portal.tab', '?ext=portal', 10);

	$tabsConfig	= TodoyuPortalManager::getTabsConfig();
	$pos		= 0;

	foreach($tabsConfig as $tabConfig) {
		$label	= TodoyuDiv::callUserFunction($tabConfig['labelFunc'], false);

		TodoyuFrontend::addSubmenuEntry('portal', $tabConfig['key'], $label, '?ext=portal&tab=' . $tabConfig['key'], $pos++);
	}

}

?>