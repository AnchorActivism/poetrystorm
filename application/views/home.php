<?php 

if (isset($_SESSION['email'])) {
	?>

	<p>Hello, <?php echo $this->usr->first_name; ?>. How are you?</p>

	<?php
	
	echo form_open('home', array('class' => 'writeon'));

	echo form_hidden('reply_to', '0');

	echo form_input(array('name' => 'line', 'placeholder' => 'Write on...', 'maxlength' => 120));

	echo form_submit('submit', 'Pass it on');

	echo form_close();

	echo validation_errors();

}
else
{
	redirect('home/guest');
}
?>