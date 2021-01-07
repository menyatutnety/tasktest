<?php

class Cookie
{
    const MONTH = 60 * 60 * 24 * 30;

    public static function create(Users $users, $numberUser)
    {
        $cookieKey = $users::generateSalt(20);
        $user = $users->addCookieKey($numberUser, $cookieKey);
        setcookie("login", (string)$user->user[$numberUser]->login, time() + self::MONTH, '/');
        setcookie("cookieKey", $cookieKey, time() + self::MONTH, '/');
        header("Refresh:0");
    }
    public static function destroy()
    {
        setcookie("login", '', time(), '/');
        setcookie("cookieKey", '', time(), '/');
    }
}