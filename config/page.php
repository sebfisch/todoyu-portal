<?php

	// Add menu entries
if( allowed('portal', 'use') ) {
	TodoyuFrontend::setDefaultTab('portal');
	TodoyuFrontend::addMenuEntry('portal', 'LLL:portal.tab', '?ext=portal', 10);

	$portalTabs	= TodoyuPortalManager::getTabs();
	foreach($portalTabs as $idTab => $tabData) {
		TodoyuFrontend::addSubmenuEntry('portal', 'portal', $tabData['title'], '?ext=portal&tab=' . $idTab, 50 + $idTab, $idTab);
	}
}

?>