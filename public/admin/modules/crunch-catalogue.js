var ScrapeService = function (config){
    this.config = config;
};

ScrapeService.prototype.getUrls = function (id, callback, callbackfail) {
    'use strict';
    var catalogueUrl = this.config.baseUrl + "/admin/scrape/catalogue/" + parseInt(id);
    $.get(catalogueUrl,function (data, status){
            if (status === "success") {
                callback(data.data, status);
            } else {
                if (callbackfail) {
                    callbackfail(status);
                }
            }
        }, "json");
};

ScrapeService.prototype.addUrl = function (id, catalogue, callback, callbackfail) {
    'use strict';
    var data = {url : catalogue, id : id},
        url = this.config.baseUrl + "/admin/scrape/insert-catalogue";
    $.post(url, {data : data}, function (data, status){
        if (status === "success") {
            callback(data.data, status);
        } else {
            if (callbackfail) {
                callbackfail(data.data, status);
            }
        }
    }, 'json');
};


