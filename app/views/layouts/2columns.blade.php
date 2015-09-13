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
		@include('layouts.admin.topmenu')

		{{-- @include('layouts.admin.subtopmenu') --}}
			<div class="container container-white" id="center-page">
				<div class="row-fluid">
					<div class="span12">
						<div class="span2">
							{{-- THIS IS THE MAIN SIDE MENU CONTENT --}}
							<div  id='main-sidemenu' class="">
								@include('layouts.admin.sidemenu')
							</div>
						</div>

						<div class="span10">
							{{-- THIS IS THE MAIN MIDDLE CONTENT --}}
							<div  id='main-content' class="page-content">
								{{$content}}
							</div>
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
			
		
	{{-- THIS IS THE FOOTER --}}
	@include('layouts.admin.admin_footer')

	{{Larasset::start('footer')->show('scripts')}}

<script type="text/javascript">
	$(document).ready(function(){
		/* MODAL-BOX*/
		$('.myModalCloned').modal({
			keyboard:false,
			show: false,
			backdrop: false
		});

		$(document).on('hidden', '.myModalCloned', function(){
			$(this).remove();
		});


		/* MAGIC SUGGEST DEFAULT OPTIONS*/
		$.fn.magicSuggest.defaults.maxDropHeight = 0;
		$.fn.magicSuggest.defaults.selectionCls = 'btn-yellow no-radius';
		$.fn.magicSuggest.defaults.hideTrigger = true;
		$.fn.magicSuggest.defaults.required = true;
		$.fn.magicSuggest.defaults.width = 500;

		
	});
</script>
	
{{Larasset::start()->get_inlineScript()}}

</html>