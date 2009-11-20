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

class TodoyuPortalQuicktaskwizardActionController extends TodoyuActionController {

	// Toggle quicktask popup
	public function popupAction(array $params) {
		return TodoyuPanelWidgetQuicktaskWizard::renderForm();
	}

	// Save quicktask
	public function saveAction(array $params) {
		$formData	= $params['quicktask'];

			// Construct form object
		$xmlPath	= 'ext/portal/config/form/quicktask.xml';
		$form		= TodoyuFormManager::getForm($xmlPath);

			// Set form data
		$form->setFormData($formData);

			// Valdiate, save workload record / re-render form
		if( $form->isValid() )	{
			$storageData	= $form->getStorageData();

			$idTask	= TodoyuPanelWidgetQuicktaskWizard::saveQuicktask($storageData);

			TodoyuHeader::sendTodoyuHeader('idTask', $idTask);
			TodoyuHeader::sendTodoyuHeader('start', $storageData['start_tracking'] == 1);
		} else {
			TodoyuHeader::sendTodoyuHeader('error', true);

			return $form->render();
		}
	}

}

?>