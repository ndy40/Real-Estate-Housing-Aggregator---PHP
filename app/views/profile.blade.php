@extends("layout.master")

@section("sidebar")
    @include("include.menu")
@stop

@section("content")
<div class="row-fluid">
    <div class="span6">
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon">
                    <i class="icon-align-justify"></i>
                </span>
                <h5>Profile</h5>
            </div>
            <div class="widget-content nopadding">
                {{Form::open(array("route" => "profile", "class" => "form-horizontal"))}}
                <div class="control-group">
                    <label class="control-label">Basic Info</label>
                    <div class="controls">
                        {{Form::text('first_name', $user->first_name, array("class" => "span4", "placeholder" => "First name"))}}
                        {{Form::text('last_name', $user->last_name, array("class" => "span4", "placeholder" => "First name"))}}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Email</label>
                    <div class="controls">
                        {{Form::email("email", $user->email, array("disabled" => "disabled"))}}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Role</label>
                    <div class="controls">
                        @foreach($user->getGroups() as $group) 
                            {{Form::text("role", ucfirst($group->name), array("disabled" => "disabled"))}} &nbsp;
                        @endforeach
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Account Status</label>
                    <div class="controls">
                        {{Form::checkbox("activated", $user->isActivated(), $user->isActivated(), array("disabled" => "disabled"))}}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Created at</label>
                    <div class="controls">
                        {{Form::text("created_at", $user->created_at, array("disabled"=> "disabled"))}}
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <a href="#changepassword" class="btn btn-mini btn-info" data-toggle="modal">Change password</a>
                        
                    </div>
                </div>
                {{Form::hidden("id",$user->id)}}
                
                {{Form::close()}}
            </div>
        </div>
    </div>
    <div class="span4" style="margin-top: 15px;">
        <!-- handle the display of status messages -->
        @if (Session::has("status"))
            <?php $data = Session::get("status"); ?>
            <div class="alert alert-block alert-{{ $data["status"]}}">
                <span class="alert-heading">Notice</span>
                <ul>
                    @foreach ($data["messages"] as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
 </div>
<!-- change password modal form. Shows up only when password is to change -->
<div class="hide modal" id="changepassword">
    <div class="modal-header">
        <span class="lead">Change Password</span><button data-dismiss="modal" class="close" type="btn">x</button>
    </div>
    <div class="modal-body">
        <div class="widget-box">
            <div class="widget-content">
                {{Form::open(array("route" => "changepassword", "class" => "form-horizontal"))}}
                <div class="control-group">
                    <label class="control-label">Old Password</label>
                    <div class="controls">
                        {{Form::password("password", array("required", "focus"))}}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Password</label>
                    <div class="controls">
                        {{Form::password("new_password", array("required", "title" => "New password"))}}
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Confirm</label>
                    <div class="controls">
                        {{Form::password("new_password_confirmation", array("required", "title" => "Confirm Password"))}}
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        {{Form::submit("Change", array("class" => "btn btn-primary"))}}
                    </div>
                </div>
                {{Form::hidden("id",$user->id)}}
                {{Form::close()}}
            </div>
        </div>
    </div>
</div>
@stop

