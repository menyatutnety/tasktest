<?php

class Users
{
    public $dbUsers;
    public $errorsValidate = null;

    public function __construct()
    {
        $this->dbUsers = Db::getUsersTable();
    }
    public function signUpUser($signUpForm)
    {
        $this->validateForSignUp($signUpForm);
        if ($this->errorsValidate !== null) {
            return $this->errorsValidate;
        } else {
            $newUser = $this->dbUsers->addChild('user');
            $newUser->addChild('login', $signUpForm['login']);
            $newUser->addChild('email', $signUpForm['email']);
            $newUser->addChild('name', $signUpForm['name']);
            $newUser->addChild('salt', self::generateSalt());
            $newUser->addChild('password_hash', $this->makeSaltyPassword($signUpForm['password'], $newUser->salt));
            $this->dbUsers->asXML(Db::getTablePatch()['Users']);
            return true;
        }
    public function signInUser($signInForm)
    {
        $this->validateForSignIn($signInForm);
        if ($this->errorsValidate === null) {
            return true;
        } else {
            return $this->errorsValidate;
        }
    }
    public function validateForSignIn(array $signInForm)
    {
        $signInForm = $this->validateNotNull($signInForm);
        $signInForm = $this->screening($signInForm);
        $user = $this->searchByLogin($signInForm['login']);
        if ($user === false || !$this->equalityPassword($user, $signInForm['password'])) {
            $this->errorsValidate[] = 'ошибка в логине или пароле';
        }
    }
    public function validateForSignUp($signUpForm)
    {
        $signUpForm = $this->validateNotNull($signUpForm);
        $this->validateEmale($signUpForm['email']);
        $signUpForm = $this->screening($signUpForm);
        $this->validatePassword($signUpForm['password'], $signUpForm['confirm_password']);
        $this->validateUniqueLogin($signUpForm['login']);
        $this->validateUniqueEmail($signUpForm['email']);
    }
    public function validateNotNull(array $param)
    {
        $newParam = [];
        $nullFlaf = false;
        foreach ($param as $key => $value) {
            $value = trim($value);
            if (strlen($value) == 0) {
                $nullFlaf = true;
            } elseif (strlen($value) < 2) {
                $this->errorsValidate[] = 'поле "' . $key . '" не должно быть короче двух символов';
            } elseif (strlen($value) > 50) {
                $this->errorsValidate[] = 'поле "' . $key . '" не должно быть длинее 50 символов';
            }
            $newParam[$key] = $value;
        }

        if ($nullFlaf) {
            $this->errorsValidate[] = 'пожалуйста, заполните все поля';
        }
        return $newParam;
    }
         public function screening(array $param)
    {
        $newParam = [];
        foreach ($param as $key => $value) {
            $newParam[$key] = htmlspecialchars($value);
        }
        return $newParam;
    }
    public function validateEmale($email)
    {
        if (preg_match('/.+@.+\..+/i', $email) == 0) {
            $this->errorsValidate[] = '"' . $email . '" не соответствует формату email';
            return false;
        }
        return true;
    }
      @param string $
      @param string 
      @return bool
     
    public function validatePassword($password, $confirm_password)
    {
        if ($password !== $confirm_password) {
            $this->errorsValidate[] = 'пароль и подтверждение не совпадают';
            return false;
        }
        return true;
    }
     @param object 
      @param string 
      @return bool
     
    public function equalityPassword($user, $password)
    {
        $saltyPassword = $this->makeSaltyPassword($password, $user->salt);
        if ($saltyPassword == $user->password_hash) {
            return true;
        }
        return false;
    }
      @param string 
      @param string 
      @return string
     
    public function makeSaltyPassword($password, $salt)
    {
        return md5($salt . md5($password));
    }
      @param int
      @return bool|string
     
    public static function generateSalt($lehgth = 10)
    {
        return substr(md5(mt_rand()), 0, $lehgth);
    }
      @param string 
      @return bool
     
    public function validateUniqueLogin($login)
    {
        $user = $this->searchByLogin($login);
        if ($user !== false) {
            $this->errorsValidate[] = 'пользователь с логином "' . $login . '"" уже есть';
            return false;
        }
        return true;
    }
      @param string 
      @return bool|object
     
    public function searchByLogin($login)
    {
        $resultObject = false;
        foreach ($this->dbUsers as $value) {
            if (htmlspecialchars_decode(trim($login)) == trim($value->login)) {
                $resultObject = $value;
                break;
            }
        }
        return $resultObject;
    }
      @param string 
      @return bool|int
    
    public function searchObjectNumberByLogin($login)
    {
        $objectNumber = false;
        $users = (array)$this->dbUsers;

        for ($i = 0; $i <= count($users['user']); $i++) {
            if ($login == (string)$users['user'][$i]->login) {
                $objectNumber = $i;
                break;
            }
        }
        return $objectNumber;
    }
      @param string $email
      @return bool
    
    public function validateUniqueEmail($email)
    {
        $user = $this->searchByEmail($email);
        if ($user !== false) {
            $this->errorsValidate[] = 'пользователь с таким email уже есть';
            return false;
        }
        return true;
    }

      @param string 
      @return bool|object
    
    public function searchByEmail($email)
    {
        $resultObject = false;
        foreach ($this->dbUsers as $value) {
            if (trim($email) == trim($value->email)) {
                $resultObject = $value;
                break;
            }
        }
        return $resultObject;
    }

      @param integer 
     @param string 
      @return SimpleXMLElement
    
    public function addSessionKey($number, $sessionKey)
    {
        $this->dbUsers->user[$number]->session_key = $sessionKey;
        $this->dbUsers->asXML(Db::getTablePatch()['Users']);
        return $this->dbUsers;
    }

      @param string 
      @param string 
      @return bool
    
    public function equalitySessionKey($login, $sessioKey)
    {
        $user = $this->searchByLogin($login);
        if ($user->session_key == $sessioKey) {
            return true;
        }
        return false;
    }

    
      @param integer 
      @param string 
      @return SimpleXMLElement
     
    public function addCookieKey($number, $cookieKey)
    {
        $this->dbUsers->user[$number]->cookie_key = $cookieKey;
        $this->dbUsers->asXML(Db::getTablePatch()['Users']);
        return $this->dbUsers;
    }
      @param string 
      @param string 
      @return bool
    public function equalityCookieKey($login, $cookieKey)
    {
        $user = $this->searchByLogin($login);
        if ($user->cookie_key == $cookieKey) {
            return true;
        }
        return false;
    }


}