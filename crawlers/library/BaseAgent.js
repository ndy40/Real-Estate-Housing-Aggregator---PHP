/*
 * This is the base class all Agents will inherit
 */
var require = patchRequire(require),
    BaseAgent;


BaseAgent = function () {
    'use strict';
    this.results = [],
    this.details = [],
    this.cssPath = {};
};

BaseAgent.prototype.initialize = function (casperjs) {
    'use strict';
    return this;
};
/*
 * This returns an array of product urls
 *
 */
BaseAgent.prototype.itemListing = function (url) {
    'use strict';
    throw "Not implemented yet";
};

/*
 *
 * @param {URL} url The url of the item
 * @returns {Object}
 */
BaseAgent.prototype.itemDetail = function (casperjs, url) {
    'use strict';
    throw "Not implemented yet";
};

/*
 * Check if a property listing is still available.
 *
 * @return boolean
 */
BaseAgent.prototype.isAvailable = function (url) {
    'use strict';
    throw "Not implemented yet";
};

exports.create = function () { 
    return BaseAgent;
};
