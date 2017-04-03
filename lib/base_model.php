<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }
    
  public function validate_string_length($paramName,$string, $length){
    $errors = array();
    if($string == '' || $string == null){
      $errors[] = $paramName. ' ERROR: Merkkijono ei saa olla tyhjä!';
    }
    if(strlen($string) < $length){
      $errors[] = $paramName. ' ERROR: Merkkijono pituus ei riitä!';
    }

    return $errors;
  }

    public function validate_number($paramName, $numb, $min, $max){
    $errors = array();
    if($numb == null || !is_numeric($numb)) {
      $errors[] = $paramName. ' ERROR: ' + 'Luku ei ole numero!';
    }
    if($numb < $min || $numb > $max){
      $errors[] = $paramName. ' ERROR: ' + 'Luku ei kelpaa!';
    }

    return $errors;
  }

}
