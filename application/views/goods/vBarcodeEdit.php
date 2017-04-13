<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Barcode</title>
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

        <?php echo form_open('goods/add_barcode'); ?>
        <table class="table">
            <tr>
                <td>Kategori</td>
                <td><?php echo $info['cat_name']; ?></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td><?php echo $info['item_name']; ?></td>
            </tr>
            <!--
            <tr>
                <td>Harga Beli</td>
                <td><?php echo number_format($info['item_ori_price']); ?></td>
            </tr>-->
            <tr>
                <td>Harga Jual</td>
                <td><?php echo number_format($info['item_price']); ?></td>
            </tr>
        </table><br>
            
        <input type="text" class="form-control" name="barcode_id" autofocus>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <?php echo form_close(); ?><br>
        
        <table class="table">
            <tr>
                <th>Barcode</th>
                <th>Pengaturan</th>
            </tr>
        <?php foreach($codes as $bcode): ?>
            <tr>
                <td><?php echo $bcode['barcode_id']; ?></td>
                <td><a href=<?php echo site_url()."/goods/del_barcode/".$bcode['barcode_id']; ?> class="btn btn-danger">del</a></td>
            </tr>
        <?php endforeach; ?>
        </table>
        <?php echo form_close(); ?>
        
        </div>
    </div>
</div>

</body>
</html>