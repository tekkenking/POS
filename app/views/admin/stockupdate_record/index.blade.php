<div class="page-header position-relative">
	<h1>
		Records
		<small>
			<i class="icon-double-angle-right"></i>
			Search for records here:
		</small>
	</h1>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="span4">

			<div class="inline"> 
				{{ Form::open(array('route'=>'stockupdate.record', 'method' => 'get', 'id'=>'recordsearchform', 'class'=>'form-inline') ) }}
					
					<div class="control-group">
						<div class="inline input-prepend">
							<span class="add-on">
								<i class="icon-calendar"></i>
							</span>
							{{Form::text('record_range', '', array('placeholder'=>'Date range', 'class'=>'span8', 'id'=>'record_range'))}}
							<button type="submit" class="btn btn-warning"><i class="icon-search"></i> Search</button>
						</div>
					</div>
				{{Form::close()}}		
			</div>

		</div>

		<div class="span8">
			<div class="alert-info bolder" style="padding:5px 11px 0; position:relative; top:-4px">
					<div class="row-fluid">
						<div class="span12">

							<div class="span8">
								<i class="icon-time"></i>
								<span class="record_date">
									From: {{format_date2($fromdate)}} - To: {{format_date2($todate)}}
								</span>
							</div>

							<!--<div class="span4">
								<span class="pull-right">
									<button class="print-salesrecordx btn btn-mini btn-purple">
										<i class="icon-print"></i> Print record
									</button>
								</span>
							</div>-->

						</div>
					</div>
			</div>
		</div>

	</div>
</div>


<div class="comments" id="stockupdate" style="border:1px solid lavender; padding: 20px;">
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

{{Larasset::start('footer')->only('ace-element', 'moment', 'daterangepicker')->show('scripts')}}


<script>
$(document).ready(function(){
	/*$('#stockupdate').slimScroll({
		height: '393px',
		alwaysVisible : true,
		railVisible:true
	});*/

	//Adding active to the topmenu
	$('#adminTopmenu > li').eq(2).addClass('active');
	//Adding active to the topmenu submenu
	$('#adminTopmenu > li.active > ul li').eq(5).addClass('active');

	//Calling date rangepicker feature
	$('#record_range').daterangepicker().prev().on(ace.click_event, function(){
		$(this).next().focus();
	});
});
</script>