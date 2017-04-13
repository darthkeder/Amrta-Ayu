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
            <form class="form-inline" action="<?php echo site_url().'/cashier/trans'; ?>" method="post">
                <label for="date_start">Periode</label>
                <input type="date" name="date_start" class="form-control" value="<?php echo date('Y-m-d'); ?>" autofocus> - 
                <input type="date" name="date_end" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form><br>
            <table class="table table-striped">
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Jam Transaksi</th>
                    <th>Nama Member</th>
                    <th>Total Belanja</th>
                    <th>Pembayaran</th>
                </tr>

                <?php foreach($trans as $tran): ?>
                <tr>
                    <td><a href="<?php echo site_url()."/cashier/trans/".$tran['purchase_id']; ?>"><?php echo $tran['purchase_id']; ?></a></td>
                    <td align="center"><?php echo date('d-M-y', strtotime($tran['purchase_date'])); ?></td>
                    <td align="center"><?php echo date('H:i', strtotime($tran['purchase_date'])); ?></td>
                    <td><?php echo $tran['customer_name']; ?></td>
                    <td align="right"><?php echo "Rp".number_format($tran['tran_amount']); ?></td>
                    <td align="right"><?php echo "Rp".number_format($tran['payment_amount']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>    
        </div>
    </div>

    
</div>

</body>
</html>