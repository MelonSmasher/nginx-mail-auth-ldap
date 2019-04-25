<?php
require __DIR__ . '/../vendor/autoload.php';

use Adldap\Adldap;
use Adldap\Auth\BindException;
use Adldap\Auth\UsernameRequiredException;
use Adldap\Auth\PasswordRequiredException;

class Auth
{
    /**
     * @var object
     */
    protected $config;

    /**
     * Auth constructor.
     * @param array $config
     */
    function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * @return bool
     */
    public function authuser()
    {
        try {
            $ad = new Adldap();
            $ad->addProvider($this->config);
            $provider = $ad->connect();
            if ($provider->auth()->attempt($this->config['username'], $this->config['password'], $bindAsUser = true)) {
                return true;
            }
            return false;
        } catch (BindException $e) {
            return false;
        } catch (UsernameRequiredException $e) {
            return false;
        } catch (PasswordRequiredException $e) {
            return false;
        }
    }
}