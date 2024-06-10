<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8" />
    <title><?php echo $cfg_webname; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="<?php echo $cfg_desc; ?>." name="description" />
    <meta content="<?php echo $cfg_author; ?>" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?php echo $cfg_baseurl; ?>assets/images/favicon_1.ico">
    <link href="<?php echo $cfg_baseurl; ?>assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo $cfg_baseurl; ?>assets/plugins/morris.js/morris.css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/pages.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/menu.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $cfg_baseurl; ?>assets/css/responsive.css" rel="stylesheet" type="text/css">
    <script src="<?php echo $cfg_baseurl; ?>assets/js/modernizr.min.js"></script>
</head>

<body class="fixed-left">
    <div id="wrapper">
        <div class="topbar">
            <div class="topbar-left">
                <div class="text-center">
                    <a href="<?php echo $cfg_baseurl; ?>index.php" class="logo"><i class="md md-terrain"></i> <span> <?php echo $cfg_logo_txt; ?> </span></a>
                </div>
            </div>
            <div class="navbar navbar-default" role="navigation">
                <div class="container">
                    <div class="">
                        <div class="pull-left">
                            <button type="button" class="button-menu-mobile open-left">
                                <i class="fa fa-bars"></i>
                            </button>
                            <span class="clearfix"></span>
                        </div>
                        <form class="navbar-form pull-left" role="search">
                            <div class="form-group">
                                <input type="text" class="form-control search-bar" placeholder="Aramak için yazın...">
                            </div>
                            <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                        </form>
                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li class="dropdown hidden-xs">
                                <a href="#" data-target="#" class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="true">
                                    <i class="md md-notifications"></i> <span class="badge badge-xs badge-danger">3</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-lg">
                                    <li class="text-center notifi-title">Bildirimler</li>
                                    <li class="list-group">
                                        <a href="javascript:void(0);" class="list-group-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <em class="fa fa-user-plus fa-2x text-info"></em>
                                                </div>
                                                <div class="media-body clearfix">
                                                    <div class="media-heading">Yeni kullanıcı kaydedildi</div>
                                                    <p class="m-0">
                                                        <small>10 okunmamış mesajınız var</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" class="list-group-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <em class="fa fa-diamond fa-2x text-primary"></em>
                                                </div>
                                                <div class="media-body clearfix">
                                                    <div class="media-heading">Yeni ayarlar</div>
                                                    <p class="m-0">
                                                        <small>Yeni ayarlar mevcut</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" class="list-group-item">
                                            <div class="media">
                                                <div class="media-left">
                                                    <em class="fa fa-bell-o fa-2x text-danger"></em>
                                                </div>
                                                <div class="media-body clearfix">
                                                    <div class="media-heading">Güncellemeler</div>
                                                    <p class="m-0">
                                                        <small><span class="text-primary">2</span> yeni güncelleme mevcut</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);" class="list-group-item">
                                            <small>Tüm bildirimleri gör</small>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="hidden-xs">
                                <a href="#" id="btn-fullscreen" class="waves-effect waves-light"><i class="md md-crop-free"></i></a>
                            </li>
                            <li class="hidden-xs">
                                <a href="#" class="right-bar-toggle waves-effect waves-light"><i class="md md-chat"></i></a>
                            </li>
                            <?php if (isset($_SESSION['user'])) { ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="true"><img src="<?php echo $cfg_baseurl; ?>assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle"> </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $cfg_baseurl; ?>main/settings_akun.php"><i class="md md-face-unlock"></i> Profil</a></li>
                                    <li><a href="<?php echo $cfg_baseurl; ?>logout.php"><i class="md md-settings-power"></i> Çıkış</a></li>
                                </ul>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft">
                <div class="user-details">
                    <div class="pull-left">
                        <img src="<?php echo $cfg_baseurl; ?>assets/images/users/avatar-1.jpg" alt="" class="thumb-md img-circle">
                    </div>
                    <?php if (isset($_SESSION['user'])) { ?>
                    <div class="user-info">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><?php echo $sess_username; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo $cfg_baseurl; ?>main/settings_akun.php"><i class="md md-face-unlock"></i> Profil<div class="ripple-wrapper"></div></a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>logout.php"><i class="md md-settings-power"></i> Çıkış</a></li>
                            </ul>
                        </div>
                        <p class="text-muted m-0"><?php echo $data_user['level']; ?></p>
                    </div>
                    <?php } ?>
                </div>
                <div id="sidebar-menu">
                    <ul>
                        <?php if (isset($_SESSION['user'])) { ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-user"></i>
                                <span>Hesap</span>
                                <span class="pull-right-container"></span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo $cfg_baseurl; ?>main/settings_akun.php">Ayarlar</a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>logout">Çıkış</a></li>
                            </ul>
                        </li>
                        <?php if ($data_user['level'] != "Member") { ?>
                        <li class="has_sub">
                            <a href="#" class="waves-effect"><i class="fa fa-user-plus"></i><span> Personel Menüsü </span><span class="pull-right"><i class="md md-add"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="<?php echo $cfg_baseurl; ?>staff/add_member.php">Üye Ekle</a></li>
                                <?php if ($data_user['level'] != "Agen") { ?>
                                <li><a href="<?php echo $cfg_baseurl; ?>staff/add_agen.php">Ajan Ekle</a></li>
                                <?php if ($data_user['level'] != "Reseller") { ?>
                                <li><a href="<?php echo $cfg_baseurl; ?>staff/add_reseller.php">Bayii Ekle</a></li>
                                <?php if ($data_user['level'] != "Admin") { ?>
                                <li><a href="<?php echo $cfg_baseurl; ?>staff/add_admin.php">Admin Ekle</a></li>
                                <?php } } } ?>
                                <li><a href="<?php echo $cfg_baseurl; ?>staff/transfer_balance.php">Bakiye Transferi</a></ul>
                        </li>
                        <?php } ?>
                        <?php if ($data_user['level'] == "Developers") { ?>
                        <li class="has_sub">
                            <a href="#" class="waves-effect"><i class="fa fa-globe"></i><span> Yönetici Menüsü </span><span class="pull-right"><i class="md md-add"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="<?php echo $cfg_baseurl; ?>admin/users.php">Kullanıcıları Yönet</a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>admin/services.php">Hizmetleri Yönet</a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>admin/orders.php">Sosyal Medya Siparişlerini Yönet</a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>admin/news.php">Haberleri Yönet</a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>admin/staff.php">Personeli Yönet</a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>admin/transfer_history.php">Transfer Geçmişi</a></li>
                            </ul>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="<?php echo $cfg_baseurl; ?>index.php"> <i class="fa fa-home icon"> <b class="bg-success"></b> </i> <span> Ana Sayfa</span> </a>
                        </li>
                        <li class="has_sub">
                            <a href="#" class="waves-effect"><i class="fa fa-shopping-cart"></i><span> Yeni Sipariş </span><span class="pull-right"><i class="md md-add"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="<?php echo $cfg_baseurl; ?>order/sosmed.php"><span> Sosyal Medya Siparişi</span> </a></li>
                                <li><a href="<?php echo $cfg_baseurl; ?>order/history/sosmed.php"><span> Sosyal Medya Geçmişi</span> </a></li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li>
                            <a href="<?php echo $cfg_baseurl; ?>index.php"> <i class="fa fa-sign-in icon"> <b class="bg-success"></b> </i> <span>Giriş</span> </a>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="<?php echo $cfg_baseurl; ?>main/kontak_admin.php"> <i class="fa fa-user-plus icon"> <b class="bg-warning"></b> </i> <span>Personel Listesi</span> </a>
                        </li>
                        <li>
                            <a href="<?php echo $cfg_baseurl; ?>api_doc.php"> <i class="fa fa-random icon"> <b class="bg-danger"></b> </i> <span>API Dokümantasyonu</span> </a>
                        </li>
                        <li class="has_sub">
                            <a href="#" class="waves-effect"><i class="fa fa-tags"></i><span> Fiyat Listesi </span><span class="pull-right"><i class="md md-add"></i></span></a>
                            <ul class="list-unstyled">
                                <li><a href="<?php echo $cfg_baseurl; ?>price/sosial_media.php"><span>Sosyal Medya</span> </a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo $cfg_baseurl; ?>main/terms_panel.php"> <i class="fa fa-bolt icon"> <b class="bg-info"></b> </i> <span>Hizmet Şartları</span> </a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="content-page">
            <div class="content">
                <div class="container">
