/*
 * This is a JQuery file for events for the page. 
 */

$(function (){
    var service = new PropertyService(config),
        loadPostCodes;
        
    //define callbacks
    loadPostCodes = function (data, status) {
        $("#postcodes-list").empty();
        
        if (data.length > 0) {
            data.forEach(function (e) {
                var content = "<li class='span3'>" + e.area + " (" + e.code + ")</li>";
                $("#postcodes-list").append(content);
            });
        } else {
            $("#postcodes-list").append("<li>No post code available</li>");
        }
    };
    
    
    //bind events
    $("#county").change(function (e) {
        var id = parseInt($(this).val());
        service.getPostCodes(id, loadPostCodes);
    });
    
});
