<?php

class accountController extends BaseController{

    public static function showAll(){
    	$accounts = account::all();
    // Renderöidään views/game kansiossa sijaitseva tiedosto index.html muuttujan $games datalla
    	View::make('account/index.html', array('accounts' => $accounts));
  	}

  	public static function show($id){
    	//$accounts = account::find(1);
    	$accounts = account::find(1);
    // Renderöidään views/game kansiossa sijaitseva tiedosto index.html muuttujan $games datalla
    	View::make('account/showAccount.html', array('accounts' => $accounts));
  	}

 	public static function store(){
    // POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
      $params = $_POST;
    // Alustetaan uusi Game-luokan olion käyttäjän syöttämillä arvoilla
      $account = new account(array(
        'id' => $params['id'],
        'username' => $params['username'],
        'password' => $params['password'],
        'name' => $params['name'],
        'sex' => $params['sex'],
        'age' => $params['age'],
        'location' => $params['location'],
        'description' => $params['description'],
        'intrestedIn' => $params['intrestedin'],
        'minAge' => $params['minage'],
        'maxAge' => $params['maxage'],
      ));
      
    Kint::dump($params);
    // Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
    $account->save();

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
    Redirect::to('/account/' . $account->id, array('message' => 'Käyttäjä on lisätty kirjastoosi!'));
  }

  	public static function create(){
      View::make('account/new.html');
  	}
}