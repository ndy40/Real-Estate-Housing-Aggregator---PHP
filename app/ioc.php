<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
App::singleton('ScrapeFactory', function ($app) {
    return new models\crawler\factories\ScrapeFactory;
});