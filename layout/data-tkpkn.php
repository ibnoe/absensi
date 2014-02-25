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
<script type="text/javascript" src="asset/js/page/data-tkpkn.js"></script>
<br /><br />
<table width="80%" cellspacing="0" cellpadding="0" class="maingrid">
	<thead>
    	<td colspan="6" class="gridheader1">
        	Tanggal: 
            <input type="text" id="tanggal" size="14" value="<?php echo date('d-m-Y', mktime(0,0,0,date('m'), date('d')-1, date('Y')))?>" /> <i>Format: dd-mm-YYYY</i>
        </td>
    </thead>
    <thead id="section-unit-kerja-1">
    	<td colspan="7" class="gridheader1">
        Unit Eselon 2: <select name="unit_biro" id="unit_biro"><?php echo $option;?></select>
        </td>
    </thead>
    <thead id="section-unit-kerja-2">
    	<td colspan="7" class="gridheader1">
        Unit Eselon 3: <select name="unit_bagian" id="unit_bagian"></select>
        </td>
    </thead>
    <thead>
    	<td colspan="6" class="gridheader1">
        	Cari berdasarkan: 
            <select id="qtype">
            	<option value="nip">NIP</option>
                <option value="nama">Nama Pegawai</option>
			</select>
            <input type="text" id="query" size="50" />
            <input type="button" id="btnCari" value="Cari Pegawai" onclick="cariPegawai();" />
        </td>
    </thead>
    <thead align="center" id="gridColumn">
    	<td width="10px" class="gridheader">No.</td>
        <td width="100px" class="gridheader">NIP</td>
        <td width="150px" class="gridheader">Nama</td>
        <td width="250px" class="gridheader">Unit Kerja</td>
        <td width="100px" class="gridheader">Potongan TKPKN</td>
        <td width="100px" class="gridheader">Keterangan</td>
    </thead>
</table>
<div id="pegawaiForm" style="display:none"></div>




