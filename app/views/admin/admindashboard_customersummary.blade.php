<div class="widget-box">
	<div class="widget-header">
		<h4> <i class="red icon-rss bigger-140"></i> Customer Summary </h4>

		<div class="widget-toolbar no-border">
			<ul id="myTab" class="nav nav-tabs padding-32">
				<li class="active">
					<a href="#customersummary_today" data-toggle="tab" class="ajaxable" data-mode="customersummary_today" data-url={{URL::route('admindashboard')}} >
						<i class="green icon-reorder bigger-120"></i>
						 Today
					</a>
				</li>

				<li class="">
					<a href="#customersummary_yesterday" data-toggle="tab" class="ajaxable" data-mode="customersummary_yesterday" data-url={{URL::route('admindashboard')}} >
					<i class="orange icon-pencil bigger-120"></i>
						Yesterday
					</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main no-padding">
			<div class="no-padding overflow-visible tab-content">
				<div class="tab-pane active" id="customersummary_today">
						@include('admin.customersummary')
				</div>
			</div>
		</div>
	</div>
</div>