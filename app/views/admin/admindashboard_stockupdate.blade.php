<div class="comments" id="stockupdate">
@if( !empty($activities) )
	@foreach( $activities as $key => $activity )
	
		<div class="itemdiv commentdiv">
			<div class="user">
				<?php $genderx =  User::where('id', $activity->user_id)->pluck('gender'); ?>
				<?php $imagex = "uploads/img/". $genderx ."5.png"; ?>
				{{HTML::image($imagex, 'Staff Avatar', array('width'=>'48', 'class'=>'img-circle'))}}
			</div>

			<div class="body">
				@include('activity_layouts.user', $activity)
			</div>
		</div>
	@endforeach

@else
	<h2 class="center lighter red" style=""><i class="icon-money"></i> No recent stock update!</h2>
@endif
</div>

<script>
$(document).ready(function(){
	$('#stockupdate').slimScroll({
		height: '393px',
		alwaysVisible : true,
		railVisible:true
	});
});
</script>