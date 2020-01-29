<?php


namespace App\Auth\Passwords;


use Closure;
use Illuminate\Auth\Passwords\PasswordBrokerManager;

class CustomPasswordBrokerManager extends PasswordBrokerManager
{

    // Override the createTokenRepository function to return your
    // custom token repository instead of the standard one
    protected function createTokenRepository(array $config)
    {
        $key = $this->app['config']['app.key'];

        if (\Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $connection = isset($config['connection']) ? $config['connection'] : null;

        return new CustomDatabaseTokenRepository(
            $this->app['db']->connection($connection),
            $this->app['hash'],
            $config['table'],
            $key,
            $config['expire']
        );
    }
    /**
     * Send a password reset link to a user.
     *
     * @param array $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        // TODO: Implement sendResetLink() method.
        parent::sendResetLink($credentials);
    }

    /**
     * Reset the password for the given token.
     *
     * @param array $credentials
     * @param \Closure $callback
     * @return mixed
     */
    public function reset(array $credentials, Closure $callback)
    {
        // TODO: Implement reset() method.
        parent::reset($credentials,$callback);
    }
}
