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
 * Test for: TodoyuPortalManager
 *
 * @package		Todoyu
 * @subpackage	Portal
 */

/**
 * Test class for TodoyuPortalManager.
 * Generated by PHPUnit on 2010-03-12 at 16:38:16.
 */
class TodoyuPortalManagerTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var TodoyuPortalManager
	 */
	protected $object;

	/**
	 * @var Array
	 */
	private $array;



	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->object = new TodoyuPortalManager;

			// Get ID of a task
		$where	= 'deleted = 0 AND type = ' . TASK_TYPE_TASK;
		$idTask	= Todoyu::db()->getFieldValue('id', 'ext_project_task', $where, '', '', '0,1', 'id');

		$this->array	= array(
			'tabKeys'		=> array(
								0 => 'selection',
								1 => 'todo',
								2 => 'feedback',
								3 => 'appointment'
			),
			'taskIDs'	=> array(
				$idTask
			)
		);
	}



	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}



	/**
	 *	Test TodoyuPortalManager::addTab
	 */
	public function testAddTab() {
		$tabKey	= $this->array['tabKeys'][0];

		$this->object->addTab(
			$tabKey,
			'TodoyuPortalRenderer::getSelectionTabLabel',
			'TodoyuPortalRenderer::renderSelectionTabContent',
			10,
			array('portal/public')
		);

		$tabConfig	= Todoyu::$CONFIG['EXT']['portal']['tabs'][$tabKey];

		$this->assertType( 'array', $tabConfig );
		$this->assertGreaterThan( 0, sizeof($tabConfig) );

		$this->assertArrayHasKey( 'key', $tabConfig );

		$this->assertArrayHasKey( 'labelFunc', $tabConfig );
		$this->assertTrue( TodoyuFunction::isFunctionReference($tabConfig['labelFunc']), 'tab \'' . $tabKey . '\' labelFunc function reference validated');

		$this->assertArrayHasKey( 'contentFunc', $tabConfig, 'tab ' . $index . ' has \'' . $tabKey . '\' contentFunc' );
		$this->assertTrue( TodoyuFunction::isFunctionReference($tabConfig['contentFunc']), 'tab \'' . $tabKey . '\' contentFunc function reference validated');

		$this->assertArrayHasKey( 'assets', $tabConfig);
		$this->assertType( 'array', $tabConfig['assets'] );
		$this->assertGreaterThan( 0, $tabConfig['assets'] );
	}



	/**
	 * Test TodoyuPortalManager::getTabsConfig
	 */
	public function testGetTabsConfig() {
		$tabsConfig	= $this->object->getTabsConfig();

			// General check of returned config array
		$this->assertType('array', $tabsConfig);
		$this->assertGreaterThan( 0, sizeof($tabsConfig) );

			// Check all tab configs
		foreach($tabsConfig as $index => $tabConfig) {
			$key	= $tabConfig['key'];

			$this->assertArrayHasKey( 'key', $tabConfig );

			$this->assertArrayHasKey( 'labelFunc', $tabConfig );
			$this->assertTrue( TodoyuFunction::isFunctionReference($tabConfig['labelFunc']), 'tab ' . $index . ' (' . $key . ') labelFunc function reference validated');

			$this->assertArrayHasKey( 'contentFunc', $tabConfig );
			$this->assertTrue( TodoyuFunction::isFunctionReference($tabConfig['contentFunc']), 'tab ' . $index . ' (' . $key . ')  contentFunc function reference validated');

			$this->assertArrayHasKey( 'assets', $tabConfig );
			$this->assertType( 'array', $tabConfig['assets'] );
			$this->assertGreaterThan( 0, sizeof($tabConfig['assets']) );
		}
	}



	/**
	 * Test TodoyuPortalManager::getTabConfig
	 */
	public function testGetTabConfig() {
		$tabKeys	= $this->array['tabKeys'];

		foreach($tabKeys as $tabKey) {
			$tabConfig	= $this->object->getTabConfig($tabKey);

			$this->assertType('array', $tabConfig);
			$this->assertGreaterThan( 0, sizeof($tabConfig) );

			$this->assertArrayHasKey( 'key', $tabConfig );

			$this->assertArrayHasKey( 'labelFunc', $tabConfig );
			$this->assertTrue( TodoyuFunction::isFunctionReference($tabConfig['labelFunc']), 'tab \'' . $tabKey . '\' labelFunc function reference validated');

			$this->assertArrayHasKey( 'contentFunc', $tabConfig );
			$this->assertTrue( TodoyuFunction::isFunctionReference($tabConfig['contentFunc']), 'tab \'' . $tabKey . '\' contentFunc function reference validated');

			$this->assertArrayHasKey( 'assets', $tabConfig, 'tab \'' . $tabKey . '\' has assets' );
			$this->assertType('array', $tabConfig['assets']);
			$this->assertGreaterThan( 0, count($tabConfig['assets']) );
		}
	}



	/**
	 * Test TodoyuPortalManager::getTabs
	 */
	public function testGetTabs() {
		$tabs	= $this->object->getTabs();

		$this->assertType('array', $tabs);
		$this->assertGreaterThan( 0, count($tabs) );

		foreach($tabs as $tab) {
			$tabKey	= $tab['key'];

			$this->assertArrayHasKey( 'key', $tab );

			$this->assertArrayHasKey( 'labelFunc', $tab );
			$this->assertTrue( TodoyuFunction::isFunctionReference($tab['labelFunc']), 'tab \'' . $tabKey . '\' labelFunc function reference validated');

			$this->assertArrayHasKey( 'contentFunc', $tab );
			$this->assertTrue( TodoyuFunction::isFunctionReference($tab['contentFunc']), 'tab \'' . $tabKey . '\' contentFunc function reference validated');

			$this->assertArrayHasKey( 'position', $tab );

			$this->assertArrayHasKey( 'assets', $tab );
			$this->assertType('array', $tab['assets']);

			$this->assertArrayHasKey( 'id', $tab );
			$this->assertArrayHasKey( 'label', $tab );
		}
	}



	/**
	 * Test TodoyuPortalManager::getTaskContextMenuItems
	 */
	public function testGetTaskContextMenuItems() {
		$idTask	= $this->array['taskIDs'][0];

//		AREA must be EXTID_PORTAL, test should fail from here
		$items	= $this->object->getTaskContextMenuItems($idTask, array());

//		$this->assertGreaterThan( 0, count($items) );

		$this->markTestIncomplete('untestable because AREA simulation not at hand.');
	}



	/**
	 * Test TodoyuPortalManager::getSelectionCount
	 *
	 * @todo Implement testGetSelectionCount().
	 */
	public function testGetSelectionCount() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test Test TodoyuPortalManager::getSelectionType
	 *
	 * @todo Implement testGetSelectionType().
	 */
	public function testGetSelectionType() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}



	/**
	 * Test TodoyuPortalManager::addTabAssetsToPage
	 *
	 * @todo Implement testAddTabAssetsToPage().
	 */
	public function testAddTabAssetsToPage() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
		  'This test has not been implemented yet.'
		);
	}

}
?>