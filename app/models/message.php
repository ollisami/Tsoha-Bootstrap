<?php

class message extends BaseModel {

  public $conversation_id, $content ,$time;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function findAll($conversation_id) {
  	// Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM Message WHERE conversation_id = :conversation_id');
    // Suoritetaan kysely
    $query->execute(array('conversation_id'=>$conversation_id));
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $messages = array();
    foreach($rows as $row){
      $messages[] = new message(array(
        'conversation_id' => $row['conversation_id'],
        'content' => $row['content'],
        'time' => $row['time'],
      ));
    }
    return $messages;
  }

  public function insert() {
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Message (conversation_id,content,time) VALUES (:conversation_id,:content,:time)');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array('conversation_id' => $this->conversation_id, 'content' => $this->content, 'time' => $this->time));
    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    $row = $query->fetch();
  }

}