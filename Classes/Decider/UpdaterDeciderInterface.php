<?php
/**
 * Created by PhpStorm.
 * User: federico
 * Date: 14/09/14
 * Time: 17:53
 */

namespace TYPO3\T3registration\Decider;


interface updaterDeciderInterface  {

    public function load(array $settings);

    public function allow(\TYPO3\T3registration\Domain\Model\User $user);

    public function isExcludable();
} 