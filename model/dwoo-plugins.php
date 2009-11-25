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
 * Portal's Dwoo plugins
 *
 * @package		Todoyu
 * @subpackage	Portal
 */


/**
 * Convert given amount of seconds to hour format
 *
 * @package		Todoyu
 * @subpackage	Portal
 *
 * @param	Dwoo		$dwoo
 * @param	Integer		$seconds
 * @return	String
 */
function Dwoo_Plugin_hour_format(Dwoo $dwoo, $seconds, $roundUp = false) {
	if( $roundUp == true )	{
		$seconds = TodoyuTimetrackingManager::calculateBillableTime( $seconds );
	}

	return Dwoo_Plugin_Workload($dwoo, $seconds);
}

?>