@extends("layout.master");

@section("sidebar")
@include ("include.menu")
@stop

@section("bread_crumbs")
@parent
<a href="#" title="Country" class="tip-bottom current"><i class="icon-th"></i>County</a>
@stop

@section ("content")
<div class="row-fluid">
    <div class="span8">
        <form class="form-inline" role="form" id="postcodeform">
            <label class="control-label">County</label>
            {{Form::select("county", $counties, null, array("id" => "county"))}} &nbsp;
            <label class="control-label">Post Code</label>
            {{Form::text("postcode")}}
            <button class="btn btn-primary">Add</button>
            <button class="btn btn-primary delete-county">Delete</button>
        </form>
        <ul id="postcodes-list" class="list-inline">

        </ul>
        
    </div>
    <div class="span4">
        <form class="form-horizontal">
            
        </form>
    </div>
</div>
@stop

@section ("scripts")
    @parent
    <script type="text/javascript" src="{{asset("admin/modules/crunch-property.js")}}"></script>
    <script type="text/javascript" src="{{asset("admin/modules/crunch-property-event.js")}}"></script>
    
@stop