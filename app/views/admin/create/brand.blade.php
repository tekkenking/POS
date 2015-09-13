<div class="">
	{{ Form::open(array('route'=>'admincreatenewbrand', 'files' => true, 'id'=>'myform', 'class'=>'form-horizontal' )) }}
		<div id="create-task-msg-error" class="error-msg"></div>

		 <div class="row-fluid">
		 	<div class="span12">
	 			<h3 class="header blue smaller lighter">Enter brand name 
	 			 	<small>
						<i class="icon-double-angle-right"></i>
						Example: MaryKay
					</small>
				</h3>
				{{Form::text('brandname', '', array('id'=>'inputbrandname', 'class'=>'span12'))}}

	 			 <h3 class="header blue smaller lighter">Brand Logo
				 	<small>
						<i class="icon-double-angle-right"></i>
						Only picture( jpg, jpeg, png, gif )
					</small>
				 </h3>
				{{ Form::file('brandlogo', array('id'=>'inputchoosepix')) }}

				<h3>
					<button type="submit" class="btn btn-primary">Submit</button>
					&nbsp; &nbsp; &nbsp;
					<button type="reset" class="btn btn-inverse">Reset</button>
				</h3>
		 	</div>
		 </div>
				
		</div>
	{{ Form::close()}}
</div>

<script>
$(function(){
		/* FILE UPLOAD */
		var $form = $('#myform');
		var file_input = $form.find('input[type=file]');
		var upload_in_progress = false;
		
		file_input.ace_file_input({
			style : 'well',
			btn_choose : 'Select or drop image here',
			btn_change: null,
			droppable: true,
			thumbnail: 'large',

			before_remove: function() {
				if(upload_in_progress)
					return false;//if we are in the middle of uploading a file, don't allow resetting file input
				return true;
			},

			before_change: function(files, dropped) {
				var file = files[0];
				if(typeof file == "string") {//files is just a file name here (in browsers that don't support FileReader API)
					if(! (/\.(jpe?g|png|gif)$/i).test(file) ) {
						bootbox.alert('Please select an image file!');
						return false;
					}
				}
				else {
					var type = $.trim(file.type);
					if( ( type.length > 0 && ! (/^image\/(jpe?g|png|gif)$/i).test(type) )
							|| ( type.length == 0 && ! (/\.(jpe?g|png|gif)$/i).test(file.name) )
							//for android's default browser!
						) {
							bootbox.alert('Please select an image file!');
							return false;
						}

					if( file.size > 2100000 ) {//~2MB
						bootbox.alert('Image size should not exceed 2MB!');
						return false;
					}
				}
	
				return true;
			}
		});
		
		
		$form.on('submit', function() {
			var submit_url = $form.attr('action');
			//if(!file_input.data('ace_input_files')) return false;//no files selected
			
			var deferred ;
			if( "FormData" in window ) {
				//for modern browsers that support FormData and uploading files via ajax
				var fd = new FormData($form.get(0));
			
				//if file has been drag&dropped , append it to FormData
				if(file_input.data('ace_input_method') == 'drop') {
					var files = file_input.data('ace_input_files');
					if(files && files.length > 0) {
						fd.append(file_input.attr('name'), files[0]);
						//to upload multiple files, the 'name' attribute should be something like this: myfile[]
					}
				}

				upload_in_progress = true;
				deferred = $.ajax({
					url: submit_url,
					type: $form.attr('method'),
					processData: false,
					contentType: false,
					dataType: 'json',
					data: fd,
					xhr: function() {
						var req = $.ajaxSettings.xhr();
						if (req && req.upload) {
							req.upload.addEventListener('progress', function(e) {
								if(e.lengthComputable) {	
									var done = e.loaded || e.position, total = e.total || e.totalSize;
									var percent = parseInt((done/total)*100) + '%';
									//percentage of uploaded file
								}
							}, false);
						}
						return req;
					},
					beforeSend : function() {
					},
					success : function() {
						
					}
				})

			}
			else {
				//for older browsers that don't support FormData and uploading files via ajax
				//we use an iframe to upload the form(file) without leaving the page
				upload_in_progress = true;
				deferred = new $.Deferred
				
				var iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
				$form.after('<iframe id="'+iframe_id+'" name="'+iframe_id+'" frameborder="0" width="0" height="0" src="about:blank" style="position:absolute;z-index:-1;"></iframe>');
				$form.append('<input type="hidden" name="temporary-iframe-id" value="'+iframe_id+'" />');
				$form.next().data('deferrer' , deferred);//save the deferred object to the iframe
				$form.attr({'method' : 'POST', 'enctype' : 'multipart/form-data',
							'target':iframe_id, 'action':submit_url});

				$form.get(0).submit();
				
				//if we don't receive the response after 60 seconds, declare it as failed!
				setTimeout(function(){
					var iframe = document.getElementById(iframe_id);
					if(iframe != null) {
						iframe.src = "about:blank";
						$(iframe).remove();
						
						deferred.reject({'status':'fail','message':'Timeout!'});
					}
				} , 60000);
			}
			
			
			////////////////////////////
			deferred.done(function(result){
				upload_in_progress = false;
				var msg='', stat;

				if(result.status == undefined){
					//Means Laravel validation error is passing here.
					var data = (result.errors != undefined) ? result.errors : result;

					//bootbox.alert(data);

					$.each(data, function(key, value){
						msg +='<div>' + value + '</div>';
					});

					stat = 'danger';
				}else{
					msg = result.message;
					stat = result.status;
				}

				$('.error-msg').html(msg).prop('class', 'error-msg display-hidden alert alert-' + stat);

				if(result.url != undefined) {
					setInterval(function(){
						window.location.replace(result.url);
					}, 1000);
					//bootbox.alert("File successfully saved. Thumbnail is: " + result.url)
				}
				return false;
			}).fail(function(res){
				upload_in_progress = false;
				bootbox.alert("There was an error");						
				//console.log(result.responseText);
			});

			deferred.promise();
			return false;
		});
		
		$form.on('reset', function() {
			file_input.ace_file_input('reset_input');
		});

		if(location.protocol == 'file:') bootbox.alert("For uploading to server, you should access this page using a webserver.");

});
</script>