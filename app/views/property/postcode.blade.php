@extends("layout.master");

@section("sidebar")
@include ("include.menu")
@stop

@section("bread_crumbs")
@parent
<a title="Country" class="tip-bottom current"><i class="icon-th"></i>County</a>
@stop

@section ("content")
<div class="row-fluid">
    <div class="span3">
        <div class="widget-box">
            <div class="widget-title">
                <h5>Filter</h5>
                 
            </div>
            <div class="widget-content">
                <form class="form-inline">
                    <div class="control-group">
                        <div class="controls">
                            {{Form::select("county", $counties, null, array("id" => "county"))}}<br/><br/>
                        </div>
                        <div class="controls">
                            {{Form::text("postcode", null, array("placeholder" => "Post code", "id" => "postcode"))}}<br/><br/>
                        </div>
                        <div class="controls">
                            {{Form::text("area", null, array("placeholder" => "Area name", "id" => "area"))}}<br/><br/>
                        </div>
                        <div class="controls-row">
                            <button class="btn btn-primary" id="add-county">Add</button>
                            <button class="btn btn-primary" class="clearform">Clear</button>
                        </div>
                    </div>
                    
                </form>
                </div>
            </div>
        </div>
    <div class="span9">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="icon-list"></i>
                </span>
                <h5>Post Codes</h5>
                <button class="pull-right btn btn-mini btn-primary delete-county" style="margin: 3px 3px;">Delete</button>
            </div>
            <div class="widget-content">
                <ul id="postcodes-list" class="list-inline">

                </ul>
            </div>
        </div>
        
    </div>
</div>
@stop

@section ("scripts")
    @parent
    <script type="text/javascript" src="{{asset("admin/modules/crunch-property.js")}}"></script>
    <script type="text/javascript" src="{{asset("admin/modules/crunch-property-event.js")}}"></script>
    
@stop