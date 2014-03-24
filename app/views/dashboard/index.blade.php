@extends('layouts.default')

@section('content')
	
	<div class="dash-banner">
		
	</div>
	<main role="main">
		<div class="page-break tt-u fw-b c-white">New Properties</div>

		<section>
			<div class="col-md-9">
				test
			</div>
			<div class="col-md-3">
				+28 <br> New properties this week
			</div>
		</section>

		<div class="page-break tt-u fw-b c-white">Average Prices</div>

		<section class="mt-0_5">
			<h1 class="ta-c c-green m-0">£1104.00 pcm</h1>

			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-default">
					<input type="radio" name="ros" id="rent"> Rent
				</label>
				<label class="btn btn-default">
					<input type="radio" name="ros" id="sale"> Sale
				</label>
			</div>	

			<div class="dropdown">
			<button class="btn dropdown-toggle sr-only" type="button" id="dropdownMenu1" data-toggle="dropdown">
			Dropdown
			<span class="caret"></span>
			</button>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Action</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Another action</a></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Something else here</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li>
				</ul>
			</div>			

		</section>

	</main>

@endsection