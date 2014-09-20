/*global exports, _ */
/**
 * This is a class that handles all the currency and number detection logic
 */

var DataType = {};

DataType.integer = function (text) {
    'use strict';
    var pattern = /\d+/i,
        result = null,
        content = text,
        temp;

    if (text !== undefined) {
        if (pattern.test(content)) {
            temp = content.match(pattern);
            if (temp === undefined) {
                throw "Input is not a valid number";
            }

            result = parseInt(temp[0], 10);
        }
    }

    return result;
};

DataType.currency = function (text) {
    'use strict';
    var pattern = /(\d{1,3})(,\d{3})*(\.\d{2,3})?/i,
        content = text,
        result = null,
        temp;
    content.replace(',', '');

    if (text !== undefined) {
        if (pattern.test(content)) {
            temp = content.match(pattern);
            if (temp === undefined) {
                throw "Input is not a valid currency format.";
            }

            temp = temp[0].replace(/,*/g, '');
            result = parseFloat(temp);
        }
    }


    return result;
};
/**
 * Extract the room type from the text.
 *
 * @param {String} text
 * @returns {String}
 */
DataType.roomType = function (text) {
    'use strict';
    var content = text,
        pattern =
            /detached|apartment?|flat?|terraced|semi\-?\s?detached|terraced|bungalow|bedroom\sproperty|house?/i,
        result;

    if (text !== undefined && !_.isArray(text)) {
        if (pattern.test(content)) {
            result = content.match(pattern)[0].toUpperCase();
        }
    } else if (_.isArray(text)) {
        _.each(text, function (e) {
            if (pattern.test(e)) {
                result = e.match(pattern)[0].toUpperCase();
            }
        });
    }
    
    //handle inconsistent labeling
    if (/bed(room)?\sproperty/i.test(result)) {
        result = "house";
    } else if (result === undefined) {
        result = "notSupported";
    } else if (/semi\-?\s?detached/ig.test(result)) {
        result = "Semi Detached";
    }
    return result;
};

DataType.string = function (text) {
    'use strict';
    if (text === undefined) {
        return null;
    }
    return text.trim();
};

DataType.areaCode = function (text) {
    'use strict';
    var content = text,
        pattern = /[A-Z]{1,}\d+[A-Z]*/i,
        result;

    if (text !== undefined) {
        if (pattern.test(content)) {
            result = content.match(pattern)[0];
        }
    }
    return result;
};

DataType.propertyStatus = {
    notOnOffer : 'notOnOffer',
    removed    : 'removed'
};

DataType.offerType = function (text) {
    'use strict';
    var content = text,
        pattern = /rent|sale/i;
    return text.match(pattern)[0];
};

exports.create = function () {
    'use strict';
    return DataType;
};