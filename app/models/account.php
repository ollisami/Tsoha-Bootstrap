<?php

class account extends BaseModel{

  public $id ,$username, $password, $name, $sex, $age, $location, $description, $intrestedIn, $minAge, $maxAge;

  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM account WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();

    if($row){
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
      return $account;
    }
    return null;
  }

   public static function all(){
    // Alustetaan kysely tietokantayhteydellämme
    $query = DB::connection()->prepare('SELECT * FROM account');
    // Suoritetaan kysely
    $query->execute();
    // Haetaan kyselyn tuottamat rivit
    $rows = $query->fetchAll();
    $accounts = array();

    // Käydään kyselyn tuottamat rivit läpi
    foreach($rows as $row){
      // Tämä on PHP:n hassu syntaksi alkion lisäämiseksi taulukkoon :)
      $accounts[] = new account(array(
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
    }

    return $accounts;
  }

    public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Account (username,password,name,sex,age,location,description,intrestedin,minAge,maxAge) VALUES (:username,:password,:name,:sex,:age,:location,:description,:intrestedin,:minage,:maxage) RETURNING id');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array('username' => $this->username, 'password' => $this->password, 'name' => $this->name, 'age' => $this->age, 'location' => $this->location, 'description' => $this->description, 'instrestedin' => $this->intrestedIn, 'minage' => $this->minAge, 'maxage' => $this->maxAge));
    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    $row = $query->fetch();
    Kint::trace();
    Kint::dump($row);
    // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
    $this->id = $row['id'];
  }
}