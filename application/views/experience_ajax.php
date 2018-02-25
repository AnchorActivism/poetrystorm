<h1 id="logo" title="Poetry Storm">
		<a href="http://www.poetrystorm.com" title="Poetry Storm">
			<img src="<?php echo asset_url(); ?>img/Logo.jpg" alt="Poetry Storm" title="Poetry Storm" />
		</a>
</h1>

<div class="gridz">

	<div class="tagline">
		It's raining poetry.
	</div>

</div>

<div class="gridz">
	<div class="poetry<?php if(isset($first_lines)) {echo ' first_lines';}?>">
<h3>Poetry</h3>

	</div>
</div>
	

<div class="gridz">
	<div class="write_line">
		<?php

		if (isset($focus)) {
			if(isset($replies[0])){$reply_to = $replies[0]['id'];}else{$reply_to = $line->id;}
		}

		echo form_open('experience', array('class' => 'writeon'));

		echo form_hidden('reply_to', $reply_to);

		echo form_textarea(array('name' => 'line', 'id' => 'line', 'cols' => 21, 'rows' => 2, 'maxlength' => '100'));
		
		?><br /><?php

		echo form_submit('submit', $submit_cta);

		echo form_close();
		?>
	</div>
</div>	
