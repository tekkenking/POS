<div id="create-task-msg-error" class="error-msg widearea"></div>

<!--<h1 class="green lighter"> Category 
	<small>
		<i class="icon-double-angle-right"></i>
	</small> 
	Product Type 
	<small>
		<i class="icon-double-angle-right"></i>
	</small>
	Product
	<small>
		<i class="icon-ellipsis-vertical"></i>
	</small>
	{{{--$name = ucwords($productcat['name'])--}}}. 
</h1>-->

<div class="">
	{{ Form::open(array('route'=>'saveaddproduct', 'id'=>'myform', 'class'=>'form-horizontal' )) }}
	{{Form::hidden('productcat_id', $productcat['id'])}}
	{{Form::hidden('mode', 1)}}

		 <div class="row-fluid">
		 	<div class="span12">

	 			<h3 class="header blue smaller lighter">Enter Products
	 				<small>
						<i class="icon-double-angle-right"></i>
						Separat by Comma or Enter.
					</small>
	 			</h3>
	 			<div id="ms-right" name="name"></div>
		 			
		 	</div>
		 </div>
		<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">
			{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}
		</span>

		</div>
	{{ Form::close()}}
</div>

<script>
	$(document).ready(function(){
		/* MAGIC SUGGEST */
		$('#ms-right').magicSuggest();

		
		$('[data-ref="submit-form"]').on('click',function(e){
			e.preventDefault();
			$(this).ajaxrequest_wrapper({
				extraContent: {'submit' : $(this).attr('value')},
				url: $('form#myform').attr('action'),
				customFormID: 'myform',
				wideAjaxStatusMsg: '.widearea',
				pageReload:true
			});
		});

	});
</script>