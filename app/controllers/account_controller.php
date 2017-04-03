<?php

class accountController extends BaseController{

  public static function frontpage(){
    View::make('suunnitelmat/etusivu.html');
  }

  public static function createAccount(){
    View::make('suunnitelmat/rekisteroidy.html');
  }

  public static function showAll(){
    	$accounts = account::all();
    	View::make('account/accounts.html', array('accounts' => $accounts));
  }

  public static function show($id){
    	$account = account::find($id);
      //Kint::dump($accounts);
      View::make('account/showAccount.html', array('accounts' => $account));
  }

 	public static function store(){
    // POST-pyynnön muuttujat sijaitsevat $_POST nimisessä assosiaatiolistassa
      $params = $_POST;
    // Alustetaan uusi account-luokan olion käyttäjän syöttämillä arvoilla


      $attributes = array(
        'username' => $params['username'],
        'password' => $params['password'],
        'name' => $params['name'],
        'sex' => $params['sex'],
        'age' => $params['age'],
        'location' => $params['location'],
        'description' => $params['description'],
        'intrestedin' => $params['intrestedin'],
        'minage' => $params['minage'],
        'maxage' => $params['maxage'],
      );
    $account = new account($attributes);
    $errors = $account->errors();
    Kint::dump($errors);
    //Kint::dump($params);
    if(count($errors) == 0){
      // Peli on validi, hyvä homma!
      $account->save();
      Redirect::to('/account/' . $account->id, array('message' => 'Käyttäjä on lisätty kirjastoosi!'));
    }else{
      // Pelissä oli jotain vikaa :(
      View::make('/account/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }

  }

	public static function create(){
    View::make('account/new.html');
	}

  public static function edit($id){
    $account = account::find($id);
    View::make('account/edit.html', array('attributes' => $account));
  }

  public static function update($id){
    $params = $_POST;

    $attributes = array(
      'username' => $params['username'],
      'password' => $params['password'],
      'name' => $params['name'],
      'sex' => $params['sex'],
      'age' => $params['age'],
      'location' => $params['location'],
      'description' => $params['description'],
      'intrestedin' => $params['intrestedin'],
      'minage' => $params['minage'],
      'maxage' => $params['maxage'],
    );

    // Alustetaan account-olio käyttäjän syöttämillä tiedoilla
    //$account = account::find($id);
    $account = new account($attributes);
    $account->id = $id;
    $errors = $account->errors();

    if(count($errors) > 0){
      View::make('account/edit.html', array('errors' => $errors, 'attributes' => $account));
    }else{
      // Kutsutaan alustetun olion update-metodia, joka päivittää pelin tiedot tietokannassa
      $account->update();

      Redirect::to('/account/' . $account->id, array('message' => 'Käyttäjää on muokattu onnistuneesti!'));
    }
  }

  public static function destroy($id){
    $account = new account(array('id' => $id));
    $account->destroy();
    Redirect::to('/accounts', array('message' => 'Käyttäjä on poistettu onnistuneesti!'));
  }
}