<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Kategori Barang</title>
	<link href="<?php echo base_url().'css/bootstrap.min.css'; ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url().'js/jquery-3.1.1.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/bootstrap.min.js'; ?>"></script>
</head>
<body>

<div id="main-menu"><?php echo $mainmenu; ?></div>

<div class="container">
    hahaha
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
            <a href=<?php echo site_url()."/goods/category/add_item";?> class="btn btn-success">Tambah Kategori</a>
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Kategori Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Pengaturan</th>
                </tr>
                <?php $i = 0; ?>
                <?php foreach($cats as $cat): ?>
                    <?php $i = $i + 1; ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><a href=<?php echo site_url()."/goods/category/".$cat['CAT_ID']; ?>><?php echo $cat['CAT_NAME']; ?></a></td>
                        <td><?php echo $cat['CNT_ITEM']; ?></td>
                        <td><a href=<?php echo site_url()."/goods/del_category/".$cat['CAT_ID']; ?>>del</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
</div>
    

</body>
</html>