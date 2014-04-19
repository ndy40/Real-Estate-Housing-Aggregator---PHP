<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//////////////////////////////////////////
//// Repository and Factory Binding //////
/////////////////////////////////////////

App::singleton('ScrapeFactory', function ($app) {
    return new models\crawler\factories\ScrapeFactory;
});

App::bind('JobQueue', function ($app) {
    return new models\crawler\JobQueue;
});

App::bind('ScrapeRepository', function ($app) {
    return new models\repositories\ScrapeRepository;
});

App::bind('AgentRespository', function ($app) {
    return new \models\repositories\AgentRepository;
});

App::bind('PropertyRepository', function ($app) {
    return new models\repositories\PropertyRepository;
});

App::bind('AuthRepository', function ($app) {
    return new \models\repositories\AuthRepository;
});


/////////////////////////////
///// Data Logic Binding ////
/////////////////////////////

App::bind("AuthLogic", function ($app) {
    return new \models\datalogic\AuthenticationLogic;
});

App::bind("PropertyLogic", function ($app) {
    return new \models\datalogic\PropertyLogic;
});

App::bind("AgentLogic", function ($app) {
    return new models\datalogic\AgentLogic;
});
