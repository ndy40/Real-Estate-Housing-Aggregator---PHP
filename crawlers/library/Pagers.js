/**
*   This file contains a list of Pager types. Base pager Pager clicks through
*   a list of links.
*/

var require = patchRequire(require),
    utils = require('utils'),
	Pager;

/**
 * This is the based pager that uses list of items to page through.
 * Default to a list of links.
 *
 * @param {Array} items A collection of Pager links to click through.
 * @param {Boolean} skipFirstPage Set to true/false if to skip scraping first page
 * @returns {Pager}
 */
var Pager = function (items, skipFirstPage, startIndex, maxIndex) {
    'use strict';
    this.items = items || [],
    this.skipFirstPage = skipFirstPage || false,
    this.startIndex = startIndex || 0,
    this.maxIndex   = maxIndex | this.items.length;

    return this;
} ;
/**
 * This is the run method for executing the pager.
 *
 * @param {CasperJs} casperjs - An instance of CasperJS
 * @param {Function} callback - The method to call when pager is ready to execute.
 * @param {Function} [completion] - Optional function to call on complete.
 * @returns {undefined}
 */
Pager.prototype.run = function (casperjs, callback, completion) {
    'use strict';
    var _self = this;

    if (!utils.isFunction(callback)) {
        throw "Callback must be a function";
    }

    if (this.startIndex > 0) {
        this.items.slice(this.startIndex, this.maxIndex);
    }
    //check if to skip running scrape on first page.
    if (this.skipFirstPage) {
        callback.apply(this, [casperjs, this.items[0], 0, this.items]);
    } else {
        //page through items
        casperjs.each(
            this.items,
            function (casperjs, value, index) {
                callback.apply(this, [casperjs, value, index]);
            }
        );
    }

    if (utils.isFunction(completion)) {
        completion.apply(this);
    }
};

/**
 * This is a next button pager. Pass in the path to the link.
 * @param {type} linkPath
 * @param {type} skipFirstPage
 * @returns {undefined}
 */
Pager.NextBtnPager = function (linkPath, skipFirstPage) {
    'use strict';
    this.linkBtn = linkPath,
    this.skipFirstPage = skipFirstPage || false;

    return this;
};
//Inherit pager.
utils.inherits(Pager.NextBtnPager, Pager);

/**
 * The run method to execute pager by clicking on the specified link.
 * @param {Casper} capserjs
 * @param {Function} callback - The callback method to call when a link has been clicked.
 * @param {Function} [completion] - Finall method to be called at the end of a scrape.
 * @returns {undefined}
 */
Pager.NextBtnPager.prototype.run = function (casperjs, callback, onCompletion) {
    'use strict';
    var self = this,
        completion = onCompletion || null,
        btnPath = this.linkBtn;

    if (!utils.isFunction(callback)) {
        throw "Callback must be a function."
    }

    casperjs.waitForSelector(btnPath, function () {
        this.wait(1500);
        casperjs.thenClick(btnPath, callback)
        .then(function () {
           this.wait(1500);
           self.run(casperjs, callback, onCompletion);
        });
    }, function () {
        if (utils.isFunction(onCompletion)) {
            onCompletion();
        }
    });

  return this;
};

exports.create = function (pagerType) {
    var pager;

    switch (pagerType) {
        case 'NextButton':
            pager = Pager.NextBtnPager;
            break;
        default:
            pager = Pager;
    }

    return pager;
};