<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 30/05/17
 * Time: 18:04
 */

namespace UserBundle\Model;

use FOS\UserBundle\Util\CanonicalFieldsUpdater;
use FOS\UserBundle\Util\PasswordUpdaterInterface;



abstract class UserManager implements UserManagerInterface
{
    private $passwordUpdater;
    private $canonicalFieldsUpdater;

    public function __construct(PasswordUpdaterInterface $passwordUpdater, CanonicalFieldsUpdater $canonicalFieldsUpdater)
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->canonicalFieldsUpdater = $canonicalFieldsUpdater;
    }

    /**
     * {@inheritdoc}
     */
    public function createUser()
    {
        $class = $this->getClass();
        $user = new $class();

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(array('emailCanonical' => $this->canonicalFieldsUpdater->canonicalizeEmail($email)));
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername($username)
    {
        return $this->findUserBy(array('usernameCanonical' => $this->canonicalFieldsUpdater->canonicalizeUsername($username)));
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsernameOrEmail($usernameOrEmail)
    {
        if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
            return $this->findUserByEmail($usernameOrEmail);
        }

        return $this->findUserByUsername($usernameOrEmail);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByConfirmationToken($token)
    {
        return $this->findUserBy(array('confirmationToken' => $token));
    }

    /**
     * {@inheritdoc}
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        $this->canonicalFieldsUpdater->updateCanonicalFields($user);
    }

    /**
     * {@inheritdoc}
     */
    public function updatePassword(UserInterface $user)
    {
        $this->passwordUpdater->hashPassword($user);
    }

    /**
     * @return PasswordUpdaterInterface
     */
    protected function getPasswordUpdater()
    {
        return $this->passwordUpdater;
    }

    /**
     * @return CanonicalFieldsUpdater
     */
    protected function getCanonicalFieldsUpdater()
    {
        return $this->canonicalFieldsUpdater;
    }
}