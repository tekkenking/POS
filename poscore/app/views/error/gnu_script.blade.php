<?php 
$geturl = URL::route("getactivatebox");

Larasset::start()->set_inlineScript('
<script>
	$(document).ready(function(){
		var callActivateBox = function (e){
			e.preventDefault();

			var $this = $(this), url = "'. $geturl .'" ;

			$this.off("click.callActivateBox", callActivateBox);

			$.get(url, function(data){
				cloneModalbox( $("#myModal") )
				.css({"width":"600px"})
				.centerModal()
				.find(".modal-body").html( data )
				.end()
				.find(".modal-header h3")
				.text("Activate this software")
				.end()
				.find(".modal-footer [data-ref=\"submit-form\"]")
				.attr("data-ref", "activate-software")
				.text("Activate software")
				.end()
				.modal();

				$this.on("click.callActivateBox", callActivateBox);
			});
		}

		$(".modalgnu_activate").on("click.callActivateBox", callActivateBox);

	});
</script>
');