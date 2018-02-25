<div id="wrapper">

	<h1 id="logo" title="Poetry Storm">
			<a href="http://www.poetrystorm.com" title="Poetry Storm">
				<img src="<?php echo asset_url(); ?>img/Logo.jpg" alt="Poetry Storm" title="Poetry Storm" />
			</a>
	</h1>

	<div class="gridz">

		<div class="tagline">
			For inspiration,<br />It's raining poetry.
		</div>

	</div>

	<div class="gridz">
		<div class="poetry<?php if(isset($first_lines)) {echo ' first_lines';}?>">
			<?php
/*
			foreach($lines as $line) {
				?>
				<p><a href="<?php echo base_url()."experience/".$line->id; ?>" title="Focus this line."><?php echo $line->line; ?></a></p>
				<?php
			}


			if (isset($replies)) {
			?>

				<p>
				<?php
				
				if (count($replies) > 2) {
					?>
				
					<a class="arrow" href=<?php echo '"'.base_url().'experience/'.$origin.'/'.$replies[2]['id'].'"'; ?> title="Previous">&laquo;</a>
					&nbsp;
					
					<?php
				}

				if (count($replies) > 0) {

					?>
					
					<a href=<?php echo '"'.base_url().'experience/'.$replies[0]['id'].'"'; ?> title="Select this line.">
						<?php echo $replies[0]['line']; ?></a>
					
					<?php
				}

				if (count($replies) > 1) {

					?>

					&nbsp;<a class="arrow" href=<?php echo '"'.base_url().'experience/'.$origin.'/'.$replies[1]['id'].'"'; ?> title="Next">&raquo;</a>
					
					<?php
				}

				?>

				</p>

			<?php
			}*/
			?>

		</div>
	</div>
	
	<div class="write_line">
		<?php

		/*if (isset($origin)) {
			if(isset($replies[0])){$reply_to = $replies[0]['id'];}else{$reply_to = $line->id;}
		}*/

		echo form_open('experience', array('class' => 'writeon'));

		echo form_input(array('type' => 'hidden', 'id' => 'reply_to_hidden', 'name' => 'reply_to'));

		echo form_textarea(array('name' => 'line', 'id' => 'line', 'cols' => 21, 'rows' => 2, 'maxlength' => '100'));
		
		?><br /><?php

		echo form_submit('submit', $submit_cta, 'id="submit"');

		echo form_close();
		?>
	</div>

</div>
