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
      $row = $_POST;
    // Alustetaan uusi Game-luokan olion käyttäjän syöttämillä arvoilla
      $account = new Account(array(
        'id' => $row['id'],
        'username' => $row['username'],
        'password' => $row['password'],
        'name' => $row['name'],
        'sex' => $row['sex'],
        'age' => $row['age'],
        'location' => $row['location'],
        'description' => $row['description'],
        'intrestedIn' => $row['intrestedin'],
        'minAge' => $row['minage'],
        'maxAge' => $row['maxage'],
      ));

    // Kutsutaan alustamamme olion save metodia, joka tallentaa olion tietokantaan
    $game->save();

    // Ohjataan käyttäjä lisäyksen jälkeen pelin esittelysivulle
    Redirect::to('/áccount/' . $game->id, array('message' => 'Käyttäjä on lisätty kirjastoosi!'));
  }

  	public static function create(){
    	$accounts = account::all();
    // Renderöidään views/game kansiossa sijaitseva tiedosto index.html muuttujan $games datalla
    	View::make('account/index.html', array('accounts' => $accounts));
  	}
}