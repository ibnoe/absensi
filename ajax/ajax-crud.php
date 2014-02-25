<?php
session_start();
require_once("../class/database.class.php");
$db = new database;

switch ($_GET['f'])
{
	case "simpanDataPegawai":		
		simpanDataPegawai();
		break;
		
	case "simpanDataAbsensi":		
		simpanDataAbsensi();
		break;
	
	case "simpanTimeSetting":		
		simpanTimeSetting();
		break;
	
	case "simpanDataPengguna":		
		simpanDataPengguna();
		break;
	
	case "simpanHariLibur":		
		simpanHariLibur();
		break;
	
	case "hapusHariLibur":		
		hapusHariLibur();
		break;
	
	case "simpanCutiBersama":		
		simpanCutiBersama();
		break;
		
	case "hapusCutiBersama":		
		hapusCutiBersama();
		break;
		
	case "fillYearDate":		
		fillYearDate();
		break;
	
	case "simpanDataRangeAbsen":		
		simpanDataRangeAbsen();
		break;
}

function simpanDataRangeAbsen()
{
	global $db;
	$CardNo = trim($_POST['CardNo']);
	$nip = trim($_POST['nip']);
	$keterangan = trim($_POST['keterangan']);
	$tanggalMulai = substr($_POST['tanggalMulai'],6,4).'-'.substr($_POST['tanggalMulai'],3,2).'-'.substr($_POST['tanggalMulai'],0,2);
	$tanggalAkhir = substr($_POST['tanggalAkhir'],6,4).'-'.substr($_POST['tanggalAkhir'],3,2).'-'.substr($_POST['tanggalAkhir'],0,2);
	$sql = "INSERT INTO range_cuti(fsCardNo,fsIDNo,keterangan,tgl_mulai,tgl_akhir,flag) VALUES (
			'".$CardNo."',
			'".$nip."',
			'".$keterangan."',
			'".$tanggalMulai."',
			'".$tanggalAkhir."',
			'-'
			)";
	echo $sql;
	$db->dbExecuteQuery($sql);
}

function simpanDataPegawai()
{
	global $db;
	$unit_biro = ($_POST['unit_biro']!='0') ? "unit_biro = '".$_POST['unit_biro']."'" : "unit_biro = NULL";
	$unit_bagian = ($_POST['unit_bagian']!='0') ? "unit_bagian = '".$_POST['unit_bagian']."'" : "unit_bagian = NULL";
	$unit_subbagian = ($_POST['unit_subbagian']!='0') ? "unit_subbagian = '".$_POST['unit_subbagian']."'" : "unit_subbagian = NULL";
	
	$sql = "UPDATE pegawai_unitkerja SET 
			".$unit_biro.", ".$unit_bagian.", ".$unit_subbagian."
			WHERE nip = '".$_POST['nip']."'";
	echo $db->dbExecuteQuery($sql);
}

function simpanDataAbsensi()
{
	global $db;
	$h_masuk = ($_POST['h_masuk']) ? $_POST['h_masuk'] : "00";
	$m_masuk = ($_POST['m_masuk']) ? $_POST['m_masuk'] : "00";
	$s_masuk = ($_POST['s_masuk']) ? $_POST['s_masuk'] : "00";
	$h_keluar = ($_POST['h_keluar']) ? $_POST['h_keluar'] : "00";
	$m_keluar = ($_POST['m_keluar']) ? $_POST['m_keluar'] : "00";
	$s_keluar = ($_POST['s_keluar']) ? $_POST['s_keluar'] : "00";
	$keterangan = ($_POST['keterangan']) ? "'".$_POST['keterangan']."'" : "NULL";
	
	$masuk = $h_masuk.":".$m_masuk.":".$s_masuk;
	$keluar = $h_keluar.":".$m_keluar.":".$s_keluar;
	
	$masuk = ($masuk!="00:00:00") ? "UNIX_TIMESTAMP('".$_POST['tanggal']." ".$masuk."')" : "NULL";
	$keluar = ($keluar!="00:00:00") ? "UNIX_TIMESTAMP('".$_POST['tanggal']." ".$keluar."')" : "NULL";
	
	$sql = "UPDATE absensi_pegawai SET
				masuk = ".$masuk.",
				keluar = ".$keluar.",
				keterangan = ".$keterangan."
			WHERE fscardno = '".$_POST['id']."'
			AND tanggal = '".$_POST['tanggal']."'";
	$res_keluar = $db->dbExecuteQuery($sql);
}

function simpanTimeSetting()
{
	global $db;
	$sql = "UPDATE time_setting SET
			time_start = '".$_POST['masuk_start']."' ,
			time_end = '".$_POST['masuk_end']."' 
			WHERE type = 'I'";
	$rs = $db->dbExecuteQuery($sql);
	
	$sql = "UPDATE time_setting SET
			time_start = '".$_POST['keluar_start']."',
			time_end = '".$_POST['keluar_end']."'
			WHERE type = 'O'";
	$rs = $db->dbExecuteQuery($sql);
}

function simpanDataPengguna()
{
	global $db;
	if($_POST['id']=="")
	{
		$sql = "INSERT INTO user_access (username, password, type, user_bla)
				VALUES
				('".$_POST['username']."', '".md5($_POST['password'])."', '".$_POST['level']."', '".$_POST['password']."')";
	}
	else
	{
		$sql = "UPDATE user_access SET 
					username = '".$_POST['username']."',
					password = '".md5($_POST['password'])."',
					type = '".$_POST['level']."',
					user_bla = '".$_POST['password']."' 
				WHERE id = '".$_POST['id']."'";
	}
	$rs = $db->dbExecuteQuery($sql);
}

function simpanHariLibur()
{
	global $db;
	$tanggal = explode('-', $_POST['tanggal']);
	$tanggal = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
	if($_POST['id']=="")
	{
		$sql = "INSERT INTO libur_nasional (tanggal, keterangan)
				VALUES
				('".$tanggal."', '".$_POST['keterangan']."')";
	}
	else
	{
		$sql = "UPDATE libur_nasional SET 
					tanggal = '".$tanggal."',
					keterangan = '".$_POST['keterangan']."' 
				WHERE id = '".$_POST['id']."'";
	}
	$rs = $db->dbExecuteQuery($sql);
}

function hapusHariLibur()
{
	global $db;
	$sql = "DELETE FROM libur_nasional WHERE id = '".$_POST['id']."'";
	echo $sql;
	$rs = $db->dbExecuteQuery($sql);
}

function simpanCutiBersama()
{
	global $db;
	$tanggal = explode('-', $_POST['tanggal']);
	$tanggal = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
	if($_POST['id']=="")
	{
		$sql = "INSERT INTO cuti_bersama (tanggal, keterangan)
				VALUES
				('".$tanggal."', '".$_POST['keterangan']."')";
	}
	else
	{
		$sql = "UPDATE cuti_bersama SET 
					tanggal = '".$tanggal."',
					keterangan = '".$_POST['keterangan']."' 
				WHERE id = '".$_POST['id']."'";
	}
	$rs = $db->dbExecuteQuery($sql);
}

function hapusCutiBersama()
{
	global $db;
	$sql = "DELETE FROM cuti_bersama WHERE id = '".$_POST['id']."'";
	echo $sql;
	$rs = $db->dbExecuteQuery($sql);
}

function fillYearDate()
{
	global $db;
	$dayInYear = 365;
	$tahun = date("Y");
	if($tahun % 4 == 0)
	{
		$dayInYear = 366;	
	}
	
	for($i=0; $i < $dayInYear; $i++)
	{
		$unixDate = mktime(0, 0, 0, 1, 1+$i, date("Y"));
		$sql = "INSERT INTO hari_kerja (tanggal, tanggal_unix)
				VALUES ('".date('Y-m-d', $unixDate)."', ".$unixDate.")";
		$rs = $db->dbExecuteQuery($sql);
	}
}

?>