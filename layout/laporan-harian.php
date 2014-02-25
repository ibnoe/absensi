<?php
require_once("class/database.class.php");
$db = new database;
$sql = "SELECT a.unit_biro, b.nama_id
		FROM kepegawaian_unitkerja a
		JOIN unit_organisasi b ON a.unit_biro = b.id
		WHERE a.unit_biro NOT IN ('BL.14', 'BL.15', 'BL.16')
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
		$("#search-form").accordion();
		$("#tgl_mulai").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#tgl_selesai").datepicker({ dateFormat: 'dd-mm-yy' });
		$("#btn-cari").click(function(){
			var unit_bagian = $("#unit_bagian").val();
			var tgl_mulai = $("#tgl_mulai").val();
			var tgl_selesai = $("#tgl_selesai").val();
			window.open("ajax/ajax-post.php?f=laporan_harian&unit_bagian="+unit_bagian+"&tgl_mulai="+tgl_mulai+"&tgl_selesai="+tgl_selesai, 'myreport', 'width=800, height=600, toolbar=0');
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
                	: <input type="text" name="tgl_mulai" id="tgl_mulai" />
                    Sampai 
                    <input type="text" name="tgl_selesai" id="tgl_selesai" />
                    &nbsp;
                    <input type="button" id="btn-cari" value="Lihat Laporan">
                </td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td style="font-size:10px; text-shadow:#000; line-height:110%;">*) Jumlah hari maksimal untuk satu periode laporan adalah 5 hari kerja.</td>
            </tr>
        </table>
    </div>
</div>