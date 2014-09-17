<?php
/**
 * Created by PhpStorm.
 * User: federico
 * Date: 14/09/14
 * Time: 18:02
 */

namespace TYPO3\T3registration\Decider;


interface DeciderInterface {

    public function getType();

    public function getPriority();

} 