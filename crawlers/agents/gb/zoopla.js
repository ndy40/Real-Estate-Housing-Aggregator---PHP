/*global patchRequire */
/*
 * This is the Agent for Zoopla scrape
 */
var require = patchRequire(require),
    _ = require('underscore'),
    Agent   = require("../../library/BaseAgent").create(),
    CrunchUtil = require("../../library/Utility").create(),
    NextButtonPager = require("../../library/Pagers").create("NextButton"),
    Zooplar;

Zoopla = CrunchUtil.extends(Agent);

Zoopla.prototype.initialize = function (casperjs) {
    'use strict';
    casperjs.options.pageSettings = {
        loadImages : false,
        loadPlugin : false
    };
    casperjs.options.waitTimeout = 15000;
    casperjs.options.logLevel = 'debug';

    this.casperjs = casperjs;

    this.cssPaths = {
        product : ".listing-results *[id*=listing_]",
        name    : "*[itemprop=name]",
        type    : "*[itemprop=name]",
        address : "a[itemprop=streetAddress]",
        url     : "a[data-ga-category*='More details']"
    };

    return this;
};

Zoopla.prototype.itemListing = function (casperjs, url) {
    'use strict';
    var callback,
        self = this,
        completed = false,
        nextBtnPath = ".paginate a[href]:last-child",
        pager = new NextButtonPager(nextBtnPath, false);

    callback = function () {
        casperjs.then(function () { 
            this.waitUntilVisible(".listing-results *[id*=listing_]",
                function () {
                    //flatten the array
                    self.results.push(self.scrapeItems(self));
                });
        });
        return;
    };

    casperjs.start(url, function () {
        this.waitUntilVisible(".listing-results *[id*=listing_]", callback);
        //.then(function () {
        //    pager.run(
        //        casperjs,
        //        callback
        //    );
        //});

        if (completed) {
            casperjs.echo("Scrape completed");
        }
    });
    return this;
};

Zoopla.prototype.scrapeItems = function () {
    'use strict';
    var self = this,
        products = this.casperjs.evaluate(function () {
            var elements = __utils__.findAll('.listing-results *[id*=listing_]');
            return Array.prototype.map.call(elements, function (e) {
               return {
                   url  : e.querySelector('a[itemprop=url]').href,
                   name : e.querySelector('a[itemprop=name]').textContent
               };
            });
        });
    self.results.push(_.flatten(products));
        
};

Zoopla.prototype.itemDetail = function (casperjs, url) {
    'use strict';
    var self = this,
        dataType = require('library/DataType').create();
    
    casperjs.start(url, function () {
        this.waitForSelector(".listing-details-h1[itemprop=name]", function () {
            var property =  this.evaluate(function () {
                var obj = {
                    'name'      : __utils__.findOne(".listing-details-h1[itemprop=name]").textContent,
                    'type'      : __utils__.findOne(".listing-details-h1[itemprop=name]").textContent,
                    'rooms'     : __utils__.findOne(".listing-details-h1[itemprop=name]").textContent,
                    'areaCode'  : __utils__.findOne("*[itemprop=streetAddress]").textContent,
                    'price'     : __utils__.findOne("*.listing-details-price *").textContent,
                    'address'   : __utils__.findOne("*[itemprop=streetAddress]").textContent,
                    'marketer'  : __utils__.findOne('.sidebar.sbt p strong a').textContent,
                    'phone'     : __utils__.findOne('.sidebar .agent_phone a').textContent
                };
                
                
                if (__utils__.exists(".listing-details-h1[itemprop=name]")) {
                    obj.rooms      = __utils__.findOne(".listing-details-h1[itemprop=name]").textContent;
                    obj.offerType  = __utils__.findOne(".listing-details-h1[itemprop=name]").textContent;
                }
                
                if (__utils__.exists('.wrap #splash h1, .price-modifier, #listings-agent p.top')) {
                    obj.status  = __utils__.findOne('.wrap #splash h1, .price-modifier, #listings-agent p.top').textContent;
                }
                
                return obj;
        });
            
            property.price    = property.price !== undefined 
                ? dataType.currency(property.price) : null;
            property.name     = dataType.string(property.name);
            property.type     = dataType.roomType(property.type);
            property.areaCode = dataType.areaCode(property.areaCode);
            property.rooms    = dataType.integer(property.rooms);
            property.address  = dataType.string(property.address);
            property.marketer = dataType.string(property.marketer);
            property.phone    = dataType.string(property.phone);
            property.status     = self.isAvailable(property.status);
            property.offerType  = dataType.offerType(property.offerType);
            
            property.images = this.evaluate(function () {
                var images = __utils__.findAll("a.images-thumb");
                return Array.prototype.map.call(images, function (e) {
                    return e.getAttribute("data-photo");
                });
            });	
            
            self.results.push(property);
        });
    });
    
    return this;
};

Zoopla.prototype.isAvailable = function (text) {
    'use strict';
    var property,
        removedPattern = /can't\sbe\sfound$/i,
        notAvailable   = /offers\sover/i,
        status = 'available';

    if (removedPattern.test(text)) {
        status = 'removed';
    } else if (notAvailable.test(text)) {
        status = 'notAvailable';
    }
    
    return status;
};

exports.create = function () { return new Zoopla("Zoopla"); };
