<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu</title>

	<style type="text/css">

	</style>
</head>
<body>

<div id="container">
	<h1>Amrta Ayu</h1>

	<div id="body-input_item">
		<?php echo form_open('cashier/get_item_detail'); ?>
			<select name="search_by">
				<option value="item_id">ID Barang</option>
				<option value="item_name">Nama Barang</option>
			</select>
			<input type="text" name="item_id" /><br>
			<span id="item_name">item name here</span>
		<?php echo form_close(); ?>
	</div>
</div>

</body>
</html>