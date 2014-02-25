<?php
session_start();
$data = array("title"=>array("data-pegawai"=>"Data Pegawai",
							"data-absensi"=>"Data Absensi",
							"data-tkpkn"=>"Data TKPKN",
							"laporan-harian"=>"Laporan Harian",
							"laporan-absensi"=>"Laporan Absensi",
							"laporan-ketidakhadiran"=>"Laporan Ketidakhadiran",
							"user-setting"=>"User Setting"));
require_once("class/database.class.php");
$db = new database;
$sqlMasuk = "SELECT * FROM time_setting WHERE type = 'I'";
$dataMasuk = $db->dbFetchArray($sqlMasuk);
$sqlKeluar = "SELECT * FROM time_setting WHERE type = 'O'";
$dataKeluar = $db->dbFetchArray($sqlKeluar);
$_SESSION['dataMasuk'] = $dataMasuk[0];
$_SESSION['dataKeluar'] = $dataKeluar[0];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistem Administrasi Absensi Pegawai</title>
	<link rel="stylesheet" href="asset/css/main.css" type="text/css" />
    <link rel="stylesheet" href="asset/css/jquery.autocomplete.css" type="text/css" />
    <link rel="stylesheet" href="asset/css/jqueryui/jquery-ui.css" type="text/css" />
    <link rel="stylesheet" href="asset/css/flexigrid/flexigrid.css" type="text/css" />
    <link rel="stylesheet" href="asset/css/grid.css" type="text/css" />
    <script type="text/javascript" src="asset/js/jquery.js"></script>
	<script type="text/javascript" src="asset/js/jquery-ui.js"></script>
    <script type="text/javascript" src="asset/js/jquery.ajaxQueue.js"></script>
    <script type="text/javascript" src="asset/js/jquery.bgiframe.js"></script>
    <script type="text/javascript" src="asset/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="asset/js/jquery.easing.js"></script>
<?php
if ($_SESSION['user'])
{
?>
    <script type="text/javascript" src="asset/js/jquery-bp.js"></script>
    <script type="text/javascript" src="asset/js/navigation.js"></script>
<?php } ?>
    <script type="text/javascript" src="asset/js/flexigrid.js"></script>
  	<script type="text/javascript">
    	$(document).ready(function(){
			$(".content").tabs();
			$("#btn-login").click(function(){
				var username = $("#username").val();
				var password = $("#password").val();
				$.ajax({
					url: "ajax/ajax-post.php?f=login",
					type: "post",
					data: {username: username, password: password},
					success: function(response)
					{
						if(response=="3")
						{
							$("#login-msg").html("login sukses");
							setInterval('location.reload()', 2000);
						}
						else if(response=="2")
						{
							$("#login-msg").html("password salah");
						}
						else
						{
							$("#login-msg").html("username tidak ditemukan");
						}
						
					}
				})
			})
		});
    </script>
</head>
<body>
	<div id="header" align="center"><img src="asset/img/header.png" /></div>
<?php
if ($_SESSION['user'])
{
	include("layout/menu.php");
?>
	<div class="container">
		<div class="content">
        	<ul>
            	<li><a href="#tabs-1"><?php echo $data['title'][$mod]?></a></li>
            </ul>
            <div id="tabs-1">
            <?php
			$content_file = "layout/".$mod.".php";
			if ( file_exists( $content_file ) )
			{
				include( $content_file );
			}
			else
			{
				include("layout/underconstruction.php");	
			}
			?>
            </div>
            <div id="footer">copyright 2010</div>
		</div>
    </div>
<?php
}
else
{
	include("layout/login-page.php");
}
?>
</body>


