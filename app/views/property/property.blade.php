@extends("layout.master");

@section("sidebar")
@include ("include.menu")
@stop

@section("bread_crumbs")
@parent
<a href="#" title="Country" class="tip-bottom current"><i class="icon-th"></i>Country</a>
@stop

@section ("content")
<div class="row-fluid">
    <div class="span12">
        <!-- filter form here -->
        <div class="widget-box">
            <div class="widget-title">
                <h5>Filter</h5>
            </div>
            <div class="widget-content">
                {{Form::open(array("class" => "form-inline"))}}
                    {{Form::label("county", "County")}}
                    {{Form::select("county", $county, null, array("class" => "input-medium", "id" => "countyFilter"))}}
                    {{Form::label("postcode", "Post Codes")}}
                    {{Form::select("postcodes", array(), null, array("class" => "input-medium", "id" => "postcodeFilter"))}}
                    <button class="btn btn-primary" id="filterButton">Filter</button>
                {{Form::close()}}
            </div>
        </div>
        <!-- end of filter form -->
    </div>
</div>

<div class="row-fluid">
     <div class="span12">
         <div class="widget-box">
             <div class="widget-title">
                 <span class="icon"><i class="icon-th-large"></i></span><h5>Properties</h5>
             </div>
             <div class="widget-content">
                 <table class="table table-bordered table-striped table-condensed" id="admin-property-tbl">
                     <thead>
                         <tr>
                             <th>ID</th>
                             <th>Agency</th>
                             <th>Marketer</th>
                             <th>Asking Price</th>
                             <th>Prop. Address (click)</th>
                             <th>Post Code</th>
                             <th>Town/Area</th>
                             <th>Prop. Type</th>
                             <th>Offer Type</th>
                             <th>Available</th>
                             <th>Date Added</th>
                             <th>Last Updated</th>
                             <th>Action</th>
                         </tr>
                     </thead>
                     <tbody></tbody>
                 </table>
                 <div class="pagination pagination-small" id="pagination">

                 </div>
             </div>
         </div>
    </div>
  </div>

@stop

@section ("scripts")
    @parent
    <script type="text/javascript" src="{{asset('admin/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/modules/crunch-property.js')}}"></script>
    <script type="text/javascript" src="{{asset('admin/modules/crunch-property-event.js')}}"></script>
@stop
