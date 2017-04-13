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
            
            <form class="form-horizontal" method="post" action="<?php echo site_url().'/goods/add_item'; ?>">
                <div class="form-group">
                    <label for="cat_id" class="col-sm-2 control-label">Kategori Barang</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="cat_id" autofocus>
                        <?php foreach($cats as $cat): ?>
                            <option value=<?php echo $cat['CAT_ID']; ?>><?php echo $cat['CAT_NAME']; ?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="item_name" class="col-sm-2 control-label">Nama Barang</label>
                    <div class="col-sm-8">
                        <input type="text" name="item_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="item_ori_price" class="col-sm-2 control-label">Harga Beli</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-addon">Rp</div>
                            <input type="number" name="item_ori_price" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="item_price" class="col-sm-2 control-label">Harga Jual</label>
                    <div class="col-sm-8">
                        <div class="input-group">
                            <div class="input-group-addon">Rp</div>
                            <input type="number" name="item_price" class="form-control">
                        </div>
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