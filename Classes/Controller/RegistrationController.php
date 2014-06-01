<?php
namespace TYPO3\T3registration\Controller;

    /***************************************************************
     *  Copyright notice
     *
     *  (c) 2014 Federico Bernardin <federico.bernardin@immaginario.com>, BFConsulting
     *
     *  All rights reserved
     *
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 3 of the License, or
     *  (at your option) any later version.
     *
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/
use TYPO3\CMS\Core\FormProtection\Exception;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;

/**
 *
 *
 * @package t3registration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class RegistrationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

    /**
     * userRepository
     *
     * @var \TYPO3\T3registration\Domain\Repository\UserRepository
     * @inject
     */
    protected $userRepository;

    /**
     * report
     *
     * @var \TYPO3\T3registration\Report\Report
     */
    protected $report;

    /**
     * action new
     *
     * @param \TYPO3\T3registration\Domain\Model\User $newUser
     * @dontvalidate $newUser
     * @return void
     */
    public function newAction(\TYPO3\T3registration\Domain\Model\User $newUser = NULL) {
        $this->checkConfiguration();
        $this->view->assign('newUser', $newUser);
    }

    private function checkConfiguration(){
        $this->report = new \TYPO3\T3registration\Report\Report();
        $this->report->setView($this->view);
        $this->report->checkConfiguration($this->settings);
        $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath('t3skin') . 'Resources/Public/Css/visual/element_message.css" />');
        $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath('t3skin') . 'Resources/Public/Css/structure/element_message.css" />');
        $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath('t3registration') . 'Resources/Public/Css/message.css" />');
    }

    /**
     * action create
     *
     * @param \TYPO3\T3registration\Domain\Model\User $newUser
     * @return void
     */
    public function createAction(\TYPO3\T3registration\Domain\Model\User $newUser) {
        $this->userRepository->add($newUser);
        $this->flashMessageContainer->add('Your new User was created.');
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function editAction(\TYPO3\T3registration\Domain\Model\User $user) {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function updateAction(\TYPO3\T3registration\Domain\Model\User $user) {
        $this->userRepository->update($user);
        $this->flashMessageContainer->add('Your User was updated.');
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function deleteAction(\TYPO3\T3registration\Domain\Model\User $user) {
        $this->userRepository->remove($user);
        $this->flashMessageContainer->add('Your User was removed.');
        $this->redirect('list');
    }


}

?>