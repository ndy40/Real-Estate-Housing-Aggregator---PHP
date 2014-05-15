<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
  <ul>
    <li class="active"><a href="{{route("dashboard")}}"><i class="icon icon-home"></i> <span>Dashboard</span></a></li>
    <li class="submenu"> <a href="#"><i class="icon icon-file"></i> <span>Listing</span></a>
      <ul>
        <li><a href="{{route("property")}}">Properties</a></li>
        <li><a href="{{route("postcode")}}">Post Code</a></li>
      </ul>
    </li>
    <li class="submenu"><a href="#reports"><i class="icon icon-tasks"></i><span>Scrape Management</span></a>
        <ul>
            <li><a href="{{route("catalogue")}}">Catalogue</a></li>
            <li><a href="#">Scheduling</a></li>
            <li><a href="#">Queue</a></li>
            <li><a href="#">Stats</a></li>
            <li><a href="{{route("failedScrapes")}}">Failed Scrapes</a></li>
        </ul>
    </li>
    <li><a href="#usermanagement"><i class="icon icon-user"></i><span>Users and Roles</span></a></li>
      <li><a href="#systems" class="submenu"><i class="icon icon-user"></i><span>Systems</span></a>
        <ul>
            <li><a href="{{url("admin/logviewer")}}" target="_blank">Logs</a></li>
        </ul>
      </li>

  </ul>
</div>
