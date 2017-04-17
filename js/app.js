var amrta = angular.module("amrtaApp", []);

amrta.controller('memberCtrl', function($scope, $http) {
    $scope.selectedMember = "";
    $scope.customer_phone = "";
    $scope.customer_name = "";
    $scope.DOB = new Date("2000-01-01");
    $scope.alertClass = "amrta-hidden";
    $scope.alertText = "";
    
    $http.get('http://localhost/amrtaayu/index.php/member/ajax_get_member/').then(function(res){
        $scope.members = res.data;
    });
    
    $scope.selectMember = function(){
        var p = {'findby' : $scope.data.findby, 'findvalue' : $scope.data.findvalue};
        $http.post('http://localhost/amrtaayu/index.php/member/ajax_select_member/', p).then(function(res){
            $scope.members = res.data;
            $scope.alertClass = "amrta-hidden";
        });
    }
    
    $scope.deleteMember = function(){
        if(delAns = confirm("Apakah yakin untuk menghapus member ini?")) {
            var p = {'customer_phone' : $scope.selectedMember};
            $http.post('http://localhost/amrtaayu/index.php/member/ajax_delete_member/', p).then(function(res){
                $scope.members = res.data;     
                $scope.alertClass = "alert alert-success";
                $scope.alertText = "Member telah berhasil dihapus";
            });
        }
    }
    
    $scope.addMember = function() {
        var p = {'customer_name' : $scope.customer_name, 'customer_phone' : $scope.customer_phone, 'DOB' : $scope.DOB};
        $http.post('http://localhost/amrtaayu/index.php/member/ajax_add_member/', p).then(function(res){
            $scope.members = res.data;
            $scope.alertClass = "alert alert-success";
            $scope.alertText = "Member telah berhasil ditambahkan";
        });
    }
});


amrta.controller('goodsCtrl', function($scope, $http){
    $scope.inputBarcode = "";
    $scope.alertClass = "amrta-hidden";
    $scope.alertText = '';
    
    $scope.findBarcode= function(){
        $scope.alertClass = "amrta-hidden";
        $scope.alertText = '';
        
        $scope.barcode_id = "";
        $scope.cat_name = "";
        $scope.item_name = "";
        $scope.item_price = "";
        $scope.created_date = "";
        
        if($scope.inputBarcode != '') {
            var p = {'barcode_id' : $scope.inputBarcode};
            $http.post('http://localhost/amrtaayu/index.php/goods/ajax_find_barcode/', p).then(function(res){
                $scope.barcode_id = res.data[0].barcode_id;
                $scope.cat_name = res.data[0].cat_name;
                $scope.item_name = res.data[0].item_name;
                $scope.item_price = res.data[0].item_price;
                $scope.created_date = Date.parse(res.data[0].created_date);
            });
        }
    }
    
    $scope.deleteBarcode = function(){
        if(confirm('Apakah anda yakin menghapus barcode ini?')) {
            var p = {'barcode_id' : $scope.barcode_id};
            $http.post('http://localhost/amrtaayu/index.php/goods/ajax_del_barcode/', p).then(function(res){
                $scope.barcode_id = '';
                $scope.cat_name = '';
                $scope.item_name = '';
                $scope.item_price = '';
                $scope.created_date = '';
                $scope.alertText = res.data;
                $scope.alertClass = 'alert alert-success';
            });
        }
    }
})

amrta.controller('catCtrl', function($scope, $http){
    $scope.cat_name = "";
    
    $http.get('http://localhost/amrtaayu/index.php/goods/ajax_get_all_category/').then(function(res){
        $scope.cats = res.data;
        console.dir(res);
    });
    
    $scope.saveCategory = function(catID, catName){
        let p = {'cat_id' : catID, 'cat_name' : catName};
        $http.post('http://localhost/amrtaayu/index.php/goods/ajax_save_category/', p).then(function(res){
            // no callback needed    
        });
    }
    
    $scope.addCategory = function(){
        let p = {'cat_name' : $scope.cat_name};
        $http.post('http://localhost/amrtaayu/index.php/member/ajax_add_category/', p).then(function(res){
            // refersh data cats
            $scope.cats = res.data;
            
            // set the message
            $scope.alertClass = "alert alert-success";
            $scope.alertText = "Member telah berhasil ditambahkan";
        });
    }
})


amrta.controller('cashierCtrl', function($scope, $http){
    $scope.members = [];
    
    $scope.findMember = function() {
        let p = {'search_type' : $scope.search_type, 'customer_info' : $scope.customer_info};
        $http.post('http://localhost/amrtaayu/index.php/cashier/ajax_find_member/', p).then(function(res){
            
        });
    }
    
})

;


$scope.addMember = function() {
        var p = {'customer_name' : $scope.customer_name, 'customer_phone' : $scope.customer_phone, 'DOB' : $scope.DOB};
        $http.post('http://localhost/amrtaayu/index.php/member/ajax_add_member/', p).then(function(res){
            $scope.members = res.data;
            $scope.alertClass = "alert alert-success";
            $scope.alertText = "Member telah berhasil ditambahkan";
        });
    }