<div class="widget-box">
    <div class="widget-title">
        <h5>Filter</h5>
    </div>
    <div class="widget-content">
        {{Form::open(array("class" => "form-inline"))}}
        <div class="control-group">
            <div class="controls">
                <label class="control-label">Country</label>
                {{Form::select("country", array("GB" => "UK"), null, array("class" => "span3"))}}&nbsp;
                <label class="control-label">County</label>
                {{Form::select("county", array(), null, array("class" => "span3"))}}&nbsp;
                <label class="control-label">Agency</label>
                {{Form::select("county", array(), null, array("class" => "span2"))}}&nbsp;
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <label class="control-label">Status</label>&nbsp;&nbsp;
                {{Form::select("available", array("-1" => "All", "0" => "No", "1" => "Yes"), null, array("class" => "span2"))}}
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