/*global patchRequire */
/*
 * This is the Agent for Dataexport scrape
 */
var require = patchRequire(require),
    _ = require('underscore'),
    fs = require('fs'),
    Agent   = require("../../library/BaseAgent").create(),
    CrunchUtil = require("../../library/Utility").create();
    
Dataexport = CrunchUtil.extends(Agent);

Dataexport.prototype.initialize = function (casperjs) {
    'use strict';
    casperjs.options.pageSettings = {
        loadImages : false,
        loadPlugin : false
    };
    casperjs.options.waitTimeout = 15000;
    casperjs.options.logLevel = 'debug';

    this.casperjs = casperjs;

    return this;
};

Dataexport.prototype.itemDetail = function (casperjs, url) {
    'use strict';
    var self = this, dataType = require('library/DataType').create();

    casperjs.start(url, function () {;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", url, false);
        xmlhttp.send();
        var xmlProperty = xmlhttp.responseXML;

        xmlhttp = new XMLHttpRequest();
        var arr = url.split('/');
        var filename = arr.pop();
        filename = filename.split('.');
        arr.pop();
        xmlhttp.open('GET', arr.join('/')+'/XML/'+filename[0]+'_AGENT.XML', false);
        xmlhttp.send();
        var xmlAgent = xmlhttp.responseXML;

        var properties = xmlProperty.getElementsByTagName('property');
        var property_data = [];

        for (var i=0; i<properties.length; i++) {
            var images = properties[i].getElementsByTagName('image');
            var img_arr = [];
            for (var j=0; j<images.length; j++) {
                img_arr[j] = images[j].childNodes[0].nodeValue;
            }
            property_data[i] = {
                type: properties[i].getElementsByTagName('type')[0].childNodes[0].nodeValue,
                rooms: properties[i].getElementsByTagName('rooms')[0].childNodes[0].nodeValue,
                areaCode: properties[i].getElementsByTagName('areacode')[0].childNodes[0].nodeValue,
                address: properties[i].getElementsByTagName('address')[0].childNodes[0].nodeValue,
                price: properties[i].getElementsByTagName('price')[0].childNodes[0].nodeValue,
                marketer: xmlAgent.getElementsByTagName('contact')[0].childNodes[0].nodeValue,
                phone: xmlAgent.getElementsByTagName('telephone')[0].childNodes[0].nodeValue,
                offertype: properties[i].getElementsByTagName('offertype')[0].childNodes[0].nodeValue,
                status: 'available',
                url: '',
                images: img_arr
            };
        }
        self.results.push(property_data);
    });
    return this;
};

exports.create = function () { return new Dataexport("Dataexport"); };