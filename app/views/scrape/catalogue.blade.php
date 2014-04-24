@extends("layout.master");

@section("sidebar")
@include ("include.menu")
@stop

@section("bread_crumbs")
@parent
<a href="#" title="Scrape catalogue" class="tip-bottom current"><i class="icon-th"></i>Catalogue</a>
@stop

@section ("content")
<div class="row-fluid">
    <!-- table agents/agency list -->
    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="icon-book"></i>
                </span>
                <h5>Scrape Catalogue</h5>
            </div>
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Agent</th>
                            <th>Country</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($agents); $i++)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$agents[$i -1]->name}}</td>
                            <td>{{$agents[$i -1]->country->name}}</td>
                            <td colspan="3">
                                <a href="#" class="catalogue-url" data-agent="{{$agents[$i - 1]->id}}" title="View cataglogue urls"><i class="icon-play"></i></a>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end of agent/agency list -->
    <div class="span4 hide" id="catalogue-table">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="icon-bookmark"></i></span>
                <h5 class="pull-left">Catalogue URL</h5>
                <span class="pull-right" style="margin: 3px 3px;">
                    <a href="#add-catalogue" id="addCatalogue" title="Add new url" data-toggle="modal"><i class="icon-plus"></i></a>
                    <a href="#" id="remove-catalogue" title="Delete url"><i class="icon-minus"></i></a>
                </span>
            </div>
            <div class="widget-content nopadding">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- rows of urls -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
<!-- modal for adding new url -->
<div class="hide modal" id="add-catalogue" style="width: 450px;">
        <div class="modal-header">
            <button class="close" type="button" data-dismiss="modal">x</button>
            <h5>Add new url</h5>
        </div>
        <div class="modal-body">
            <form class="form-horizontal">
                <div class="widget-box" style="width: 80%;">
                    <div class="widget-content">
                        <div class="controls-row">
                            {{Form::text("url", null, array("class" => "input-lg", "placeholder" => "URL", "id" => "newUrl"))}}&nbsp;
                            <button class="btn btn-primary" id="insert-url">Add</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- end of modal -->

@stop

@section ("scripts")
    @parent
    <script type="text/javascript" src="{{asset("admin/modules/crunch-catalogue.js")}}"></script>
    <script type="text/javascript">
    $(function () {
        var scrapeService = new ScrapeService(config);
        
        function loadUrl(data, status) {
            if (status === 'success') {
                $("#catalogue-table table tbody").empty();
                if (data.urls.length > 0) {
                    data.urls.forEach(function (e){
                        var content = '<tr><td><input type="checkbox" data-url="' + e.id + '" class="checkboxurls" /></td><td><a href="'+ e.url + '" target="_blank" >' + e.url + '</a></td></tr>';
                        $("#catalogue-table table tbody").append(content);
                    });
                    $("#add-catalogue").attr("data-agent", parseInt(data.agent));
                    $("#remove-catalogue").attr("data-agent", parseInt(data.agent));
                    $("#catalogue-table").show();
                } 
            } else {
                $("#catalogue-table").addClass("hide");
            }
        }
        
        $(".catalogue-url").on("click", function (e) {
            var agentId = parseInt($(this).attr("data-agent"));
            scrapeService.getUrls(agentId, loadUrl);
        });
        //logic for deleting catalogue urls
        $("#remove-catalogue").on("click", function (e) {
            var agentId = parseInt($(this).attr("data-agent"));
            //get all checked checkboxes
            $(".checkboxurls:checked").each(function (i, e){
                var id = $(e).attr("data-url");
                scrapeService.deleteCatalogue(id, function (){
                   scrapeService.getUrls(agentId, loadUrl);
                });
            });
        });
        
        $("#add-catalogue").on("click", function (e){
           $("#url-modal").alert(); 
           $("$newUrl").val("");
        });
        
        $("#insert-url").on("click", function (e){
           var id = parseInt($("#add-catalogue").attr("data-agent")),
               url = $("#newUrl").val();
            scrapeService.addUrl(id, url, function (data, status){
                if (status === "success") {
                   scrapeService.getUrls(data.agency_id, loadUrl);
                   $("#add-catalogue").modal("hide");
                }
                
            });
                
        });
    });
</script>
@stop

