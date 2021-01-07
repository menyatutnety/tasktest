<?php

class SiteController extends MainController
{
    public function actionIndex()
    {
        if (self::isGuest()) {
            return $this->render('site/index');
        }
        return $this->render('site/hello');
    }
    
     
    public function actionHello()
    {
        return $this->render('site/hello');
    }
    public function actionSignin()
    {
        $users = new Users();

        $activateUser = $users->signInUser($_POST['signInForm']);
        if ($activateUser === true) {
            $numberUser = (int)$users->searchObjectNumberByLogin($_POST['signInForm']['login']);

            if (isset($_POST['check'])) {
                Cookie::create($users, $numberUser);
            }
            Session::create($users, $numberUser);
            self::responseJson([
                'success' => true,
                'message' => 'добро пожаловать',
            ]);
        } else {
            self::responseJson([
                'success' => false,
                'message' => implode('<br>', $activateUser),
            ]);
        }
    }

    
    public function actionSignup()
    {
        $users = new Users();
        $registerUser = $users->signUpUser($_POST['signUpForm']);
        if ($registerUser === true) {
            self::responseJson([
                'success' => true,
                'message' => 'спасибо за регистрацию, теперь вы можете авторизоваться',
            ]);
        } else {
            self::responseJson([
                'success' => false,
                'message' => implode('<br>', $registerUser),
            ]);
        }
    }

 
    public function actionLogout()
    {
        Session::delete();

        Cookie::destroy();

        $this->redirect('');
    }

}