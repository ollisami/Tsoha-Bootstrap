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
