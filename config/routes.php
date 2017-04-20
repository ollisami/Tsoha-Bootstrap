<?php

  function check_logged_in(){
    BaseController::check_logged_in();
  }

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
    accountController::create();
  });

  $routes->post('/rekisteroidy', function() {
    accountController::create();
  });

  $routes->get('/account/new', function(){
    accountController::create();
  });

  $routes->post('/account/store', function(){
      accountController::store();
  });

  $routes->get('/accounts', 'check_logged_in', function() {
    accountController::showAll();
  });

  $routes->get('/account/:id', 'check_logged_in', function(){
    accountController::show();
  });

  $routes->get('/account/:id/edit', 'check_logged_in', function(){
    accountController::edit();
  });

  $routes->post('/account/:id/edit', 'check_logged_in', function($id){
    accountController::update($id);
  });

  $routes->post('/account/:id/destroy', 'check_logged_in', function($id){
    accountController::destroy($id);
  });

  $routes->get('/account/:id/destroy', 'check_logged_in', function($id){
    accountController::destroy($id);
  });

//------------------------add like----------------------------------------
  $routes->post('/account/addlike', 'check_logged_in', function(){
    accountController::addLike();
  });


//-------------------------Conversation----------------------------------------
  $routes->get('/account/:id/keskustelu/:pairId', 'check_logged_in', function($id, $pairId){
    accountController::message($id, $pairId);
  });

  $routes->post('/account/:id/conversation/:conversationId', 'check_logged_in', function($id, $conversationId){
    accountController::sendMessage($id, $conversationId);
  });

  $routes->get('/account/:id/conversation/:conversationId', 'check_logged_in', function($id, $conversationId){
    accountController::showMessageBord($id, $conversationId);
  });


//-----------------LOGIN--------------------------------
$routes->get('/login', function(){
    // Kirjautumislomakkeen esittäminen
    loginController::login();
  });

$routes->post('/login', function(){
  // Kirjautumisen käsittely
  loginController::handle_login();
});

$routes->post('/logout', function(){
    loginController::logout();
});
//---------------------/login--------------------------


