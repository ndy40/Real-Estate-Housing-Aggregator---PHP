@extends('layouts.default')

@section('content')

	<main role="main">
		<!--
		<section class="dash-bg c-white">
			<div class="container fw-b fz-2">
				IG9
			</div>
		</section>
		-->

		<div class="page-break tt-u fw-b c-white">New Properties</div>
		
		<section>
			<div class="container">
				<span class="fl-l mt-1">New properties this week</span>
				<h1 class="ta-c c-green m-0 mb-0_5 fl-r">+28</h1>
			</div>
		</section>

		<div class="page-break tt-u fw-b c-white">Average Prices</div>

		<section class="mt-0_5 mb-1">
			<h1 class="ta-c c-green m-0 mb-0_5">£1104.00 pcm</h1>

			<section class="container">
				<div class="btn-group fl-l r-gap" data-toggle="buttons">
					<label class="btn btn-tap active">
						<input type="radio" name="ros" id="rent"> Rent
					</label>
					<label class="btn btn-tap">
						<input type="radio" name="ros" id="sale"> Sale
					</label>
				</div>	

				<div class="dropdown fl-l r-gap">
					<button class="btn btn-tap dropdown-toggle sr-only" type="button" data-toggle="dropdown">
						Detatched
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						<li role="presentation"><a role="menuitem"  href="#">Option1</a></li>
						<li role="presentation"><a role="menuitem"  href="#">Option2</a></li>
					</ul>
				</div>			

				<div class="dropdown fl-l">
					<button class="btn btn-tap dropdown-toggle sr-only" type="button" data-toggle="dropdown">
						1 Bed
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
						<li role="presentation"><a role="menuitem"  href="#">2 Bed</a></li>
						<li role="presentation"><a role="menuitem"  href="#">3 Bed</a></li>
						<li role="presentation"><a role="menuitem"  href="#">4 Bed</a></li>
					</ul>
				</div>

				<div class="cl-l"></div>

			</section>
		</section>

		<div class="page-break tt-u fw-b c-white ">Market Shakers</div>

		<section class="container block">
			<article class="mt-1">
				<div class="fl-l fw-b w-7">
					<div class="c-grey sml-font">Asking Price increasaed</div>
					<span>23 High Road, Buckhurst Hill, IG9 6</span>
				</div>
				<h1 class="ta-c c-green m-0 mb-0_5 fl-r">+4.7%</h1>
			</article>
		</section>

		<section class="container block">
			<article class="mt-1">
				<div class="fl-l fw-b w-7">
					<div class="c-grey sml-font">Asking Price increasaed</div>
					<span>Flat 8, 23 Beatrice Court, Buckhurst Hill, IG9 6</span>
				</div>
				<h1 class="ta-c c-green m-0 mb-0_5 fl-r">+4.7%</h1>
			</article>
		</section>		

		<div class="page-break tt-u fw-b c-white ">Latest Changes</div>

		<section class="container block">
			<article class="mt-1">
				<div class="fw-b">
					<div class="c-grey sml-font fl-l">Asking Price increasaed</div>
					<div class="c-grey sml-font fl-r">Today 2:12PM</div>
				</div>
				<div class="cl fw-b">
					<div class="fl-l" style="width:90%;">Flat 8, 23 Beatrice Court, Buckhurst Hill, IG9 6</div>
					<i class="ion-ios7-arrow-right fz-2 fl-r c-grey mt-0_5"></i>
				</div>
			</article>
		</section>

		<section class="container block">
			<article class="mt-1">
				<div class="fw-b">
					<div class="c-grey sml-font fl-l">Asking Price increasaed</div>
					<div class="c-grey sml-font fl-r">Today 2:12PM</div>
				</div>
				<div class="cl fw-b">
					<div class="fl-l" style="width:90%;">Flat 8, 23 Beatrice Court, Buckhurst Hill, IG9 6</div>
					<i class="ion-ios7-arrow-right fz-2 fl-r c-grey mt-0_5"></i>
				</div>
			</article>
		</section>

	</main>

@endsection