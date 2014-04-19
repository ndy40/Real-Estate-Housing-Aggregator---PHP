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
     <div class="span9">
         <div class="widget-box">
             <div class="widget-title">
                 <span class="icon"><i class="icon-th-large"></i></span><h5>Properties</h5>
             </div>
             <div class="widget-content">
                 <table class="table table-bordered table-striped">
                     <thead>
                         <tr>
                             <th>Name</th>
                             <th>Address</th>
                             <th>Post Code</th>
                             <th colspan="2">
                                 Action
                             </th>
                         </tr>
                     </thead>
                 </table>
             </div>
         </div>
    </div>
    <div class="span3">
        <!-- filter form here -->
        <div class="widget-box">
            <div class="widget-title">
                <h5>Filter</h5>
            </div>
            <div class="widget-content">
                {{Form::open(array("role" => "form"))}}
                <div class="control-group">
                    <label class="control-label">Country</label>
                        <div class="controls">
                            {{Form::select("country", array("GB" => "UK"), null)}}
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">County</label>
                        <div class="controls">
                            {{Form::select("county", array(), null)}}
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Agency</label>
                        <div class="controls">
                            {{Form::select("county", array(), null)}}
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Status</label>
                        <div class="controls">
                            {{Form::select("available", array("-1" => "All", "0" => "No", "1" => "Yes"), null)}}
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <button class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                {{Form::close()}}
            </div>
        </div>
        <!-- end of filter form -->
    </div>
    
</div>
@stop
