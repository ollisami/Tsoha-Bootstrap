<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  //View::make('home.html');
      echo 'Tämä on etusivu!';
    }

    public static function sandbox(){
      View::make('helloworld.html');
    }

    public static function frontpage(){
      View::make('suunnitelmat/etusivu.html');
    }

    public static function createAccount(){
      View::make('suunnitelmat/rekisteroidy.html');
    }
  }
