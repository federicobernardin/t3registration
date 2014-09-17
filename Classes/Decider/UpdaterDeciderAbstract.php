<?php
/**
 * Created by PhpStorm.
 * User: federico
 * Date: 14/09/14
 * Time: 17:56
 */

namespace TYPO3\T3registration\Decider;


class updaterDeciderAbstract implements UpdaterDeciderInterface, DeciderInterface{

    protected $settings;

    public function load(array $settings)
    {
        $this->settings = $settings;
    }

    public function allow(\TYPO3\T3registration\Domain\Model\User $user)
    {
        // TODO: Implement allow() method.
    }

    public function getPriority()
    {
        return 100;
    }

    public function isExcludable()
    {
        return false;
    }

    public function getType()
    {
        return 'update';
    }
}