<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Kasir</title>
    <link href="<?php echo base_url().'css/bootstrap.min.css'; ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url().'js/jquery-3.1.1.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/bootstrap.min.js'; ?>"></script>
</head>
<body>
    

<?php echo $mainmenu; ?>
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
        <div class="col-md-4">
            <form class="form-inline" action="<?php echo site_url().'/member/find_member'; ?>" method="post">
                <div class="form-group">
                    <label for="customer_phone">Handphone</label>
                    <input type="text" name="customer_phone" class="form-control">
                </div>
                <button type="submit" class="btn btn-default">Cari</button>
            </form>
            <?php if($this->input->cookie('member_name') != ""): ?>
                <?php echo $this->input->cookie('member_name')."-".$this->input->cookie('member_hp'); ?>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <form class="form-horizontal" action="<?php echo site_url().'/cashier/clear'; ?>" method="post">
                <button type="submit" class="btn btn-danger">Hapus Semua</button>
            </form>
        </div>
    </div><br>
    
    <div class="row">
        <div class="col-md-4">
            <form class="form-inline" action="<?php echo site_url().'/cashier'; ?>" method="post">
                <div class="form-group">
                    <label for="barcode_id">Barcode</label>
                    <input type="text" name="barcode_id" class="form-control" autofocus>
                    <input type="hidden" name="param" value="additem">
                </div>
                <button type="submit" class="btn btn-success">Tambah</button>
            </form>
        </div>
        <div class="col-md-6">
            <form class="form-horizontal" action="<?php echo site_url().'/cashier/checkout'; ?>" method="post">
                <table class="table table-striped">
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                    </tr>    
                <?php foreach($cart as $item): ?>
                    <tr>
                        <td><?php echo $item['barcode_id']; ?></td>
                        <td><?php echo $item['item_name']; ?></td>
                        <td align="right"><?php echo "Rp".number_format($item['item_price']); ?></td>
                    </tr>
                <?php endforeach; ?>
                    <tr>
                        <td colspan="2">Jumlah Barang</td>
                        <td align="right"><?php echo $cart_sum['item_count']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Total Harga</td>
                        <td align="right"><?php echo "Rp".number_format($cart_sum['item_price']); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">Bayar</td>
                        <td align="right">
                            <div class="input-group">
                                <div class="input-group-addon">Rp</div>
                                <input type="number" name="pay_amount" class="form-control">
                            </div>
                        </td>
                    </tr>
                </table>
                <button class="btn btn-primary" type="submit">Bayar</button>
            </form>
        </div>
    </div>
    
</div>

</body>
</html>
