@extends('layouts.default')

@section('content')
	<main role="main">

		<form action="" class="mt-2 brad-4 container">
			<input type="text" class="full-w" placeholder="Enter Postcode">
			<input type="submit" value="Find Property" class="mt-1 btn btn-default w-5 fl-r">
		</form>	

		<form action="" class="mb-2 container">
			<section class="mt-1">
				<!-- <div class="title-txt">Calculation Type</div> -->
				<div class="btn-group cl-r " data-toggle="buttons">
					<label class="btn btn-tap active">
						<input type="radio" name="ros" id="rent"> Buy to Let
					</label>
					<label class="btn btn-tap">
						<input type="radio" name="ros" id="sale"> Buy to Sell
					</label>
				</div>
			</section>

			<section class="mt-1">
				<div class="title-txt">Asking Price</div>
				<input type="text" class="full-w" placeholder="e.g £200,000">
			</section>

			<section class="mt-1">
				<div class="title-txt">Deposit</div>
				<input type="text" class="full-w" placeholder="e.g £200,000">
			</section>		

			<section class="mt-1">
				<div class="title-txt">Interest Rate</div>
				<input type="text" class="full-w" placeholder="3.5%">
			</section>	

			<section class="mt-1">
				<div class="title-txt">Monthly Rent</div>
				<input type="text" class="full-w" placeholder="£1,000">
			</section>		

			<section class="mt-1">
				<div class="title-txt">Minimum Yeild</div>
				<input type="text" class="full-w w-7" placeholder="10%">
				<i class="calc-btn ion-minus"></i>
				<i class="calc-btn ion-plus"></i>
			</section>		

			<input type="submit" value="Add Cost" class="mt-1 btn btn-default fl-r full-w">							

		</form>
	</main>

@endsection