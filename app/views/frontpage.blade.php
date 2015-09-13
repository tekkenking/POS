<div class="row-fluid">
	<div class="span12">
		<div class="span4 fontpageleft">
			@include('searchproduct')
			{{--@include('payment_details')--}}
		</div>


		<div class="span8 frontpageright">
			@include('cart')
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$(this).find('#frontpageTopmenu > li').eq(0).addClass('active');
});
</script>