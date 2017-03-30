<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/etusivu', function() {
  	HelloWorldController::frontpage();
  });

  $routes->get('/rekisteroidy', function() {
  	HelloWorldController::createAccount();
  });

// Pelin lisäyslomakkeen näyttäminen
  $routes->get('/account/new', function(){
    accountController::create();
  });

      // Pelin lisääminen tietokantaan
  $routes->post('/account/store', function(){
      accountController::store();
  });

  $routes->get('/accounts', function() {
    accountController::showAll();
  });

  $routes->get('/accounts/:id', function($id){
    accountController::show($id);
  });



