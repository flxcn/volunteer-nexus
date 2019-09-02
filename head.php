<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<style type="text/css">
html, body
{
	height: 100%;
}

body
{
	font: 14px sans-serif;
	text-align: center;
	align-items: center;
	justify-content: center;
	padding-top: 40px;
	padding-bottom: 40px;
	background-color: #f5f5f5;
	margin-bottom: 60px;

	<?php
	if($pageContent=='Form')
	{
		echo
		"display: -ms-flexbox;
		display: -webkit-box;
		-ms-flex-align: center;
		-ms-flex-pack: center;
		-webkit-box-align: center;
		-webkit-box-pack: center;";
	}
	?>
}

.footer {
	position: absolute;
	bottom: 0;
	width: 100%;
	height: 60px; /* Set the fixed height of the footer here */
	line-height: 60px; /* Vertically center the text there */
	background-color: #f5f5f5;
}



</style>
