@extends("layout.master");

@section("sidebar")
@include ("include.menu")
@stop

@section("bread_crumbs")
@parent
<a href="#reports" title="Scrape catalogue" class="tip-bottom"><i class="icon-th"></i>Scrape Management</a>
<a href="#" title="Failed scrapes" class="tip-bottom current"><i class="icon-th"></i>Failed Scrapes</a>
@stop

@section ("content")
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title">
                    <h5>Failed Scrapes</h5>
                </div>
                <div class="widget-content nopadding">
                    <table id="scrapeTabel" class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>Agency</th>
                                <th>File Path</th>
                                <th>Error Messages</th>
                                <th>XML Output</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scrapes as $scrape)
                                <tr>
                                    <td>{{$scrape->agency->name}}</td>
                                    <td>{{$scrape->results}}</td>
                                    <td>{{$scrape->message}}</td>
                                    <td>{{$scrape->data}}</td>
                                    <td><a href="#delete" class="btn btn-mini btn-primary delete-scrape" data-scrape="{{$scrape->id}}">Delete</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section ("scripts")
    @parent
    <script type="text/javascript" src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/modules/failed-scrapes.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            var service = new FailedScrapes(config);

            var table = $('#scrapeTabel').dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "sDom": '<""l>t<"F"fp>'
            });

            $("#scrapeTabel .delete-scrape").on("click", function (e) {
                var id = parseInt($(this).attr("data-scrape")),
                    row = $(this).parents("tr").get(0);

                service.deleteScrape(id, function (data, status) {
                    if (data == true) {
                        table.fnDeleteRow(row);
                    } else {
                        alert("Error while deleting failed scrape.");
                    }
                }, function (data, status) {
                    //failure method
                    alert("Failed to delete scrape.");
                });
            });
        });
    </script>

@stop