<?php

class match extends BaseModel {

  public $id, $account_1_id ,$account_2_id, $conversation_id;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function createPair($id_1, $id_2) {
  	if(!match::findWithAccount($id_1, $id_2)) {
	  	$conversation = match::createConversation();
	    $query = DB::connection()->prepare('INSERT INTO Match (account_1_id,account_2_id,conversation_id) VALUES (:id_1,:id_2,:conversation) RETURNING id');
	    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
	    $query->execute(array('id_1' => $id_1, 'id_2' => $id_2, 'conversation' => $conversation[0]));
	    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
	    $row = $query->fetch();
	    //Kint::trace();
	    Kint::dump($row);
	    // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
	    $params = array();
	    if($row){
	    	$id = $row['id'];
	    	$match = match::find($id);
	    	Kint::dump($match);
		}
	}
  }

  public static function find($id) {
    $query = DB::connection()->prepare('SELECT * FROM Match WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();
    $match = array();
    if($row){
      $match[] = new match(array(
        'id' => $row['id'],
        'account_1_id' => $row['account_1_id'],
        'account_2_id' => $row['account_2_id'],
        'conversation_id' => $row['conversation_id'],
      ));
    }
    return $match;
  }

  public static function findWithAccount($id_1, $id_2) {
    $query = DB::connection()->prepare('SELECT * FROM Match WHERE account_1_id = :id_1 AND account_2_id = :id_2 LIMIT 1');
    $query->execute(array('id_1' => $id_1, 'id_2' => $id_2));
    $row = $query->fetch();
    $match = array();
    if($row){
      $match[] = new match(array(
        'id' => $row['id'],
        'account_1_id' => $row['account_1_id'],
        'account_2_id' => $row['account_2_id'],
        'conversation_id' => $row['conversation_id'],
      ));
      return $match;
    } else {
	    $query = DB::connection()->prepare('SELECT * FROM Match WHERE account_1_id = :id_2 AND account_2_id = :id_1 LIMIT 1');
	    $query->execute(array('id_1' => $id_1, 'id_2' => $id_2));
	    $row = $query->fetch();

	    if($row){
      	  $match[] = new match(array(
	        'id' => $row['id'],
	        'account_1_id' => $row['account_1_id'],
	        'account_2_id' => $row['account_2_id'],
	        'conversation_id' => $row['conversation_id'],
	      ));
    	}
	}
    return $match;
  }

  public static function createConversation() {
  	$query = DB::connection()->prepare('INSERT INTO Conversation DEFAULT VALUES RETURNING id');
  	$query->execute(array());
  	$conversation = $query->fetch();
  	return $conversation;
  }
}