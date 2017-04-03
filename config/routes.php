<?php

  $routes->get('/', function() {
    accountController::frontpage();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/etusivu', function() {
  	accountController::frontpage();
  });

  $routes->get('/rekisteroidy', function() {
  	accountController::createAccount();
  });

  $routes->get('/account/new', function(){
    accountController::create();
  });

  $routes->post('/account/store', function(){
      accountController::store();
  });

  $routes->get('/accounts', function() {
    accountController::showAll();
  });

  $routes->get('/account/:id', function($id){
    accountController::show($id);
  });

  $routes->get('/account/:id/edit', function($id){
    accountController::edit($id);
  });

  $routes->post('/account/:id/edit', function($id){
    accountController::update($id);
  });

  $routes->post('/account/:id/destroy', function($id){
    accountController::destroy($id);
  });

  $routes->get('/account/:id/destroy', function($id){
    accountController::destroy($id);
  });




$routes->get('/login', function(){
    // Kirjautumislomakkeen esittäminen
    UserController::login();
  });

$routes->post('/login', function(){
  // Kirjautumisen käsittely
  UserController::handle_login();
});



