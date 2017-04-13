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
    <script type="text/javascript" src="<?php echo base_url().'js/angular.min.js'; ?>"></script>
    <script type="text/javascript" src="<?php echo base_url().'js/app.js'; ?>"></script>
</head>
<body ng-app="amrtaApp">
    
<div id="main-menu"><?php echo $mainmenu; ?></div>

<div class="container" ng-controller="memberCtrl">
    <div class="row">
        <div class="col-md-10">
            <div ng-class="alertClass" ng-cloak>
                {{ alertText }}
            </div>
        </div>
    </div>

    
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Member Baru</h4>
            </div>
            <div class="modal-body">
                <p>
                    <form action="" class="form-horizontal">
                        <div class="form-group">
                            <label for="customer_name" class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="customer_name" class="form-control" ng-model="customer_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone" class="col-sm-2 control-label">Handphone</label>
                            <div class="col-sm-10">
                                <input type="text" name="customer_phone" class="form-control" ng-model="customer_phone">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="DOB" class="col-sm-2 control-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" name="DOB" class="form-control" value="" ng-model="DOB">
                            </div>
                        </div>
                    </form>
                </p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" ng-click="addMember()" data-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
            </div>
        </div>

        </div>
    </div>
    
    
    <div class="row">
        <div class="col-md-10">
        
        <form class="form-inline">
            <select name="findby" class="form-control" autofocus ng-model="data.findby">
                <option value="customer_name">Nama</option>
                <option value="customer_phone">Handphone</option>
            </select>
            <input type="search" name="findvalue" ng-model="data.findvalue" class="form-control">
            <button type="submit" class="btn btn-primary" ng-click="selectMember()">Cari</button>
        </form><br>
            
        <!-- modal button to add new member -->
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Tambah Member</button><br><br>
        
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Handphone</th>
                <th>Pengaturan</th>
            </tr>
            <tr ng-repeat="member in members" ng-cloak>
                <td>{{ $index+1 }}</td>
                <td>{{ member.customer_name }}</td>
                <td>{{ member.customer_phone }}</td>
                <td><a href="#" class="btn btn-danger" ng-click="$parent.selectedMember=member.customer_phone;deleteMember();">Hapus</a></td>
            </tr>
        </table>
            
        
        </div>
    </div>
    
    
</div>

</body>
</html>