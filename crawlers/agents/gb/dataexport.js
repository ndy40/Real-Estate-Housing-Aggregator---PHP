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

<<<<<<< HEAD
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
=======
Dataexport.prototype.itemListing = function (casperjs, url) {
    'use strict';
    var self = this, dataType = require('library/DataType').create();

    casperjs.start(url, function () {
        var path = url.split(','),
            extractxml_path = path[0],
            upload_path = path[1],
            filename = path[2],
            xmltext = fs.open(extractxml_path, 'r').read(),
            parser = new DOMParser(),
            xmlProperty = parser.parseFromString (xmltext, "text/xml");

        xmltext = fs.open(upload_path+'/XML/'+filename+'_AGENT.XML', 'r').read();
        var xmlAgent = parser.parseFromString(xmltext, "text/xml");
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501

        var properties = xmlProperty.getElementsByTagName('property');
        var property_data = [];

        for (var i=0; i<properties.length; i++) {
            var images = properties[i].getElementsByTagName('image');
            var img_arr = [];
            for (var j=0; j<images.length; j++) {
                img_arr[j] = images[j].childNodes[0].nodeValue;
            }
<<<<<<< HEAD
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
=======
            var phone = xmlAgent.getElementsByTagName('telephone')[0].childNodes[0],
                areaCode = properties[i].getElementsByTagName('areacode')[0].childNodes[0],
                description = properties[i].getElementsByTagName('description')[0].childNodes[0];
                status = properties[i].getElementsByTagName('publish')[0].childNodes[0].nodeValue;
            property_data[i] = {
                type: properties[i].getElementsByTagName('type')[0].childNodes[0].nodeValue,
                rooms: dataType.integer(properties[i].getElementsByTagName('rooms')[0].childNodes[0].nodeValue),
                areaCode: areaCode!==undefined?dataType.areaCode(areaCode.nodeValue):null,
                address: dataType.string(properties[i].getElementsByTagName('address')[0].childNodes[0].nodeValue),
                price: dataType.currency(properties[i].getElementsByTagName('price')[0].childNodes[0].nodeValue),
                marketer: dataType.string(xmlAgent.getElementsByTagName('agentname')[0].childNodes[0].nodeValue),
                phone: phone!==undefined?dataType.string(phone.nodeValue):null,
                offertype: dataType.offerType(properties[i].getElementsByTagName('offertype')[0].childNodes[0].nodeValue),
                status: status==='0'?'notAvailable':'available',
                url: filename,
                description: description!==undefined&&status==='1'?description.nodeValue:null,
                images: status==='0'?null:img_arr
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
            };
        }
        self.results.push(property_data);
    });
    return this;
};

<<<<<<< HEAD
exports.create = function () { return new Dataexport("Dataexport"); };
=======
exports.create = function () { return new Dataexport("Dataexport"); };
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
