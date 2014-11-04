<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE-edge,chrome=1">
		<title> {{ ucwords($title) }} </title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
   		<meta name="author" content="">

		{{Larasset::start('header')->show('styles')}}
		{{Larasset::start('header')->show('scripts')}}

	</head>
	<body>
	@if(Auth::check())@include('layouts.topmenu')@endif
	
		<div class="container container-white">
			<div class="row-fluid">
				{{-- THIS IS THE MAIN MIDDLE CONTENT --}}
				<div  id='main-content'>
					<div class="page-content">
						{{$content}}
					</div>
				</div>
			</div>
				
		</div>

<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
   		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	<h3 class="lighter grey"></h3>
    </div>
    <div class="modal-body modalbox-body">
    	<p>Loading…</p>
    </div>
    <div class="modal-footer">

		<button class="btn btn-info btn-small" value="saveandclose" data-ref="submit-form">
			<i class="icon-save bigger-210"></i>
				<span>Save and Close</span>
		</button>
    	<button class="btn btn-danger btn-small" data-dismiss="modal" aria-hidden="true">
    		<i class="icon-remove bigger-210"></i>
				<span>Close</span>
    	</button>

    </div>
</div>

@include('layouts.footer')
</html>