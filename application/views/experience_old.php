<?php

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
	
		<a href=<?php echo '"'.base_url().'experience/'.$focus.'/'.$replies[2]['id'].'"'; ?> title="Previous">&laquo;</a>

		<?php
	}

	if (count($replies) > 0) {

		?>
		
		<a href=<?php echo '"'.base_url().'experience/'.$replies[0]['id'].'"'; ?> title="Select this line.">
			<?php echo $replies[0]['line']; ?>
		</a>
		
		<?php
	}

	if (count($replies) > 1) {

		?>

		<a href=<?php echo '"'.base_url().'experience/'.$focus.'/'.$replies[1]['id'].'"'; ?> title="Next">&raquo;</a>
		
		<?php
	}

	?>

	</p>

<?php
}
if (isset($focus)) {
if(isset($replies[0])){$reply_to = $replies[0]['id'];}else{$reply_to = $line->id;}
}

echo form_open('experience', array('class' => 'writeon'));

echo form_hidden('reply_to', $reply_to);

echo form_input(array('name' => 'line', 'placeholder' => 'Write on...', 'maxlength' => 120));

if (!isset($focus)) {

		echo form_submit('submit', 'Start a Poem');
	
	}
	else
	{

		echo form_submit('submit', 'Pass it on');

}

	echo form_close();