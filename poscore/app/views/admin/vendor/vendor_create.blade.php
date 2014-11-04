{{--Larasset::start('header')->only('bootstrap-datepicker')->show('styles')--}}
<div class="banknewentry" id="newbankentry">
	<div class="row-fluid">
		<div class="span12">
			<div class="offset3 span6 offset3 widget-box">
				{{Form::open(array('route'=>'vendor.save', 'id'=>'createvendor', 'class'=>'form-inline'))}}
			
					<div class="widget-header widget-header-blue widget-header-flat">
						<h3 class="lighter">Create new vendor</h3>
					</div>

			<div class="widget-body">
				<div class="widget-main">
					<div class="row-fluid">
						<div class="span12">

								<div class="control-group">
									<label for="vendor_name" class="control-label"><span class="red">*</span> Vendor's name <small class="muted">e.g <em>Glamour56</em></small></label>

									<div class="controls">
										{{Form::text('name','', array('class'=>'span12', 'id'=>'vendor_name', 'validate'=>'required'))}}
									</div>
								</div>
								<div class="control-group">
									<label for="email" class="control-label"><span class="red">*</span> Vendor's email <small class="muted">e.g <em>glamour56@yahoo.com</em></small></label>

									<div class="controls">
										{{Form::text('email', '', array('class'=>'span12', 'id'=>'email', 'validate'=>'required|email'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="phone" class="control-label"><span class="red">*</span> Vendor's phone number <small class="muted">e.g <em>08077379160</em></small></label>

									<div class="controls">
										{{Form::text('phone','', array('class'=>'span12', 'id'=>'phone', 'validate'=>'required|phone'))}}
									</div>

								</div>

								<div class="control-group">
									<label for="address" class="control-label"><span class="red">*</span>Vendor's address <small class="muted"> Suite FF8. Berger paint plaza. Wuse 2 </small></label>

									<div class="controls">
										{{Form::textarea('address','', array('class'=>'span12', 'id'=>'address', 'rows'=>'2', 'validate'=>'required'))}}
									</div>
								</div>

								<div class="control-group">
									<label for="comment" class="control-label">Comment <small class="muted">e.g <em>A note attached to this vendor</em></small></label>

									<div class="controls">
										{{Form::textarea('comment','', array('class'=>'span12', 'id'=>'comment', 'rows'=>'2'))}}
									</div>
								</div>

						</div>
					</div>
				</div>
			</div>

				<div id="create-task-msg-error" class="error-msg"></div>

				<div class="form-actions">
					<button class="btn btn-info" type="submit">
						<i class="icon-ok bigger-110"></i>
						Submit
					</button>

					&nbsp; &nbsp; &nbsp;
					<button class="btn" type="reset">
						<i class="icon-undo bigger-110"></i>
						Reset
					</button>

					&nbsp; &nbsp; &nbsp;
	<span id="create-task-ajaxloader" class="ajaxloader" style="display:none;">{{HTML::image('vendor/bucketcodes/img/ajax-loader.gif')}}</span>
				</div>
				{{Form::close()}}
			</div>
		</div>
	</div>
</div>
{{--Larasset::start('footer')->only('bootstrap-datepicker', 'ace-element')->show('scripts')--}}

<script type="text/javascript">
$(function(){

	$('button[type="submit"]').on('click',function(e){
		e.preventDefault();
		$(this).ajaxrequest_wrapper({
			validate: {vtype:'inline', etype:'inline'},
			clearfields: true
		});
	});
});
</script>