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
    <script type="text/javascript" src="<?php echo base_url().'js/angular.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/app.js'; ?>"></script>
</head>
<body ng-app="amrtaApp">

<div id="main-menu"><?php echo $mainmenu; ?></div>

<div class="container" ng-controller="catCtrl">
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
    
    
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kategori Baru</h4>
            </div>
            <div class="modal-body">
                <p>
                    <form action="" class="form-horizontal">
                        <div class="form-group">
                            <label for="cat_name" class="col-sm-2 control-label">Kategori</label>
                            <div class="col-sm-10">
                                <input type="text" name="cat_name" class="form-control" ng-model="cat_name">
                            </div>
                        </div>
                    </form>
                </p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" ng-click="addCategory()" data-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>

        </div>
    </div>    
    
    
    
    
    <div class="row">
        <div class="col-md-10">
            <!--<a href=<?php echo site_url()."/goods/category/add_item";?> class="btn btn-success">Tambah Kategori</a>-->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">
                <span class="glyphicon glyphicon-plus"></span>Tambah Kategori
            </button>
            <table class="table table-striped">
                <tr>
                    <th>No</th>
                    <th>Kategori Barang</th>
                    <th>Jumlah Barang</th>
                    <th>Pengaturan</th>
                </tr>
                <tr ng-repeat="cat in cats" ng-cloak>
                    <td>{{ $index+1 }}</td>
                    <td>
                        <label ng-hide="editing">{{ cat.CAT_NAME }}</label>
                        <input type="text" ng-show="editing" class="form-control" ng-model="cat.CAT_NAME" value="{{ cat.CAT_NAME }}">
                    </td>
                    <td>{{ cat.CNT_ITEM }}</td>
                    <td>
                        <button type="button" class="btn btn-success" ng-show="editing" ng-click="saveCategory(cat.CAT_ID, cat.CAT_NAME); editing = !editing">
                            <span class="glyphicon glyphicon-ok"></span> Save
                        </button>
                        <button type="button" class="btn btn-danger">
                            <span class="glyphicon glyphicon-remove"></span> Delete
                        </button>
                        <button type="button" class="btn btn-info" ng-click="editing = !editing">
                            <span class="glyphicon glyphicon-pencil"></span> Edit
                        </button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
</div>
    

</body>
</html>