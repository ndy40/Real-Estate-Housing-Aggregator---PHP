/*global Zooplar, require */
/**
 *  This is the main script that initiates a crawl.
 */

var casperjs = require('casper').create(),
    utils   = require('utils'),
<<<<<<< HEAD
    _ = require("../node_modules/underscore"),
=======
    _ = require("node_modules/underscore"),
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
    fs = require('fs'),
    config,
    scriptArgs, //holds a collection of arguments for the scrape.
    scriptOptions, //holds the set of options for the current scrape
    CrunchUtility = require('library/Utility').create(),
    agent, //variable to hold agent to be instantiated at run time.
    agentPath,
    propertyUrls,
    propertyDetails,
    index, 
    scrapeJob = {},
    results = [],
    configFile;

if (casperjs.cli.args.length === 0) {
    throw "No argument provided for executio.";
} else if (casperjs.cli.args.length !== 4) {
    throw "Invalid number of arguments passed. crunch.js country agent";
}

if (casperjs.cli.has('config')) {
    configFile = casperjs.cli.get('config');
} else {
    configFile = 'config.json';
}

config = JSON.parse(fs.open(configFile, 'r').read());
//read scrape parameters
casperjs.cli.drop('cli');
casperjs.cli.drop('casper-path');

scriptArgs = casperjs.cli.args;
scriptOptions = casperjs.cli.options;
//validate argument format
CrunchUtility.validateArguments(scriptArgs);
scrapeJob = CrunchUtility.buildJob(scriptArgs, scriptOptions);


//build agent
agentPath = config.config.directory.agents + '/' + scrapeJob.country + '/' 
    + scrapeJob.agent;
agent = require(agentPath).create();
agent.initialize (casperjs);

<<<<<<< HEAD
if (scrapeJob.agent == 'dataexport') {
	results = agent.itemDetail(casperjs, scrapeJob.url).results;
	casperjs.clear();
} else if (scrapeJob.agent == 'zoopla') {
	//switch context on type of job
	switch (scrapeJob.type) {
	    case 'item':
	        results = agent.itemDetail(casperjs, scrapeJob.url).results;
	        break;
	    default:
	        results = agent.itemListing(casperjs, scrapeJob.url).results;
	        casperjs.clear();
	}
=======
//switch context on type of job
switch (scrapeJob.type) {
    case 'item':
        results = agent.itemDetail(casperjs, scrapeJob.url).results;
        break;
    default:
        results = agent.itemListing(casperjs, scrapeJob.url).results;
        casperjs.clear();
>>>>>>> ce07b156a6f337b9d44a120b15c9cdd8f3f71501
}

//execute scrape here.
casperjs.run(function () {
    var listing = _.compact(results),
        output;
    scrapeJob.results = _.flatten(listing);
    output = CrunchUtility.buildListingOutput(this, scrapeJob);
    //Todo: do a thorough error check before outputing. E.g if there isn't 
    //data returned, then something is likely wrong.
    casperjs.echo(output);
    this.exit(0);
});