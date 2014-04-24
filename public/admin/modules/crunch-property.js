
var PropertyService = function (config) {
    this.config = config;
};

PropertyService.prototype.getPostCodes = function (countyId, callback, failcallback) {
    'use strict';
    var url = this.config.baseUrl,
        data = "/admin/service/post-codes-by-county/" + countyId;
    $.get(url + data, function (data, status){
        if (status === "success") {
            callback(data.data, status);
        } else if (failcallback) {
            failcallback(data, status);
        }
    }, "json");
};




