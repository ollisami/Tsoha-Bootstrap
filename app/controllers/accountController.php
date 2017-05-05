<?php

class accountController extends BaseController{

  public static function frontpage(){
    $account = BaseController::get_user_logged_in();
      if(count($account) == 0) {
        View::make('suunnitelmat/etusivu.html');
      } else {
        accountController::show();
      }
  }

  public static function createAccount(){
    View::make('suunnitelmat/rekisteroidy.html');
  }

  public static function showAll(){
    	$accounts = account::all();
    	View::make('account/accounts.html', array('accounts' => $accounts));
  }

  public function hastagsToString($tags) {
    $tagString = "";
    foreach ($tags as $t) {
      $content = $t->content;
      $tagString = $tagString.'#'.$content." ";
    }
    return $tagString;
  }

  public static function show(){
    	$account = BaseController::get_user_logged_in();
      if(count($account) == 0) {
        Redirect::to('/', array('error' => 'Käyttäjää ei löytynyt!'));
      } else {
        $offeredaccounts = account::getOfferedAccounts($account[0]->id,$account[0]->minage, $account[0]->maxage, $account[0]->intrestedin);
        //kint::dump($offeredaccounts);
        $pairs = vote::getPairs($account[0]->id);
        $tags = hashtag::findAllWithUser($account[0]->id);
        foreach ($offeredaccounts as $offeredAccount) {
          $offeredTags = hashtag::findAllWithUser($offeredAccount->id);
          $sameTags = array();
          foreach ($offeredTags as $offeredTag) {
              if (in_array($offeredTag, $tags)) {
                  array_push($sameTags, $offeredTag);
              }
          }
          $tagString = accountController::hastagsToString($sameTags);
          $offeredAccount->sharedTags = $tagString;
        }

        $tagString = accountController::hastagsToString($tags);

        //$offeredaccounts = account::all();
        View::make('account/showAccount.html', array('accounts' => $account, 'offeredaccounts' => $offeredaccounts, 'pairs' => $pairs, 'hashtags' => $tagString));
    }
  }



 	public static function store(){
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
    $account = new account($attributes);
    $errors = $account->errors();
    //kint::dump($errors);
    ////kint::dump($params);
    if(count($errors) == 0){
      $account->save();
      accountController::sethashtags($params['hashtags'], $account);
      Redirect::to('/', array('message' => 'Käyttäjä on lisätty tietokantaan!'));
    }else{
      View::make('/account/new.html', array('errors' => $errors, 'attributes' => $attributes));
    }

  }

  public static function sethashtags($tagstring, $account){       
        $tagstring = preg_replace('/\s+/', '', $tagstring);
        $tags = explode("#", $tagstring);
        kint::dump($tags);
        if(count($tags) > 0) {
          foreach ($tags as $tag) {
            if (!empty($tag)) {
              kint::dump($tag);
              $t = hashtag::findAllByContent($tag);
              if(count($t) == 0) {
                $id = hashtag::insert($tag);
                $t = new hashtag(array('id' => $id, 'content' => $tag));
              } else {
                $t = $t[0];
              }
              // kint::dump(hashtag::checkIfUserHashtagExist($account->id, $t-> $id));
              //if(!hashtag::checkIfUserHashtagExist($account->id, $t-> $id)) {
              hashtag::insertUserhashtag($account->id, $t->id);
              //}
              kint::dump($account->id);
              kint::dump($t);
            }
          }
        }
  }

	public static function create(){
    View::make('account/new.html');
	}

  public static function edit(){
    $account = BaseController::get_user_logged_in();
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
      $tags = hashtag::findAllWithUser($account[0]->id);
      $tagString = "";
        foreach ($tags as $t) {
          $content = $t->content;
          $tagString = $tagString.'#'.$content." ";
        }
      $hashtags = array('tags' => $tagString);
    View::make('account/edit.html', array('attributes' => $attributes, 'hashtags'=>$hashtags));
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
    $account = new account($attributes);
    $account->id = $id;
    $errors = $account->errors();

    if(count($errors) > 0){
      $message = 'Error: ';
      View::make('account/edit.html', array('errors' => $errors, 'attributes' => $account));
    }else{
      $account->update();
      accountController::sethashtags($params['hashtags'], $account);
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
    $conversationId = $match[0]->conversation_id;
    Redirect::to('/account/' . $id . '/conversation/' . $conversationId);
  }

  public static function showMessageBord($id, $conversationId){
    $conversation = message::findAll($conversationId);
    $conversationStatus = count($conversation);
    $account = account::find($id);

    View::make('account/conversation.html', array('conversation' => $conversation, 'conversationStatus' => $conversationStatus, 'gender' => $account[0]->sex));
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
    $conversation = message::findAll($conversationId);
    $conversationStatus = count($conversation);
    $account = account::find($id);
     View::make('account/conversation.html', array('message' => 'Viesti lähetetty onnistuneesti!','conversation' => $conversation, 'conversationStatus' => $conversationStatus, 'gender' => $account[0]->sex));
  }

  public static function addLike(){
    $params = $_POST;
    //kint::dump($params);
    $attributes = array(
      'account_id' => $params['account_id'],
      'liked_account_id' => $params['liked_account_id'],
      'status' => $params['status']
    );

    $vote = new vote($attributes);
    $vote ->save();

    $account = BaseController::get_user_logged_in();
    if(count($account) == 0) {
      Redirect::to('/', array('error' => 'Käyttäjää ei löytynyt!'));
    } else {
      $offeredaccounts = account::getOfferedAccounts($account[0]->id,$account[0]->minage, $account[0]->maxage, $account[0]->intrestedin);
      //kint::dump($offeredaccounts);
      $pairs = vote::getPairs($account[0]->id);
      $tags = hashtag::findAllWithUser($account[0]->id);
      foreach ($offeredaccounts as $offeredAccount) {
        $offeredTags = hashtag::findAllWithUser($offeredAccount->id);
        $sameTags = array();
        foreach ($offeredTags as $offeredTag) {
            if (in_array($offeredTag, $tags)) {
                array_push($sameTags, $offeredTag);
            }
        }
        $tagString = accountController::hastagsToString($sameTags);
        $offeredAccount->sharedTags = $tagString;
      }

      $tagString = accountController::hastagsToString($tags);

      //$offeredaccounts = account::all();
      View::make('account/showAccount.html', array('accounts' => $account, 'offeredaccounts' => $offeredaccounts, 'pairs' => $pairs, 'hashtags' => $tagString, 'showPopUp' => true));
    }
  }
}