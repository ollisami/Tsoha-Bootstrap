<?php

class UserController extends BaseController{

  public static function login(){
    View::make('suunnitelmat/etusivu.html');
  }

  public static function handle_login(){
    $params = $_POST;
    $user = User::authenticate($params['username'], $params['password']);
    if(!$user){
      View::make('suunnitelmat/etusivu.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
    }else{
      $_SESSION['user'] = $user->id;

      Redirect::to('/accounts', array('message' => 'Tervetuloa takaisin ' . $user->name . '!'));
    }
  }
}

