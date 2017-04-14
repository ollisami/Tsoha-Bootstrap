<?php

class loginController extends BaseController{

  public static function login(){
    View::make('suunnitelmat/etusivu.html');
  }

  public static function handle_login(){
    $params = $_POST;
    //if (!$params || !$params['username'] || !$params['password']) {
      //View::make('suunnitelmat/etusivu.html' ,  array('error' => 'Käyttäjätunnus tai salasana oli tyhjä!'));
    //}

    $user = account::authenticate($params['username'], $params['password']);
    if(!$user){
      View::make('suunnitelmat/etusivu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
    }else{
      $_SESSION['user'] = $user[0]->id;

      Redirect::to('/account/' . $user[0]->id, array('message' => 'Tervetuloa takaisin ' . $user[0]->username . '!'));
    }
  }

  public static function logout(){
    $_SESSION['user'] = null;
    Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
  }
}

