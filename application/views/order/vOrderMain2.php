<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Kasir</title>

	<style type="text/css">

	</style>
</head>
<body>

<div id="container">
    
    <span id="error_message"><?php echo $this->session->flashdata('error_message'); ?></span>
    <span id="success_message"><?php echo $this->session->flashdata('success_message'); ?></span>
    
    <div id="body-custid">
    <?php echo form_open('member/find_member_order'); ?>
        <label for="customer_phone">Handphone</label>
        <input type="text" name="customer_phone">
        <input type="submit" value="Cari"><br>
        <a href=<?php echo site_url()."/member/add_member_order"; ?>>Tambah member baru</a>  
    <?php echo form_close(); ?>
    <?php if($this->input->cookie('member_name') != ""): ?>
        <?php echo $this->input->cookie('member_name')."-".$this->input->cookie('member_hp'); ?>
    <?php endif; ?>
    </div><br>
    
    <div id="body-cashier">
    
    <?php echo form_open('cashier/order'); ?>
        <label for="barcode_id">Barcode</label>
        <input type="text" name="barcode_id">
        <input type="hidden" name="param" value="additem">
        <input type="submit" value="Tambah">
    <?php echo form_close(); ?>    
    
    </div><br><br>
    
    <div id="body-list-item">
    <?php if(isset($cart)): ?>
    <?php echo form_open('cashier/checkout'); ?>
    <table>
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
            <td colspan="2" align="right">Jumlah Barang</td>
            <td align="right"><?php echo $cart_sum['item_count']; ?></td>
        </tr>
        <tr>
            <td colspan="2" align="right">Total Harga</td>
            <td align="right"><?php echo "Rp".number_format($cart_sum['item_price']); ?></td>
        </tr>
        <tr>
            <td colspan="2" align="right">Bayar</td>
            <td align="right"><input type="number" name="pay_amount"></td>
        </tr>
        <tr>
            <td colspan="3"><input type="submit" value="Bayar"></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
    <?php endif; ?>
    </div>
    
</div>

</body>
</html>