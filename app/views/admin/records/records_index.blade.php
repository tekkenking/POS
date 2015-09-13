@section('sidebar')

{{--@include('admin.usersplace_sidemenu')--}}

@stop

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
		<div class="span6">
		{{ Form::open(array('route'=>'adminHomeRecords', 'method' => 'get', 'id'=>'recordsearchform', 'class'=>'form-inline') ) }}
			
			<div class="control-group">
				<div class="inline">
					<select name="record_type" id="record_type">
						<?php $record_types = array('sales'=>'Sales', 'customers'=>'Customers total purchase') ?>
						@foreach($record_types as $v=>$op)
							<option value="{{$v}}" @if( isset($_GET['record_type']) && $_GET['record_type'] === $v ) selected="selected" @endif> {{$op}}
							</option>
						@endforeach
					</select>
				</div>

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

		<div class="span6">
			@if( isset($view_file ) )
			<div class="alert alert-success no-margin bolder">
				<div class="row-fluid">
					<div class="span12">

						<div class="span9">
							<i class="icon-time"></i>
							<span class="record_date">
								From: {{format_date2($fromdate)}} - To: {{format_date2($todate)}}
							</span>
						</div>

						<div class="span3">
							<span class="pull-right">
								<button class="print-salesrecordx btn btn-mini btn-yellow">
									<i class="icon-print"></i> Print record
								</button>
							</span>
						</div>

					</div>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>

@if( isset($view_file ) )
		<div class="row-fluid">
			<div class="span12">
				<div id="print-salesrecordx" class="">
					@include($view_file)
				</div>
			</div>
		</div>

@endif


{{Larasset::start('footer')->only('ace-element', 'moment', 'daterangepicker', 'dataTables-min', 'dataTables-bootstrap')->show('scripts')}}

<script type="text/javascript">
$(function(){
	//Adding active to the topmenu
	$('#adminTopmenu > li').eq(2).addClass('active');
	//Adding active to the topmenu submenu
	$('#adminTopmenu > li.active > ul li').eq(1).addClass('active');

	//Calling date rangepicker feature
	$('#record_range').daterangepicker().prev().on(ace.click_event, function(){
		$(this).next().focus();
	});

	//Removes the sidebar and expand the main content
	//Function inside g56_function.js file
	removeSidebarAndFitPage();
});
</script>