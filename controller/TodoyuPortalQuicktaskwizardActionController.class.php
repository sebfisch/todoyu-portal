<?php

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