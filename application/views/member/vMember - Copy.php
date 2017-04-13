<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Member</title>
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
        
        <form class="form-inline" action="<?php echo site_url().'/member/list_member'; ?>" method="post">
            <select name="findby" class="form-control" autofocus>
                <option value="customer_name">Nama</option>
                <option value="customer_phone">Handphone</option>
            </select>
            <input type="search" name="findvalue" class="form-control">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form><br>
        
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Handphone</th>
                <th>Pengaturan</th>
            </tr>
            <?php $i = 0; ?>
            <?php foreach($members as $member): ?>
            <?php $i++; ?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $member['customer_name']; ?></td>
                <td><?php echo $member['customer_phone']; ?></td>
                <td><a href="<?php echo site_url().'/del_member/'.$member['customer_phone']; ?>" class="btn btn-danger">Hapus</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
            
        
        </div>
    </div>
    
    
</div>

</body>
</html>