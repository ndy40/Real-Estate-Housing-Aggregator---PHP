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
                var content = "<li class='delete-county'><input type='checkbox' value='" + e.id + "' class='county' name='toDeleteCounty'/> " + e.area + " (" + e.code + ")</li>";
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
        
        return false;
    });

    $(".delete-county").on("click", function (e) {
        if(confirm("Are you sure you want to delete the items?")) {
            $("#postcodes-list input:checked").each(function (i, e) {
                var id = $(e).val();
                service.deletePostCodes(id, function (data, status){
                    if (data == true) {
                        $("#county").trigger("change");
                    }
                });
            });
        }
        
        return false;
    });
    
    //add post code
    $("#add-county").on("click", function(e) {
       var id = $("#county").val(),
           postCode = $("#postcode").val(),
           area = $("#area").val(),
           data = {county : id, postcode : postCode, area : area};
        service.addPostCode(data, function (data, status){
            alert(data);
        }, function (data, status){
            alert(status);
        });
        
        return false;
    });
    
    $(".clearform").on("click", function (e){
        $("input", this.parentNode).val('');
        return false;
    });

     //run init
    $("#county").trigger("change");


   
    
});