<?php

class vote extends BaseModel {

  public $account_id ,$liked_account_id, $status;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public function save() {
    $query = DB::connection()->prepare('INSERT INTO vote (account_id, liked_account_id, status) VALUES (:account_id, :liked_account_id, :status)');
    $query->execute(array('account_id' => $this->account_id, 'liked_account_id' => $this->liked_account_id, 'status' => $this->status));
    $query->fetch();
  }

  public static function getPairs($id) {
    $query = DB::connection()->prepare('SELECT * FROM vote WHERE account_id = :id and status = 2');
    $query->execute(array('id'=>$id));
   	$rows = $query->fetchAll();
    $givenLikes = array();
    foreach($rows as $row){
      $givenLikes[] = new vote(array(
        'account_id' => $row['account_id'],
        'liked_account_id' => $row['liked_account_id'],
        'status' => $row['status']
      ));
    }
    ////kint::dump($givenLikes);

    $query = DB::connection()->prepare('SELECT * FROM vote WHERE liked_account_id = :id and status = 2');
    $query->execute(array('id'=>$id));
   	$rows = $query->fetchAll();
    $resivedLikes = array();
    foreach($rows as $row){
      $resivedLikes[] = new vote(array(
        'account_id' => $row['account_id'],
        'liked_account_id' => $row['liked_account_id'],
        'status' => $row['status']
      ));
    }
    ////kint::dump($resivedLikes);

    $pairs = array();

    foreach ($givenLikes as $given) {
    	foreach ($resivedLikes as $resived) {
    		if($given->liked_account_id == $resived->account_id) {
    			$account = account::find($given->liked_account_id);
    			if(count($account)> 0) {
    				match::createPair($given->liked_account_id, $resived->liked_account_id);
    				$pairs[] = $account[0];
    			}
    		}
    	}
    }
    ////kint::dump($pairs);
    return $pairs;
  }
}