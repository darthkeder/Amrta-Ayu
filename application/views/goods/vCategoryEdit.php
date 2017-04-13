<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Transaksi</title>
	<link href="<?php echo base_url().'css/bootstrap.min.css'; ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url().'js/jquery-3.1.1.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/bootstrap.min.js'; ?>"></script>
</head>
<body>

<div id="main-menu"><?php echo $mainmenu; ?></div>
    
<div class="container">
	<div class="row">
        <div class="col-md-10">
            <?php if($this->session->flashdata('error_message') != ""): ?>
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?php echo $this->session->flashdata('error_message'); ?>
            </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('success_message') != ""): ?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?php echo $this->session->flashdata('success_message'); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-10">
        <?php echo form_open('goods/add_category/update'); ?>
            <input type="hidden" name="cat_id" value="<?php echo $cats['CAT_ID']; ?>">
            <div class="form-group">
                <label for="cat_name">Kategori</label>
                <input type="text" name="cat_name" class="form-control" value="<?php echo $cats['CAT_NAME']; ?>" autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        <?php echo form_close(); ?>
        </div>
    </div>
    
</div>

</body>
</html>