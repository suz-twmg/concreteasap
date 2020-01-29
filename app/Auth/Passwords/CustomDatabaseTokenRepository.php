<?php


namespace App\Auth\Passwords;


use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class CustomDatabaseTokenRepository extends DatabaseTokenRepository
{
    // Overrides the standard token creation function
    public function createNewToken()
    {
        return substr(parent::createNewToken(), 0, 8);
    }
}
