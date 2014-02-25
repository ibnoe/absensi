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
			var nama = $("#nama_pjs").val();
			var nip = $("#nip_pjs").val();
			var jabatan = $("#jabatan_pjs").val();
			window.open("ajax/ajax-post.php?f=laporan_absensi&unit_bagian="+unit_bagian+"&unit_biro="+unit_biro+"&tgl_mulai="+tgl_mulai+"&tgl_selesai="+tgl_selesai+"&nama="+nama+"&nip="+nip+"&jabatan="+jabatan, 'myreport', 'width=800, height=600, toolbar=0');
		});
		getBagian($("#unit_biro").val());
		$("#unit_biro").change(function(){
			getBagian($(this).val());
		})
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
</script>
<div id="search-form">
	<h3><a href="#">Kriteria Pencarian</a></h3>
    <div>
    	<table>
        	<tr>
            	<td>Unit Biro</td>
                <td>: <select name="unit_biro" id="unit_biro"><?php echo $option;?></select></td>
            </tr>
            <tr>
            	<td>Unit Bagian</td>
                <td>: <select name="unit_bagian" id="unit_bagian"></select></td>
            </tr>
            <tr>
            	<td>Tanggal</td>
                <td>
                	: <input type="text" name="tgl_mulai" id="tgl_mulai" size="11" />
                    Sampai 
                    <input type="text" name="tgl_selesai" id="tgl_selesai" size="11" />
                    &nbsp;
                    
                </td>
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
            <tr>
            	<td>&nbsp;</td>
                <td style="font-size:10px; text-shadow:#000; line-height:110%;"></td>
            </tr>
        </table>
    </div>
</div>