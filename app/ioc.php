<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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