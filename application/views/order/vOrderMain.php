<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Amrta Ayu - Pesanan</title>
	<link href="<?php echo base_url().'css/bootstrap.min.css'; ?>" rel="stylesheet">
    <script src="<?php echo base_url().'js/angular.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/jquery-3.1.1.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/bootstrap.min.js'; ?>"></script>
    
    <script>
        var app = angular.module("myApp", []);

        app.controller("myCtrl", function($scope, $http) {
            $scope.formData = {};
            
            $scope.processForm = function() {
                console.log("submitting data...");
                $http({
                    method      : 'GET',
                    url         : 'http://localhost/amrtaayu/index.php/orders/test_ajax'
                }).then(function(response){
                    for(var i=0; i < response.data.length; i++) {
                        var todo = {"name" : response.data[i].item_name};
                        $scope.todos.push(todo);
                    }
                });
            };
        });
    </script>
</head>
<body ng-app="myApp" ng-controller="myCtrl">

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
        
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Tambah Pesanan</button>
            
        <a href=<?php echo site_url()."/orders/add_item";?> class="btn btn-success">Tambah Pesanan</a>
        <table class="table">
            <tr>
                <th>ID Order</th>
                <th>Nama Pemesan</th>
                <th>Tanggal Pembayaran</th>
                <th>Dibuat Oleh</th>
                <th>Tanggal Pembuatan</th>
            </tr>
        <?php foreach($orders as $order): ?>
            <tr>
                <td><a href="<?php echo site_url()."/orders/".$order['order_id']; ?>"><?php echo $order['order_id']; ?></a></td>
                <td><?php echo $order['customer_name']; ?></td>
                <td><?php echo $order['payment_date']; ?></td>
                <td><?php echo $order['username']; ?></td>
                <td><?php echo date('d-M-y', strtotime($order['created_date'])); ?></td>
            </tr>
        <?php endforeach; ?>
        </table>

        </div>
    </div>
    
    
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Data Pemesan</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    
    
    <br><br>
    <form ng-submit="processForm()">
        <!-- NAME -->
        <div id="name-group" class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" placeholder="Bruce Wayne" ng-model="formData.name">
            <span class="help-block"></span>
        </div>

        <!-- SUPERHERO NAME -->
        <div id="superhero-group" class="form-group">
            <label>Superhero Alias</label>
            <input type="text" name="superheroAlias" class="form-control" placeholder="Caped Crusader" ng-model="formData.superheroAlias">
            <span class="help-block"></span>
        </div>

        <!-- SUBMIT BUTTON -->
        <button type="submit" class="btn btn-success btn-lg btn-block">
            <span class="glyphicon glyphicon-flash"></span> Submit!
        </button>
    </form>

    <!-- SHOW DATA FROM INPUTS AS THEY ARE BEING TYPED -->
    <pre>
    {{ formData }}
    </pre>
    

    
</div>

</body>
</html>