<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      //View::make('helloworld.html');
      $test = account::find(1);
      $allAccounts = account::all();
    // //kint-luokan dump-metodi tulostaa muuttujan arvon
      //kint::dump($test);
      //kint::dump($allAccounts);
    }

    public static function frontpage(){
      View::make('suunnitelmat/etusivu.html');
    }

    public static function createAccount(){
      View::make('suunnitelmat/rekisteroidy.html');
    }
  }
