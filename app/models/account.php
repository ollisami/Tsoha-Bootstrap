<?php

class account extends BaseModel{

  public $id ,$username, $password, $name, $sex, $age, $location, $description, $intrestedin, $minage, $maxage, $validators;

  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array('validate_username', 'validate_password', 'validate_name', 'validate_location', 'validate_description');
  }

  public static function find($id){
    $query = DB::connection()->prepare('SELECT * FROM Account WHERE id = :id LIMIT 1');
    $query->execute(array('id' => $id));
    $row = $query->fetch();
    $account = array();
    if($row){
      $account[] = new account(array(
        'id' => $row['id'],
        'username' => $row['username'],
        'password' => $row['password'],
        'name' => $row['name'],
        'sex' => $row['sex'],
        'age' => $row['age'],
        'location' => $row['location'],
        'description' => $row['description'],
        'intrestedin' => $row['intrestedin'],
        'minage' => $row['minage'],
        'maxage' => $row['maxage'],
      ));
    }
    return $account;
  }

  public static function getOfferedAccounts($maxage, $minage, $intrestedin) {
    /*
    $query = DB::connection()->prepare('SELECT * FROM account WHERE age <= :maxage AND age >= :minage');

    if($intrestedin != 3) {
      $query = DB::connection()->prepare('SELECT * FROM account WHERE age <= :maxage AND age >= :minage AND sex = :intrestedin');
      $query->execute(array('maxage'=>$maxage, 'minage'=>$minage, 'intrestedin'=>$intrestedin));
    }else {
      $query->execute(array('maxage'=>$maxage, 'minage'=>$minage));
    }
    */
    $query = DB::connection()->prepare('SELECT * FROM account');
    $query->execute();
    
    $rows = $query->fetchAll();
    $accounts = array();
    foreach($rows as $row){
      $accounts[] = new account(array(
        'id' => $row['id'],
        'username' => $row['username'],
        'password' => $row['password'],
        'name' => $row['name'],
        'sex' => $row['sex'],
        'age' => $row['age'],
        'location' => $row['location'],
        'description' => $row['description'],
        'intrestedin' => $row['intrestedin'],
        'minage' => $row['minage'],
        'maxage' => $row['maxage'],
      ));
    }

    return $accounts;
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
        'intrestedin' => $row['intrestedin'],
        'minage' => $row['minage'],
        'maxage' => $row['maxage'],
      ));
    }

    return $accounts;
  }

  public function save(){
    // Lisätään RETURNING id tietokantakyselymme loppuun, niin saamme lisätyn rivin id-sarakkeen arvon
    $query = DB::connection()->prepare('INSERT INTO Account (username,password,name,sex,age,location,description,intrestedin,minage,maxage) VALUES (:username,:password,:name,:sex,:age,:location,:description,:intrestedin,:minage,:maxage) RETURNING id');
    // Muistathan, että olion attribuuttiin pääse syntaksilla $this->attribuutin_nimi
    $query->execute(array('username' => $this->username, 'password' => $this->password, 'name' => $this->name,'sex' => $this->sex, 'age' => $this->age, 'location' => $this->location, 'description' => $this->description, 'intrestedin' => $this->intrestedin, 'minage' => $this->minage, 'maxage' => $this->maxage));
    // Haetaan kyselyn tuottama rivi, joka sisältää lisätyn rivin id-sarakkeen arvon
    $row = $query->fetch();
    //Kint::trace();
    //Kint::dump($row);
    // Asetetaan lisätyn rivin id-sarakkeen arvo oliomme id-attribuutin arvoksi
    $this->id = $row['id'];
  }

  public function update(){
    $query = DB::connection()->prepare('UPDATE Account SET username = :username, password = :password, name = :name, sex = :sex, age = :age, location = :location, description = :description, intrestedin = :intrestedin, minage = :minage, maxage = :maxage WHERE id = :id');
    $query->execute(array(
    'id' => $this->id,
     'username' => $this->username, 
     'password' => $this->password, 
     'name' => $this->name,
     'sex' => $this->sex, 
     'age' => $this->age, 
     'location' => $this->location, 
     'description' => $this->description, 
     'intrestedin' => $this->intrestedin, 
     'minage' => $this->minage, 
     'maxage' => $this->maxage));
    
  }


  public function destroy(){
    $query = DB::connection()->prepare('DELETE FROM Account WHERE id = :id');
    $t = $this->id;
    $query->execute(array('id' => $t));
  }

  public function authenticate($username, $password) {
    $query = DB::connection()->prepare('SELECT * FROM Account WHERE username = :username AND password = :password LIMIT 1');
    $query->execute(array('username' => $username, 'password' => $password));
    $row = $query->fetch();
    $account = array();
    if($row){
        $account[] = new account(array(
          'id' => $row['id'],
          'username' => $row['username'],
          'password' => $row['password'],
          'name' => $row['name'],
          'sex' => $row['sex'],
          'age' => $row['age'],
          'location' => $row['location'],
          'description' => $row['description'],
          'intrestedin' => $row['intrestedin'],
          'minage' => $row['minage'],
          'maxage' => $row['maxage'],
      ));
    }
    return $account;
  }


  public function errors(){
    $errors = array();

    foreach ($this->validators as $validator) {
      $errors = array_merge($errors, $this->{$validator}());
    }

    //Numbers
    $errors = array_merge($errors, parent::validate_number("Sex",$this->sex, 1, 2));
    $errors = array_merge($errors, parent::validate_number("Age",$this->age, 18, 140));
    $errors = array_merge($errors, parent::validate_number("intrested in",$this->intrestedin, 1, 3));
    $errors = array_merge($errors, parent::validate_number("min age",$this->minage, 18, 140));
    $errors = array_merge($errors, parent::validate_number("max age",$this->maxage, 18, 140));

  return $errors;
}

  public function validate_username () {
    return parent::validate_string_length("Username",$this->username, 4);
  }

    public function validate_password () {
    return parent::validate_string_length("Password",$this->password, 4);
  }

    public function validate_name () {
    return parent::validate_string_length("Name",$this->name, 4);
  }

    public function validate_location () {
    return parent::validate_string_length("Location",$this->location, 4);
  }

    public function validate_description () {
    return parent::validate_string_length("Description",$this->description, 4);
  }
}