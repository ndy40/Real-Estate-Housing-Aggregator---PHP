/**
 * Created by ndy40 on 05/05/2014.
 */
var FailedScrapes = function (config) {
    'use strict';
    this.config = config;
};

/**
 * Method for fetching all failed scrapes
 *
 * @param Function onSuccess
 * @param Function onFailure
 */
FailedScrapes.prototype.fetchscrapes = function (onSuccess, onFailure) {
    'use strict';
    var baseUrl = this.config.baseUrl + "/admin/scrape/failed-scrapes";
    $.get(baseUrl, function (data, status){
        if (status === 'success') {
            onSuccess(data.data, status);
        } else if (onFailure) {
            onFailure(data, status);
        }
    }, "json");
};

FailedScrapes.prototype.deleteScrape = function (id, onSuccess, onFailure) {
    'use strict';
    var baseUrl = this.config.baseUrl + "/admin/scrape/delete-failed-scrape",
        data = {id : id};
    $.post(baseUrl, data, function (data, status) {
        if (status === "success") {
            onSuccess(data, status);
        } else if (onFailure) {
            onFailure(data, status);
        }
    }, "json");
};
