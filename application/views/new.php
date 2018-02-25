<h1 id="logo" title="Poetry Storm">
		<a href="http://www.poetrystorm.com" title="Poetry Storm"><img src="<?php echo asset_url(); ?>img/Logo.jpg" alt="Poetry Storm" title="Poetry Storm" /></a>
</h1>

<div class="gridz">

	<div class="write_line_placeholder">
		&nbsp;
	</div>

	<div class="tagline">
		It's raining poetry.
	</div>
	
	<div class="login">
		<h3>Get an account and get inspired!</h3>
			<a href="/login/service/Facebook" title="Login with Facebook">
				<img src="<?php echo asset_url(); ?>img/LoginFB.png" alt="Login with Facebook" title="Facebook"  class="facebook" />
			</a>
			
			<p>
				<a href="/login/service/Twitter" title="Login with Twitter">
					<img src="<?php echo asset_url(); ?>img/twit.png" alt="Login with Twitter" title="Twitter" />
				</a>

				<a href="/login/service/Google" title="Login with a Google Account">
					<img src="<?php echo asset_url(); ?>img/gplus.png" alt="Login with a Google Account" title="Google" />
				</a>
			</p>

		<a href="experience" title="Continue without an account" class="skip">Continue without an account.</a><br />
		<a href="experience" title="Continue without an account" class="skip">Create an account.</a>
	</div>
	
	<div class="write_line">
		<?php
		echo form_open('experience', array('class' => 'writeon'));

		echo form_hidden('reply_to', 0);

		echo form_textarea(array('name' => 'line', 'placeholder' => 'Write on...', 'cols' => 21, 'rows' => 2, 'maxlength' => '46'));
		
		echo form_submit('submit', 'PASS IT ON');

		echo form_close();
		?>
	</div>	

</div>

<div class="gridz">

	<div class="hero">
		<img src="<?php echo asset_url(); ?>img/Devices.png" alt="Available on all your devices." title="Available on all your devices." />
		<h3>Available on all your devices.</h3>
	</div>

</div><!-- end grid -->

<div class="poetry">
	<?php

	foreach($lines as $line) {
	?>
	<p><a href="<?php echo base_url()."experience/".$line->id; ?>" title="Focus this line."><?php echo $line->line; ?></a></p>
	<?php
	}
	?>

</div>

