<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Amrta Ayu</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu Kasir<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo site_url().'/cashier'; ?>">Kasir</a></li>
                        <li><a href="<?php echo site_url().'/cashier/trans'; ?>">List Transaksi</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu Barang<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo site_url().'/goods/category'; ?>">Kategori Barang</a></li>
                        <li><a href="<?php echo site_url().'/goods/item'; ?>">List Barang</a></li>
                        <li><a href="<?php echo site_url().'/goods/find_barcode'; ?>">Cari Barang</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu Pesanan<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo site_url().'/orders'; ?>">List Pesanan</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Menu Member<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="<?php echo site_url().'/member/list_member'; ?>">List Member</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if($this->session->userdata('user') == ""): ?>
                <li><a href="<?php echo site_url().'/user'; ?>">Login</a></li>
                <?php else: ?>
                <li><a href="<?php echo site_url().'/user/logout'; ?>">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>