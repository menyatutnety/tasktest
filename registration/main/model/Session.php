<?php

class Session
{
      @param Users 
    public static function create(Users $users, $number)
    {
        $sessionKey = Users::generateSalt(20);
        $user = $users->addSessionKey($number, $sessionKey);

        $_SESSION['auth'] = true;
        $_SESSION['login'] = (string)$user->user[$number]->login;
        $_SESSION['name'] = (string)$user->user[$number]->name;
        $_SESSION['sessionKey'] = (string)$user->user[$number]->session_key;
    }
    public static function delete()
    {
        unset($_SESSION['auth']);
        unset($_SESSION['login']);
        unset($_SESSION['name']);
        unset($_SESSION['sessionKey']);
        session_destroy();
    }

}