<?php
/**
 * Created by PhpStorm.
 * User: federico
 * Date: 14/09/14
 * Time: 18:39
 */

namespace TYPO3\T3registration\Decider\UpdaterDecider;

use \TYPO3\T3registration\Decider\UpdaterDeciderAbstract;


class DummyUpdaterDecider extends UpdaterDeciderAbstract{

    public function allow(\TYPO3\T3registration\Domain\Model\User $user){
        return true;
    }
} 