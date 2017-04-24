<?php

class hashtag extends BaseModel {

  public $id, $content;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function findAll($id) {
  	// Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM hashtag WHERE id = :id');
    // Suoritetaan kysely
    $query->execute(array('id'=>$id));
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $hashtag = array();
    foreach($rows as $row){
      $hashtag[] = new hashtag(array(
        'id' => $row['id'],
        'content' => $row['content'],
      ));
    }
    return $hashtag;
  }

    public static function findAllByContent($content) {
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM hashtag WHERE content = :content');
    // Suoritetaan kysely
    $query->execute(array('content'=>$content));
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $hashtag = array();
    foreach($rows as $row){
      $hashtag[] = new hashtag(array(
        'id' => $row['id'],
        'content' => $row['content'],
      ));
    }
    return $hashtag;
  }

  public function insert($content) {
    $query = DB::connection()->prepare('INSERT INTO hashtag (content) VALUES (:content) RETURNING id');
    $query->execute(array('content' => $content));
    $row = $query->fetch();
    return $row['id'];
  }

    public function insertUserhashtag($account_id, $hashtag_id) {
    $query = DB::connection()->prepare('INSERT INTO userHashtag (account_id, hashtag_id) VALUES (:account_id, :hashtag_id)');
    $query->execute(array('account_id' => $account_id, 'hashtag_id' => $hashtag_id));
    $row = $query->fetch();
  }

    public function checkIfUserHashtagExist($account_id, $hashtag_id) {
    $query = DB::connection()->prepare('SELECT * FROM userHashtag WHERE account_id = :account_id AND hashtag_id = :hashtag_id');
    // Suoritetaan kysely
    $query->execute(array('account_id'=>$account_id, 'hashtag_id' => $hashtag_id));
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    if(count($rows) > 0) {
      return false;
    }
    return true;
  }

    public static function findAllWithUser($account_id) {
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT hashtag_id FROM userHashtag WHERE account_id = :account_id');
    // Suoritetaan kysely
    $query->execute(array('account_id'=>$account_id));
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $hashtags = array();
    foreach($rows as $row){
          $tag = hashtag::findAll($row['hashtag_id']);
          foreach($tag as $t){
            if(!in_array($t, $hashtags)) {
              array_push($hashtags, $t);
            }
        }
      }
    return $hashtags;
  }

}