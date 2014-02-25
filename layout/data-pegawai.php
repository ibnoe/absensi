<?php
require_once("class/database.class.php");
$db = new database;
$sql = "SELECT a.unit_biro, b.v_uraian as nama_id
		FROM pegawai_unitkerja a
		JOIN t_par_instansi b ON a.unit_biro = b.c_kode
		GROUP BY a.unit_biro";
$biroArr = $db->dbFetchArray($sql);
$option = "";
foreach ( $biroArr as $biro )
{
	$option .= "<option value='".$biro['unit_biro']."'>".$biro['nama_id']."</option>";
}

?>
<script type="text/javascript" src="asset/js/page/data-pegawai.js"></script>
<br /><br />
<table width="80%" cellspacing="0" cellpadding="0" class="maingrid">
	<thead id="section-unit-kerja-1">
    	<td colspan="4" class="gridheader1">
        Unit Eselon 2: <select name="unit_biro" id="unit_biro"><?php echo $option;?></select>
        </td>
    </thead>
    <thead id="section-unit-kerja-2">
    	<td colspan="4" class="gridheader1">
        Unit Eselon 3: <select name="unit_bagian" id="unit_bagian"></select>
        </td>
    </thead>
    <thead>
    	<td colspan="4" class="gridheader1">
        	Cari berdasarkan: 
            <select id="qtype">
            	<option value="a.nip">NIP</option>
                <option value="nama">Nama Pegawai</option>
			</select>
            <input type="text" id="query" size="50" />
            <input type="button" id="btnCari" value="Cari Pegawai" onclick="cariPegawai();" />
        </td>
    </thead>
    <thead>
    	<td colspan="4" class="gridheader1">
        	<input type="button" id="btnEdit" value="Mutasi Pegawai" />
            <input type="button" id="btnSetRangeAbsen" value="Range Waktu Absen" />
        </td>
    </thead>
	<thead align="center" id="gridColumn">
    	<td width="10px" class="gridheader">No.</td>
        <td width="100px" class="gridheader">NIP</td>
        <td width="150px" class="gridheader">Nama</td>
        <td width="250px" class="gridheader">Unit Kerja</td>
    </thead>
</table>
<div id="pegawaiForm" style="display:none"></div>
<div id="SetRangeAbsenForm" style="display:none"></div>




