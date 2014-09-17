<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) Federico Bernardin 2014
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/


namespace TYPO3\T3registration\Utility;


use TYPO3\T3registration\Decider\DeciderInterface;

/**
 * Class DeciderUtility manages the operation of decider (add, remove, get, etc...)
 *
 * @author Federico Bernardin <federico@bernardin.it>
 * @package TYPO3\T3registration\Utility
 */
class DeciderUtility
{

    static private $deciders = array();

    /**
     * Add the decider
     * @param DeciderInterface $decider the decider to add
     */
    static public function addDecider($decider)
    {
        if (class_exists($decider) && !array_key_exists($decider, self::$deciders)) {
            $deciderClass = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($decider);
            if ($deciderClass instanceof DeciderInterface) {
                self::$deciders[$deciderClass->getType()][$decider] = $deciderClass;
            }
        }
    }

    /**
     * Remove all decider
     */
    static public function removeAll()
    {
        self::$deciders = array();
    }

    public function removeAllFromType($type)
    {
        unset(self::$deciders[$type]);
    }

    /**
     * Remove single decider from key
     * @param DeciderInterface $decider
     */
    static public function removeDecider($decider)
    {
        $type = $decider->getType();
        if (array_key_exists($decider, self::$deciders[$type])) {
            unset(self::$deciders[$type][$decider]);
        }
    }

    /**
     * @return array validators array
     */
    static public function getDeciders($type)
    {
        return self::sortDecidersByPriority(self::$deciders[$type]);
    }

    static protected function sortDecidersByPriority($deciders){
        $tempSortedDeciders = array();
        $sortedDeciders = array();
        foreach($deciders as $decider){
            $tempSortedDeciders[$decider->getPriority()][] = $decider;
        }
        krsort($tempSortedDeciders);
        foreach($tempSortedDeciders as $priority){
            foreach($priority as $decider){
                $sortedDeciders[] = $decider;
            }
        }
        return $sortedDeciders;
    }

    /**
     * return specific decider object or null id it's not found
     * @param string $key key to search
     * @param string $type type of decider
     * @return null|DeciderInterface the specific decider or null
     */
    static public function getDecider($key,$type)
    {
        if (isset(self::$deciders[$type][$key])) {
            return self::$deciders[$type][$key];
        } else {
            return null;
        }
    }
}