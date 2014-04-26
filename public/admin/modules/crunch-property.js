
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

/**
* Method for deleting post codes. 
* @param int postCodeId the ID of the post code to delete.
* @param Function onSuccess the function to call on success.
* @param Function onFailure The function to call on failure. 
*/
PropertyService.prototype.postDeletePostCodes = function (postCodeId, onSuccess, onFailure) {
    'use strict';
    var url = this.config.baseUrl + "/admin/service/delete-post-code",
        data = {data : {"id" : postCodeId}};

    $.post(url, data, function (data, status){
        if (status === "success") {


        } else if (onFailure) {
            onFailure(data, status);
        }

    }, "json");
};




