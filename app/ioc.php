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
    return new models\factory\ScrapeFactory;
});

App::bind('ListingQueue', function ($app) {
    return new models\crawler\queue\ListingQueue;
});

App::bind('DetailsQueue', function ($app) {
    return new models\crawler\queue\DetailsQueue;
});

App::bind('DataQueue', function ($app) {
    return new models\crawler\queue\DataQueue;
});

App::bind('ImageProcessingQueue', function ($app) {
    return new models\crawler\queue\ImageProcessingQueue;
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

App::singleton('EntityFactory', function ($app) {
    return new \models\factory\EntityFactory;
});

App::bind('ScrapeRepository', function ($app) {
    return new \models\repositories\ScrapeRepository(
        App::make("PropertyRepository"),
        App::make("AgentRespository")
    );
});

<<<<<<< HEAD
=======
App::bind('FeedRepository', function ($app) {
    return new \models\repositories\FeedRepository(
        App::make("PropertyRepository"),
        App::make("AgentRespository")
    );
});

>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

/////////////////////////////
///// Data Logic Binding ////
/////////////////////////////

App::bind("AuthLogic", function ($app) {
    return new \models\datalogic\AuthenticationLogic(
        new \models\repositories\AuthRepository
    );
});

App::bind("PropertyLogic", function ($app) {
    return new \models\datalogic\PropertyLogic(
        new \models\repositories\PropertyRepository
    );
});

App::bind("AgentLogic", function ($app) {
    return new models\datalogic\AgentLogic(
        new models\repositories\AgentRepository
    );
});

App::bind("ScrapeLogic", function ($app) {
    return new \models\datalogic\ScrapeLogic(
        App::make("ScrapeRepository"),
        App::make("EntityFactory")
    );
<<<<<<< HEAD
});
=======
});
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
