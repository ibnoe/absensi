<?php
$mod = ($_GET['mod']!="") ? $_GET['mod'] : "data-pegawai";
?>
<div id="navigation" class="container">
    <div id="data-pegawai" class="pri-nav <?php if($mod=='data-pegawai'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=data-pegawai">Data Pegawai</a></div>
    </div>
    <div id="data-absensi" class="pri-nav <?php if($mod=='data-absensi'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=data-absensi">Data Absensi</a></div>
    </div>
    <div id="data-tkpkn" class="pri-nav <?php if($mod=='data-tkpkn'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=data-tkpkn">Data TKPKN</a></div>
    </div>
    <div id="laporan-absensi" class="pri-nav <?php if($mod=='laporan-absensi'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=laporan-absensi">Laporan Absensi</a></div>
    </div>
    <div id="laporan-ketidakhadiran" class="pri-nav <?php if($mod=='laporan-ketidakhadiran'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=laporan-ketidakhadiran">Laporan Ketidakhadiran</a></div>
    </div>
    <div id="load-data" class="pri-nav <?php if($mod=='load-data'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=load-data">Load Data</a></div>
    </div>
    <div id="user-setting" class="pri-nav <?php if($mod=='user-setting'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=user-setting">User Setting</a></div>
    </div>
    <div id="logout" class="pri-nav <?php if($mod=='logout'){ echo "active";} else {echo "";} ?>">
    	<div><a href="?mod=logout">Log Out</a></div>
    </div>
</div>