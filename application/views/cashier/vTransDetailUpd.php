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
            
        <form action="<?php echo site_url().'/cashier/discount/UPDATE'; ?>" class="form-horizontal" method="post">
            <input type="hidden" name="purchase_id" value="<?php echo $info['purchase_id']; ?>">
            <table class="table">
                <tr>
                    <td>ID Transaksi</td>
                    <td><?php echo $info['purchase_id']; ?></td>
                </tr>
                <tr>
                    <td>Tanggal Transaksi</td>
                    <td><?php echo date('d-M-y', strtotime($info['purchase_date'])); ?></td>
                </tr>
                <tr>
                    <td>Jam Transaksi</td>
                    <td><?php echo date('H:i', strtotime($info['purchase_date'])); ?></td>
                </tr>
                <tr>
                    <td>Nama Member</td>
                    <td><?php echo $info['customer_name']; ?></td>
                </tr>
                <tr>
                    <td>Total Belanja</td>
                    <td><?php echo "Rp".number_format($info['tran_amount']); ?></td>
                </tr>
                <tr>
                    <td>Pembayaran</td>
                    <td><?php echo "Rp".number_format($info['payment_amount']); ?></td>
                </tr>
            </table><br>

            <h2>Daftar Barang Belanja</h2>
            <table class="table">
                <tr>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                </tr>
            <?php foreach($trans as $tran): ?>
                <tr>
                    <input type="hidden" name="barcode[]" value="<?php echo $tran['barcode_id']; ?>">
                    <input type="hidden" name="initialprice[]" value="<?php echo $tran['item_price']; ?>">
                    <td><?php echo $tran['barcode_id']; ?></td>
                    <td><?php echo $tran['item_name']; ?></td>
                    <td>
                        <div class="input-group">
                            <div class="input-group-addon">Rp</div>
                            <input type="number" name="price[]" class="form-control" value="<?php echo $tran['item_price']; ?>" autofocus>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </table>
            <button type="submit" class="btn btn-primary">Simpan</button>
            
        </form>
            
        </div>
    </div>

    
</div>

</body>
</html>