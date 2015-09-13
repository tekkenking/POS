<div class="navbar navbar-inverse navbar-fixed-bottom footer" style="background: none repeat scroll 0% 0% transparent; border: medium none; margin-bottom: 36px;">
	<div class="container container alert alert-danger center no-margin-bottom">
	    <div class="row-fluid">
		    <div class="span12">
		    	<span class="bolder"><i class="icon-ban-circle bigger-140"></i> {{$gnu_msg}}.</span> 
		    	<small class="bolder" style="color:black"> Only sales feature has been disabled..<button class="btn-primary modalgnu_activate"> Click here </button> to activate this software and resume sales</small>
			</div>
		</div>
	</div>
</div>

@include('error.gnu_script')

<script>
$(document).ready(function(e){
	$("#searchproduct")
	.removeClass("searchproduct")
	.attr("disabled", "disabled");
});
</script>