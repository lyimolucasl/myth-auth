<?php namespace Myth\Auth\Authentication\Passwords;

use Myth\Auth\Config\Auth;
use Myth\Auth\Entities\User;

class PasswordValidator
{
    /**
     * @var Auth
     */
    protected $config;

    protected $error;

    protected $suggestion;

    public function __construct(Auth $config)
    {
        $this->config = $config;
    }

    /**
     * Checks a password against all of the Validators specified
     * in `$passwordValidators` setting in Config\Auth.php.
     *
     * @param string $password
     * @param User   $user
     *
     * @return bool
     */
    public function check(string $password, User $user): bool
    {
        $valid = false;

        foreach ($this->config->passwordValidators as $className)
        {
            $class = new $className();
            $class->setConfig($this->config);

            if ($class->check($password, $user) === false)
            {
                $this->error = $class->error();
                $this->suggestion = $class->suggestion();

                break;
            }
        }

        return $valid;
    }

    /**
     * Returns the current error, as defined by validator
     * it failed to pass.
     *
     * @return mixed
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Returns a string with any suggested fix
     * based on the validator it failed to pass.
     *
     * @return mixed
     */
    public function suggestion()
    {
        return $this->suggestion;
    }


}