<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Tambah Pesanan</title>
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
            
            <form class="form-horizontal" method="post" action="<?php echo site_url().'/orders/select_customer'; ?>">
                <div class="form-group">
                    <label for="customer_phone" class="col-sm-2 control-label">No HP Pemesan</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="customer phone">
                    </div>
                </div>
                <div class="form-group">
                    <label for="customer_name" class="col-sm-2 control-label">Nama Pemesan</label>
                    <div class="col-sm-8">
                        <input type="text" name="customer_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    
    
</div>

</body>
</html>