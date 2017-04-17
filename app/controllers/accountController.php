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
      if(count($account) == 0) {
        Redirect::to('/', array('error' => 'Käyttäjää ei löytynyt!'));
      } else {
        $offeredaccounts = account::getOfferedAccounts($account[0]->minage, $account[0]->maxage, $account[0]->intrestedin);
        $pairs = vote::getPairs($account[0]->id);
        //$offeredaccounts = account::all();
        //Kint::dump($pairs);
        View::make('account/showAccount.html', array('accounts' => $account, 'offeredaccounts' => $offeredaccounts, 'pairs' => $pairs));
    }
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
      Redirect::to('/', array('message' => 'Käyttäjä on lisätty tietokantaan!'));
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
     $attributes = array();
     if($account) {
        $attributes = array(
          'username' => $account[0]->username,
          'name' => $account[0]->name,
          'sex' => $account[0]->sex,
          'age' => $account[0]->age,
          'location' => $account[0]->location,
          'description' => $account[0]->description,
          'intrestedin' => $account[0]->intrestedin,
          'minage' => $account[0]->minage,
          'maxage' => $account[0]->maxage,
        );
      }
    View::make('account/edit.html', array('attributes' => $attributes));
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
      $message = 'Error: ';
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

  public static function message($id, $pairId){
    $match = match::findWithAccount($id, $pairId);
    Redirect::to('/account/' . $id . '/conversation/' . $match[0]->conversation_id);
  }

  public static function showMessageBord($id){
    $conversation = message::findAll($id);
    View::make('account/conversation.html', array('conversation' => $conversation));
  }

  public static function sendMessage($id, $conversationId){
    $params = $_POST;
    $attributes = array(
      'conversation_id' => $conversationId,
      'content' => $params['content']
      //'time' => new Date()
    );

    $message = new message($attributes);
    $message->insert();
    //Kint::dump($message);
    $conversation = message::findAll($conversationId);
    Kint::dump($conversation);
    //Redirect::to('/account/' . $id . '/conversation/' . $conversationId, array('message' => 'Viesti lähetetty onnistuneesti!', 'conversation' => $conversation));
    View::make('account/conversation.html', array('message' => 'Viesti lähetetty onnistuneesti!','conversation' => $conversation));
  }
}