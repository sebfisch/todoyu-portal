<?php
/****************************************************************************
* todoyu is published under the BSD License:
* http://www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2010, snowflake productions GmbH, Switzerland
* All rights reserved.
*
* This script is part of the todoyu project.
* The todoyu project is free software; you can redistribute it and/or modify
* it under the terms of the BSD License.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the BSD License
* for more details.
*
* This copyright notice MUST APPEAR in all copies of the script.
*****************************************************************************/

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
//		$this->object = new TodoyuPortalManager;
//
//			// Get ID of a task
//		$where	= 'deleted = 0 AND type = ' . TASK_TYPE_TASK;
//		$idTask	= Todoyu::db()->getFieldValue('id', 'ext_project_task', $where, '', '', '0,1', 'id');
//
//		$this->array	= array(
//			'tabKeys'		=> array(
//								0 => 'selection',
//								1 => 'todo',
//								2 => 'feedback',
//								3 => 'appointment'
//			),
//			'taskIDs'	=> array(
//				$idTask
//			)
//		);
	}



	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}



	/**
	 * Test TodoyuPortalManager::addTab
	 */
	public function testAddTab() {
			// Add tab as first
		TodoyuPortalManager::addTab('dummy', 'TodoyuPortalRenderer::getSelectionTabLabel', 'TodoyuPortalRenderer::renderSelectionTabContent', 1);

		$tab	= TodoyuPortalManager::getTabConfig('dummy');

		$this->assertEquals('dummy', $tab['key']);
	}



	/**
	 * Test TodoyuPortalManager::getTabsConfig
	 */
	public function testGetTabsConfig() {
		TodoyuPortalManager::addTab('test', 'TodoyuPortalRenderer::getSelectionTabLabel', 'TodoyuPortalRenderer::renderSelectionTabContent', 0);

		$tabs	= TodoyuPortalManager::getTabsConfig();

		$this->assertEquals('test', $tabs[0]['key']);
		$this->assertEquals('TodoyuPortalRenderer::getSelectionTabLabel', $tabs[0]['labelFunc']);
		$this->assertEquals('TodoyuPortalRenderer::renderSelectionTabContent', $tabs[0]['contentFunc']);
		$this->assertEquals(0, $tabs[0]['position']);
	}



	/**
	 * Test TodoyuPortalManager::getTabConfig
	 */
	public function testGetTabConfig() {
		TodoyuPortalManager::addTab('special', 'TodoyuPortalRenderer::getSelectionTabLabel', 'TodoyuPortalRenderer::renderSelectionTabContent');

		$tab	= TodoyuPortalManager::getTabConfig('special');

		$this->assertEquals('TodoyuPortalRenderer::renderSelectionTabContent', $tab['contentFunc']);
	}



	/**
	 * Test TodoyuPortalManager::getTabs
	 */
	public function testGetTabs() {
		$tabs	= TodoyuPortalManager::getTabs();

		$this->assertArrayHasKey('label', $tabs[0]);
		$this->assertArrayHasKey('id', $tabs[0]);

		$pos	= $tabs[0]['position'];

		foreach($tabs as $tab) {
			$this->assertGreaterThanOrEqual($pos, $tab['position']);
			$pos = $tab['position'];

			$this->assertTrue( TodoyuFunction::isFunctionReference($tab['labelFunc']), $tab['key']);
			$this->assertTrue( TodoyuFunction::isFunctionReference($tab['contentFunc']));
		}
	}



	/**
	 * Test TodoyuPortalManager::getTaskContextMenuItems
	 */
	public function testGetTaskContextMenuItems() {
		$items	= array();

		$items	= TodoyuPortalManager::getTaskContextMenuItems(0, $items);

		$this->assertType('array', $items);
		$this->assertEquals(0, sizeof($items));
	}



	/**
	 * Test TodoyuPortalManager::getSelectionCount
	 *
	 * @todo Implement testGetSelectionCount().
	 */
	public function testGetSelectionCount() {
		$count	= TodoyuPortalManager::getSelectionCount();

		$this->assertType('int', $count);
	}

}

?>