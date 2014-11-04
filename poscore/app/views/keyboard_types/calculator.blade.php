<div id="tan_keyboard">	
	<div id="tan_pos" data-class="btn-app btn-inverse" class="pos_pos">
		<div class="btn-group">
			<button class="btn symbol" id="key_7" kcode=55>
				<span class="st">7</span>
			</button>
			<button class="btn symbol" id="key_8" kcode=56>
				<span class="st">8</span>
			</button>
			<button class="btn symbol" id="key_9" kcode=57>
				<span class="st">9</span>
			</button>
		</div>
		<br>
		<div class="btn-group">
			<button class="btn symbol" id="key_4" kcode=52>
				<span class="st">4</span>
			</button>
			<button class="btn symbol" id="key_5" kcode=53>
				<span class="st">5</span>
			</button>
			<button class="btn symbol" id="key_6" kcode=54>
				<span class="st">6</span>
			</button>
		</div>
		<br>
		<div class="btn-group">
			<button class="btn symbol" id="key_1" kcode=49>
				<span class="st">1</span>
			</button>
			<button class="btn symbol" id="key_2" kcode=50>
				<span class="st">2</span>
			</button>
			<button class="btn symbol" id="key_3" kcode=51>
				<span class="st">3</span>
			</button>
		</div>
		<br>
		<div class="btn-group">
			<button class="btn delete bolder" id="key_delete" kcode=8 style="width:100px">
				c
			</button>
			<button class="btn symbol" id="key_0" kcode=48>
				<span class="st">0</span>
			</button>
			<button class="btn symbol" id="key_." kcode=190>
				<span class="st">.</span>
			</button>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$(this).tkeyboard({
		useKeyboard: true,
		keyboardID: '#tan_pos',
	});
})
</script>