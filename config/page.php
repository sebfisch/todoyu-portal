<?php

	// Add menu entries
if( allowed('portal', 'general:use') ) {
	TodoyuFrontend::setDefaultTab('portal');
	TodoyuFrontend::addMenuEntry('portal', 'LLL:portal.tab', '?ext=portal', 10);

	$tabsConfig	= TodoyuPortalManager::getTabsConfig();
	$pos		= 0;

	foreach($tabsConfig as $tabConfig) {
		$label	= TodoyuDiv::callUserFunction($tabConfig['labelFunc'], false);

		TodoyuFrontend::addSubmenuEntry('portal', $tabConfig['key'], $label, '?ext=portal&tab=' . $tabConfig['key'], $pos++);
	}


//
//	$portalTabs	= TodoyuPortalManager::getTabs();
//
//	TodoyuDebug::printHtml($portalTabs);
//	foreach($portalTabs as $idTab => $tabData) {
//		TodoyuFrontend::addSubmenuEntry('portal', 'portal', $tabData['title'], '?ext=portal&tab=' . $idTab, 50 + $idTab, $idTab);
//	}
}

?>