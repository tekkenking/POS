<div class="widget-box">
	<div class="widget-header">
		<h4> <i class="red icon-rss bigger-140"></i> Lost & Best </h4>

		<div class="widget-toolbar no-border">
			<ul id="myTab" class="nav nav-tabs padding-32">
				<li class="active">
					<a href="#lostcustomerssummary" data-toggle="tab" class="ajaxable" data-mode="lostcustomerssummary" data-url={{URL::route('admindashboard')}} >
						<i class="green icon-reorder bigger-120"></i>
						 Lost customers
					</a>
				</li>

				<li class="">
					<a href="#bestcustomerssummary" data-toggle="tab" class="ajaxable" data-mode="bestcustomerssummary" data-url={{URL::route('admindashboard')}} >
					<i class="orange icon-pencil bigger-120"></i>
						Best customers
					</a>
				</li>
			</ul>

		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main no-padding">
			<div class="no-padding overflow-visible tab-content">
				<div class="tab-pane active" id="lostcustomerssummary">
						@include('admin.lostcustomerssummary')
				</div>
			</div>
		</div>
	</div>
</div>