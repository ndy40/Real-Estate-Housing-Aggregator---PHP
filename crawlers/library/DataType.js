/*global exports */
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

    if (content === undefined) {
        throw "Input is undefined";
    }
    if (pattern.test(content)) {
        temp = content.match(pattern);
        if (temp === undefined) {
            throw "Input is not a valid number";
        }

        result = parseInt(temp[0], 10);
    }

    return result;
};

DataType.currency = function (text) {
    'use strict';
    var pattern = /\d+[\.,]?\d*/i,
        content = text.replace(',',''),
        result = null,
        temp;

    if (content === undefined) {
        throw "Input is undefined";
    }

    if (pattern.test(content)) {
        temp = content.match(pattern);
        if (temp === undefined) {
            throw "Input is not a valid currency format.";
        }

        temp = temp[0];
        result = parseFloat(temp);
    }

    return result;
};
/**
 * Extract the room type from the text.
 *
 * @param {String} text
 * @returns {String}
 */
DataType.roomType = function(text) {
    'use strict';
    var content = text,
        pattern = /detached|apartment|flat|terraced/i,
        result;

    if (pattern.test(content)) {
        result = content.match(pattern)[0].toUpperCase();
    }

    return result;
};

DataType.string = function (text) {
    'use strict';
    return text.trim();
};

DataType.areaCode = function (text) {
    'use strict';
    var content = text,
        pattern = /[A-Z]{2,}\d+[A-Z]*/i,
        result;

    if (pattern.test(content)) {
        result = content.match(pattern)[0];
    }

    return result;
};

exports.create = function () {
    'use strict';
    return DataType;
};