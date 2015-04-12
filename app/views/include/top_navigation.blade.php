@if (isset($user))
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li class="" ><a title="User profile" href="{{route('profile')}}"><i class="icon icon-user"></i> <span class="text">Profile</span></a></li>
    <li class=""><a title="Logout" href="{{route("logout")}}"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
  </ul>
</div>
@endif