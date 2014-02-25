<?php
@session_start();
list($h_masuk1, $m_masuk1, $s_masuk1) = explode(":", $_SESSION['dataMasuk']['time_start']);
list($h_keluar1, $m_keluar1, $s_keluar1) = explode(":", $_SESSION['dataKeluar']['time_start']);
list($h_masuk2, $m_masuk2, $s_masuk2) = explode(":", $_SESSION['dataMasuk']['time_end']);
list($h_keluar2, $m_keluar2, $s_keluar2) = explode(":", $_SESSION['dataKeluar']['time_end']);
?>
<script type="text/javascript" src="asset/js/page/load-data.js"></script>
<div id="form">
	<?php
	if($_SESSION['userlevel']!="admin")
	{
		die("Fitur ini hanya dapat digunakan oleh pengguna dengan hak akses administrator.");	
	}
	?>
    <h3><a href="#">Load Data Absensi</a></h3>
	<div align="center">
		<input type="button" value="Load Data Absen Pegawai" id="btnLoad" />
	</div>
	<h3><a href="#">Setting Waktu Absen</a></h3>
	<div>
		<table>
        	<tr>
            	<td>Waktu masuk</td>
                <td>
                	<input type="text" size="2" id="h_masuk1" value="<?= $h_masuk1?>" />:
                    <input type="text" size="2" id="m_masuk1" value="<?= $m_masuk1?>" />:
                    <input type="text" size="2" id="s_masuk1" value="<?= $s_masuk1?>" />
                    &nbsp;s/d&nbsp;
                    <input type="text" size="2" id="h_masuk2" value="<?= $h_masuk2?>" />:
                    <input type="text" size="2" id="m_masuk2" value="<?= $m_masuk2?>" />:
                    <input type="text" size="2" id="s_masuk2" value="<?= $s_masuk2?>" />
                </td>
            </tr>
            <tr>
            	<td>Waktu pulang</td>
                <td>
                	<input type="text" size="2" id="h_keluar1" value="<?= $h_keluar1?>" />:
                    <input type="text" size="2" id="m_keluar1" value="<?= $m_keluar1?>" />:
                    <input type="text" size="2" id="s_keluar1" value="<?= $s_keluar1?>" />
                    &nbsp;s/d&nbsp;
                    <input type="text" size="2" id="h_keluar2" value="<?= $h_keluar2?>" />:
                    <input type="text" size="2" id="m_keluar2" value="<?= $m_keluar2?>" />:
                    <input type="text" size="2" id="s_keluar2" value="<?= $s_keluar2?>" />
                </td>
            </tr>
            <tr>
            	<td>&nbsp;</td>
                <td>
                	<input type="button" id="save_time" value="Simpan" />
                </td>
            </tr>
        </table>
	</div>
    <h3><a href="#">Input Libur Nasional</a></h3>
	<div>
		<table width="80%" cellspacing="0" cellpadding="0" class="maingrid">
        <thead>
            <td colspan="3" class="gridheader1">
                <input type="button" id="btnEdit" value="Edit Hari Libur" />
                &nbsp;
                <input type="button" id="btnAdd" value="Tambah Hari Libur" />
                &nbsp;
                <input type="button" id="btnDelete" value="Hapus Hari Libur" />
            </td>
        </thead>
        <thead align="center" id="gridColumn">
            <td width="10px" class="gridheader">No.</td>
            <td width="100px" class="gridheader">Tanggal</td>
            <td width="150px" class="gridheader">Keterangan</td>
        </thead>
    </table>
	</div>
    <h3><a href="#">Input Cuti Bersama</a></h3>
	<div>
		<table width="80%" cellspacing="0" cellpadding="0" class="maingridcuti">
        <thead>
            <td colspan="3" class="gridheader1">
                <input type="button" id="btnEditCuti" value="Edit Cuti Bersama" />
                &nbsp;
                <input type="button" id="btnAddCuti" value="Tambah Cuti Bersama" />
                &nbsp;
                <input type="button" id="btnDeleteCuti" value="Hapus Cuti Bersama" />
            </td>
        </thead>
        <thead align="center" id="gridColumn">
            <td width="10px" class="gridheader">No.</td>
            <td width="100px" class="gridheader">Tanggal</td>
            <td width="150px" class="gridheader">Keterangan</td>
        </thead>
    </table>
	</div>
</div>
<div id="pegawaiForm" style="display:none"></div>
