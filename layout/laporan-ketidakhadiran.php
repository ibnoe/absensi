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
<script>
	$(document).ready(function() {
		$("#search-form").accordion({autoHeight: false});
		$("#tgl_mulai").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_selesai").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#btn-cari").click(function(){
			var unit_bagian = $("#unit_bagian").val();
			var unit_biro = $("#unit_biro").val();
			var tgl_mulai = $("#tgl_mulai").val();
			var tgl_selesai = $("#tgl_selesai").val();
			var jenis_laporan = $("#jenis_laporan").val();
			var nip = $("#pegawai").val();
			var nama = $("#nama_pjs").val();
			var nip_pjs = $("#nip_pjs").val();
			var jabatan = $("#jabatan_pjs").val();
			window.open("ajax/ajax-post.php?f="+jenis_laporan+"&unit_bagian="+unit_bagian+"&unit_biro="+unit_biro+"&tgl_mulai="+tgl_mulai+"&tgl_selesai="+tgl_selesai+"&nip="+nip+"&nama_pjs="+nama+"&nip_pjs="+nip_pjs+"&jabatan="+jabatan, 'myreport', 'width=800, height=600, toolbar=0');
		});
		getBagian($("#unit_biro").val());
		$("#unit_biro").change(function(){
			getBagian($(this).val());
		})
		getPegawai($("#unit_bagian").val());
		$("#unit_bagian").change(function(){
			getPegawai($(this).val());
		})
		showPegawaiList($("#jenis_laporan").val())
	});
	
	function getBagian(biro)
	{
		$.ajax({
			url: "ajax/ajax-post.php?f=getBagian&biro="+biro,
			type: "get",
			success: function(response)
			{
				$("#unit_bagian").html(response)
			}
		})
	}
	
	function getPegawai(bagian)
	{
		$.ajax({
			url: "ajax/ajax-post.php?f=getPegawaiList&bagian="+bagian,
			type: "get",
			success: function(response)
			{
				$("#pegawai").html(response)
			}
		})
	}
	
	function showPegawaiList(reportType)
	{
		if (reportType == 'laporan_pegawai')
		{
			$("#pegawai_list").show();
		}
		else
		{
			$("#pegawai_list").hide();
			if(reportType=="daftar_absensi")
			{
				$("#tgl_mulai").val("01-01-<?=date("Y")?>");
			}
		}
	}
</script>
<div id="search-form">
	<h3><a href="#">Kriteria Pencarian</a></h3>
    <div>
    	<table>
        	<tr>
            	<td>Jenis Laporan</td>
                <td>: 
                	<select name="jenis_laporan" id="jenis_laporan" onchange="showPegawaiList(this.value)">
                    	<option value="laporan_ketidakhadiran">Rekapitulasi Absensi Bulanan - TKPKN</option>
                        <option value="laporan_uangmakan">Rekapitulasi Absensi Bulanan - Uang Makan</option>
                        <option value="laporan_pegawai">Laporan Absensi Bulanan Pegawai</option>
                        <option value="daftar_absensi">Daftar Absensi Pegawai</option>
                    </select>
                </td>
            </tr>
        	<tr>
            	<td>Unit Eselon 2</td>
                <td>: <select name="unit_biro" id="unit_biro"><?php echo $option;?></select></td>
            </tr>
            <tr>
            	<td>Unit Eselon 3</td>
                <td>: <select name="unit_bagian" id="unit_bagian"></select></td>
            </tr>
            <tr>
            	<td>Tanggal</td>
                <td>
                	: <input type="text" name="tgl_mulai" id="tgl_mulai" size="11" />
                    - 
                    <input type="text" name="tgl_selesai" id="tgl_selesai" size="11" />
                </td>
            </tr>
            <tr id="pegawai_list" style="display:none;">
            	<td>Pegawai</td>
                <td>: <select name="pegawai" id="pegawai"></select></td>
            </tr>
            <tr>
            	<td>Jabatan Penandatangan</td>
                <td>
                	: <input type="text" name="jabatan_pjs" id="jabatan_pjs" size="50" />
                </td>
            </tr>
            <tr>
            	<td>Nama Penandatangan</td>
                <td>
                	: <input type="text" name="nama_pjs" id="nama_pjs" size="50" />
                </td>
            </tr>
            <tr>
            	<td>NIP Penandatangan</td>
                <td>
                	: <input type="text" name="nip_pjs" id="nip_pjs" />
                </td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td>
                	&nbsp;<input type="button" id="btn-cari" value="Lihat Laporan">
                </td>
            </tr>
        </table>
    </div>
</div>