<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Barang</title>
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
            <form class="form-inline" action="<?php echo site_url().'/goods/item'; ?>" method="post">
                <label for="item_name">Nama Barang</label>
                <input type="search" name="item_name" class="form-control" autofocus>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form><br>
            <a href="<?php echo site_url().'/goods/item/add_item';?>" class="btn btn-success">Tambah barang</a>
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Kategori Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Barang Tersedia</th>
                    <th>Pengaturan</th>
                </tr>
                <?php $i = 0; ?>
                <?php foreach($items as $item): ?>
                    <?php $i = $i + 1; ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $item['cat_name']; ?></td>
                        <td><a href=<?php echo site_url()."/goods/item/".$item['item_id']?>><?php echo $item['item_name']; ?></a></td>
                        <td><?php echo 'Rp'.number_format($item['item_ori_price']); ?></td>
                        <td><?php echo 'Rp'.number_format($item['item_price']); ?></td>
                        <td><?php echo $item['avail_item']; ?></td>
                        <td><a href="<?php echo site_url().'/goods/del_item/'.$item['item_id']; ?>" class="btn btn-danger">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
        
</div>

</body>
</html>