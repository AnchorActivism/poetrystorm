<!--  <script src="js/scripts.js"></script>-->

  <?php 

  //JS VARS
	if (isset($js_vars)) {
		?>
			<!-- VARS -->
			<script type="text/javascript">
			<!--
				<?php
				foreach ($js_vars as $k => $v) {
					
					echo 'var '.$k.' = '.$v.';';

				}
		 		
				?>

			-->
			</script>
	<?php

 	}
  ?>

  <?php

  //JS FILES 
	if (isset($js)) {
		?>
			<!-- FILES -->
		<?php
		foreach ($js as $f) {
			?>
			<script src="<?php echo asset_url().'js/'.$f.'.js'; ?>" type="text/javascript"></script>
			<?php
		}
 	}
  ?>

   <?php 

  //JS SCRIPTS
	if (isset($js_scripts)) {
		?>
			<!-- SCRIPTS -->
			<script type="text/javascript">
			<!--
			$(function() {
				<?php
				foreach ($js_scripts as $s) {
					
					echo $s;

				}
		 		
				?>

			});
			-->
			</script>
	<?php

 	}
  ?>

</body>
</html>