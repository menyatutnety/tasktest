<?php
class MainController
{
    
    public static function existSession()
    {
        if (isset($_SESSION['auth']) && $_SESSION['auth']) {
            $users = new Users();
            if ($users->equalitySessionKey($_SESSION['login'], $_SESSION['sessionKey'])) {
                return true;
            }
        }
        return false;
    }
    public static function isGuest()
    {
        if (self::existSession()) return false;
        if (isset($_COOKIE['login']) && $_COOKIE['login'] != '') {
            $users = new Users();
            if ($users->equalityCookieKey($_COOKIE['login'], $_COOKIE['cookieKey'])) {
                $numberUser = (int)$users->searchObjectNumberByLogin($_COOKIE['login']);
                Session::create($users, $numberUser);
                return false;
            }
        }
        return true;
    }

    public static function responseJson($param = [])
    {
        header('Content-type: application/json');
        echo json_encode($param);
        exit;
    }
    public function render($view)
    {
        $view = trim($view, '/');
        include MAIN . '/view/header.php';
        include MAIN . '/view/' . $view . '.php';
        include MAIN . '/view/footer.php';

        return true;
    }
    public function redirect($view)
    {
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        header("Location: http://$host$uri/$view");
        die;
    }
}