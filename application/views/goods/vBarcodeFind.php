<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Cari Barcode</title>
	<link href="<?php echo base_url().'css/bootstrap.min.css'; ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url().'js/jquery-3.1.1.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/bootstrap.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/angular.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/app.js'; ?>"></script>
</head>
<body ng-app="amrtaApp">

<div id="main-menu"><?php echo $mainmenu; ?></div>
    
<div class="container" ng-controller="goodsCtrl">
	<div class="row">
        <div class="col-md-10">
            <div ng-class="alertClass" ng-cloak>
                {{ alertText }}
            </div>
        </div>
    </div>

	<div class="row">
        <div class="col-md-10">
            
        <form class="form-inline">
            <label for="barcode_id">Barcode</label>
            <input type="text" name="barcode_id" class="form-control" autofocus ng-model="inputBarcode">
            <button type="submit" class="btn btn-primary" ng-click="findBarcode()">Cari</button>
        </form><br>
    
        <table class="table" ng-cloak>
            <tr>
                <td>Barcode</td>
                <td>{{ barcode_id }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>{{ cat_name }}</td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td>{{ item_name }}</td>
            </tr>
            <tr>
                <td>Harga Jual</td>
                <td>{{ item_price | number : fractionSize }}</td>
            </tr>
            <tr>
                <td>Tanggal Input</td>
                <td>{{ created_date | date:'dd-MMM-yyyy' }}</td>
            </tr>
        </table>
        <a href="" class="btn btn-danger" ng-click="deleteBarcode()">Hapus</a>
            
	</div>
</div>

</body>
</html>