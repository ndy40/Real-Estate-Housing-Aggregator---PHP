/*global exports */
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var CrunchUtility = {};

CrunchUtility.extends = function (baseClass) {
    var fn = function () {
        baseClass.call(this);
    };

    fn.prototype = new baseClass();
    fn.prototype.constructor = fn;

    return fn;
};

CrunchUtility.validateArguments = function (parameters) {
    'use strict';
    if (parameters[0] === undefined) {
        throw "Country parameter not provided.";
    } else if (parameters[1] === undefined) {
        throw "Agent argument not passed.";
    } else if (parameters[2] === undefined) {
        throw "Url to scrape must be passed";
    } else if (parameters[2]) {
        //var urlexp = new RegExp('(http|ftp|https)://[\\w-]+(\\.[\\w-]+)+([\\w-.,@?^=%&:/~+#-]*[\\w@?^=%&;/~+#-])?');
        //if (!urlexp.test(parameters[2])) {
        //    throw "Invalid URL format passed.";
        //}
    } else if (parameters[3] === undefined || !/scrape|item/i.test(parameters[3])) {
        throw "Scrape type must be specified. Possible values list/item";
    }

    return true;
};

/**
 *
 * @param {Array} options - An associative array of options.
 * @returns {Boolean} - True if the validation succeeds.
 */
CrunchUtility.validateOptions = function (options) {
    'use strict';
    var urlexp = new RegExp('scrape|item');

    if (options.hasOwnProperty("mode")
            && !/[local|production]/.test(options['mode'])) 
    {
        throw "Mode parameter can either be local/production";
    }
    
    if (options.hasOwnProperty('item')) {
        if (!urlexp.test(options['item'])) {
            throw "Invalid item url passed";
        }
    }
};

/**
 * This builds the JOb object required to run a scrape
 * @method buildJob
 * @param {Array} args - index based array.
 * @param {Array} options - Associative array of options.
 * @returns {Object}
 */
CrunchUtility.buildJob = function (args) {
    'use strict';
    var job = {},
        i;

    //build arguments
    if (args.length < 3) {
        throw "Invalid number of scrape arguments";
    }

    job.country = args[0].toLowerCase();
    job.agent   = args[1].toLowerCase();
    job.url     = args[2];
    job.type    = args[3];

    return job;
};

CrunchUtility.buildListingOutput = function (casperjs, scrapeJob) {
    'use strict';
    var doc = document.createElement('root'),
        job = document.createElement("job"),
        country = document.createElement('country'),
        agent   = document.createElement('agent'),
        type    = document.createElement('scrapeType'),
        url     = document.createElement('url'),
        results = document.createElement('results');
        
    
     
    country.appendChild(document.createTextNode(scrapeJob.country));
    job.appendChild(country);
    
    agent.appendChild(document.createTextNode(scrapeJob.agent));
    job.appendChild(agent);
    
    type.appendChild(document.createTextNode(scrapeJob.type));
    job.appendChild(type);
    
    url.appendChild(document.createTextNode(scrapeJob.results[0]));
    job.appendChild(url);
    
    //generate results
    scrapeJob.results.forEach(function(element){
        var item = document.createElement('item');
        for(var prop in element) {
            var property = document.createElement(prop);
            if (utils.isArray(element[prop])) {
                Array.prototype.forEach.call(element[prop], function (e) {
                    var imageTag = document.createElement("src");
                    imageTag.appendChild(document.createTextNode(e));
                    property.appendChild(imageTag);
                });
            } else {
                property.appendChild(document.createTextNode(element[prop]));
            }
            
            item.appendChild(property);
        }
        results.appendChild(item);
    });
    
    job.appendChild(results);
    
   	doc.appendChild(job);
       
    return doc.innerHTML;
};



exports.create = function () { 
    'use strict';
    return CrunchUtility; 
};

