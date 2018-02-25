<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Poetry Storm</title>
  <meta name="description" content="Poetry Storm">
  <meta name="author" content="Patrick Turmala">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php 
	if (isset($css)) {
		foreach ($css as $f) {
			?><link rel="stylesheet" type="text/css" href="<?php echo asset_url().'css/'.$f.'.css'; ?>"><?php
		}
 	}
  ?>

  <!--<link rel="stylesheet" href="css/styles.css?v=1.0">-->

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>

	<div class="nav">
		<ul>
			<li>
				<a href="experience" title="Experience" alt="experience">Experience</a>
			</li>

			<li>
				<a href="about" title="About" alt="About">About</a>
			</li>

			<li>
				<a href="browse" title="Browse" alt="Browse">Browse</a>
			</li>
		</ul>
	</div>
