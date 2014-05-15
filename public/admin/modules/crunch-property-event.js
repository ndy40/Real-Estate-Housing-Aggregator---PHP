/*
 * This is a JQuery file for events for the page. 
 */

$(function (){
    var service = new PropertyService(config),
        loadPostCodes;

    ////////////////////////////////
    /////// Admin County page //////
    ///////////////////////////////

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

    ///////////////////////////////////
    ////// Admin Property Page ///////
    /////////////////////////////////
    var propTable = $("#admin-property-tbl"),
        filter = {},
        loadTableData,
        pagination;
    filter.county = $("#countyFilter").val();
    filter.postcode = $("#postcodeFilter").val();

    loadTableData = function (data, status) {
        var properties = data.data,
            count = data.count,
            size  = data.size,
            current = data.page;

        $("tbody", propTable).empty();

        if (!properties.hasOwnProperty("length")) {
           $("tbody", propTable).append("<tr><td colspan='3'>No data to display</td></tr>");
        } else {
            properties.forEach(function (e, i) {
                var row = "<tr>";

                row += "<td>" +  e.id + '</td>';
                row += "<td>" + e.agency.name + "</td>";
                row += "<td>" + e.marketer + "</td>";
                row += "<td>" + e.price + "</td>";
                row += '<td><a href="' + e.url + '" target="_blank">' + e.address + "</a></td>";
                row += "<td>" + e.post_code.code + "</td>";
                row += "<td>" + e.type.name + "</td>";
                row += "<td>" + (e.offer_type !== null ? e.offer_type : "N/A") + "</td>";
                row += "<td>" + (e.available == 1? "Yes" : "No") + "</td>";
                row += "<td>" + e.created_at + "</td>";
                row += "<td>" + e.updated_at + "</td>";
                row += "<td><a href='#' class='btn btn-mini btn-info'>History</a></td>";

                row += "</tr>";
                $("tbody", propTable).append(row);

            });
        }

        pagination(current, size, count);


    };

    pagination = function (page, size, count) {
        var iterations = Math.ceil(count/size),
            ul = "<ul>";

        for(var i = 1; i <= iterations; i += 1) {
            if (i === parseInt(page)) {
                ul += '<li class="active"><a href="#pager" page="' + i + '">' + i + '</a></li>';
            } else {
                ul += '<li><a href="#pager" page="' + i + '">' + i + '</a></li>';
            }
        }
        ul += "</ul>";

        $("#pagination").empty().append(ul);
    };

    //first load
    service.fetchProperties(filter, loadTableData, function (data, status) {
        alert("Failed to load properties");
    });

    $("#countyFilter").on("change", function (){
        var countyId = $(this).val();
        service.getPostCodes(countyId, function (data, status){
            $("#postcodeFilter").empty().append("<option value='-1'>All</option>");
            data.forEach(function (e) {
                $("#postcodeFilter").append(
                    '<option value="' + e.id + '">' + e.code + " - " + e.area + "</option>"
                )
            });
        });
    });

    $("#filterButton").on("click", function (e){
        var filter = {};
        e.preventDefault();
        filter.county = $("#countyFilter").val();
        filter.post_code_id = $("#postcodeFilter").val();
        service.fetchProperties(filter, loadTableData);

    });

    $("#pagination").on("click", "a", function (e) {
        e.preventDefault();
        filter.county = $("#countyFilter").val();
        filter.postcode = $("#postcodeFilter").val();
        filter.page = parseInt($(this).attr("page"));
        service.fetchProperties(filter, loadTableData);
    });
});
