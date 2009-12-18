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
 * Panel widget: quicktask
 *
 */

class TodoyuPanelWidgetQuicktask extends TodoyuPanelWidget implements TodoyuPanelWidgetIf {


	/**
	 * Constructor (init widget)
	 *
	 */
	public function __construct(array $config, array $params = array(), $idArea = 0) {
			// construct PanelWidget (init basic configuration)
		parent::__construct(
			'portal',							// ext key
			'quicktask',						// panel widget ID
			'LLL:panelwidget-quicktask.title',	// widget title text
			$config,							// widget config array
			$params,							// widget params
			$idArea								// area ID
		);

			// Add public and widget assets
		TodoyuPage::addExtAssets('portal', 'public');
		TodoyuPage::addExtAssets('portal', 'panelwidget-quicktask');

		$this->addHasIconClass();
	}


	public function renderContent() {
		$tmpl	= 'ext/portal/view/panelwidget-quicktask.tmpl';
		$data	= array(
			'id'	=> $this->getID()
		);

		$content	= render($tmpl, $data);

		$this->setContent($content);

		return $content;
	}


	/**
	 * Render
	 *
	 * @return	String
	 */
	public function render() {
		$this->renderContent();

		return parent::render();
	}



	/**
	 * Render quicktask form
	 *
	 * @return	String
	 */
	public function renderForm()	{
			// Construct form object
		$xmlPath	= 'ext/portal/config/form/quicktask.xml';
		$form		= TodoyuFormManager::getForm($xmlPath);

			// Preset (empty) form data
		$formData	= $form->getFormData();
		$formData	= TodoyuFormHook::callLoadData($xmlPath, $formData, 0);

		return $form->render();
	}



	public static function isAllowed() {
		return allowed('portal', 'panelwidget:quicktaskWizard');
	}

}
?>