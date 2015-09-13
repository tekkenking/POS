{{ Form::open(array('route'=>'save_regionalsystemsettings', 'id'=>'saveregionalsettings', 'class'=>'', 'style'=>'position:relative;' )) }}

<div class="profile-edit-tab-content">
	<div class="tab-pane in active" id="edit-basic">
		<div class="row-fluid">
			<div class="span12">
				<div class="span6">
					

<fieldset>
	<legend>Currency</legend>
	{{Form::text('currencysymbol', '', array('id'=>'currencysymbol', 'placeholder'=>'Enter currency symbol. e.g. $', 'validate'=>'required') )}}

	{{Form::text('currencyname', '', array('id'=>'currencyname', 'placeholder'=>'Enter currency name. e.g. Dollar', 'validate'=>'required') )}}
</fieldset>

<fieldset>
	<legend>Date format on receipt</legend>

	<div name="commentnote_fontsize" data-options="Normal:inherit, Small:12px" rel="selectoption" data-class="btn-success btn-small" data-default="Normal" setvaluefrom="data-value"></div>
</fieldset>

				</div>
			</div>
		</div>
	</div>
</div>

{{Form::close()}}