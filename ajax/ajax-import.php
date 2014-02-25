<?php
require_once("../class/database.class.php");
$db = new database;

$day = (date("l")=="Monday") ? 3 : 1;
$now = date('m/d/Y');
$now2 = date('Y-m-d');
$yesterday = date('m/d/Y', mktime(0, 0, 0, date('m'), date('d')-$day, date('Y')));
$yesterday2 = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-$day, date('Y')));
$yesterdayUnix = mktime(0, 0, 0, date('m'), date('d')-$day, date('Y'));

$conn = odbc_connect("absensi_djkn", "admin", "");
$sqlMasuk = "SELECT userid, FORMAT(CHECKTIME, 'mm/dd/yyyy') as tanggal, MIN(FORMAT(CHECKTIME, 'hh:mm:ss')) as waktu, MIN(CHECKTIME)
				FROM checkinout
				WHERE FORMAT(CHECKTIME, 'mm/dd/yyyy') ='".$now."'
				AND (checktype = 'I'
				OR CHECKTIME > #".$now." 05:59:00 AM#)
				GROUP BY userid, FORMAT(CHECKTIME, 'mm/dd/yyyy') ";
$resultMasuk = odbc_exec($conn, $sqlMasuk);
$accessDataMasuk = array();
while($data = odbc_fetch_array($resultMasuk))
{
	$accessDataMasuk[] = $data;
}

$sqlKeluar = "SELECT userid, FORMAT(CHECKTIME, 'mm/dd/yyyy') as tanggal, MAX(FORMAT(CHECKTIME, 'hh:mm:ss')) as waktu, MAX(CHECKTIME)
				FROM checkinout
				WHERE FORMAT(CHECKTIME, 'mm/dd/yyyy') ='".$yesterday."'
				AND (checktype = 'O'
				OR CHECKTIME < #".$yesterday." 07:00:00 PM#)
				GROUP BY userid, FORMAT(CHECKTIME, 'mm/dd/yyyy')";
$resultKeluar = odbc_exec($conn, $sqlKeluar);
$accessDataKeluar = array();
while($data = odbc_fetch_array($resultKeluar))
{
	$accessDataKeluar[] = $data;
}
$rowData = array();

$sql = "SELECT DISTINCT(userid) as userid FROM pegawai_registry ";
$dataPegawai = $db->dbFetchArray($sql);
foreach ($dataPegawai as $val)
{
	$checkin = 'NULL';
	foreach ($accessDataMasuk as $masuk)
	{
		if ( $val['userid'] == $masuk['userid'] )
		{
			$checkin = $masuk['waktu'];
			list($y, $m, $d) = explode("-", $now2);
			list($h, $i, $s) = explode(":", $masuk['waktu']);
			$checkin = mktime($h, $i, $s, $m, $d, $y);
		}
	}
	
	$sqlCekKet = "SELECT id,fscardno, tgl_mulai ,	tgl_akhir, keterangan
					FROM range_cuti
					WHERE fscardno  = '".$val['userid']."'
					AND  tgl_mulai<='".$now2."' AND tgl_akhir>='".$now2."'
					ORDER BY ID DESC LIMIT 1";
	$totalRow = $db->dbCountRow($sqlCekKet);
	$keteranganCek="";
	if($totalRow > 0){
		$cekKet = $db->dbFetchArray($sqlCekKet);
		$keteranganCek = $cekKet[0]['keterangan'];
		$idCek = $cekKet[0]['id'];
	}
	
	$sql = "SELECT * FROM absensi_pegawai WHERE fscardno = '".$val['userid']."' AND tanggal = '".$now2."' ";
	$rs = $db->dbFetchArray($sql);
	if($rs=="null")
	{
		$result = $db->dbFetchArray($sql);
		$masuk = ($result[0]['masuk']!="") ? explode(":", $result[0]['masuk']) : array("", "", "");
		$sql = "INSERT INTO absensi_pegawai VALUES 
			('".$val['userid']."', '".$now2."', ".$checkin.", NULL, '".$keteranganCek."')";
			
	}
	else
	{
		$sql = "UPDATE absensi_pegawai SET
				masuk = ".$checkin.",keterangan='".$keteranganCek."'
				WHERE fscardno = '".$val['userid']."' AND tanggal = '".$now2."' ";
	}
	$result = $db->dbExecuteQuery($sql);
	$sqlUpdateId = "UPDATE  range_cuti SET flag='T'
					WHERE fscardno  = '".$val['userid']."'
					AND id=".$idCek;
	$sqlUpdateId = $db->dbFetchArray($sqlUpdateId);

}

foreach ($dataPegawai as $val)
{
	$checkout = 'NULL';
	foreach ($accessDataKeluar as $keluar)
	{
		if ( $val['userid'] == $keluar['userid'] )
		{
			list($y, $m, $d) = explode("-", $yesterday2);
			list($h, $i, $s) = explode(":", $keluar['waktu']);
			$checkout = mktime($h, $i, $s, $m, $d, $y);
		}
	}
	$sql = "UPDATE absensi_pegawai SET
				keluar = ".$checkout."
			WHERE fscardno = '".$val['userid']."'
			AND tanggal = '".$yesterday2."'";
	$result = $db->dbExecuteQuery($sql);
	
	importCutiBersama($yesterday2, $dataPegawai);
}

function importCutiBersama($tanggal, $dataPegawai)
{
	global $db;
	$sql = "SELECT * FROM cuti_bersama WHERE tanggal = '".$tanggal."' ";
	$result = $db->dbFetchArray($sql);
	
	if($result != "null")
	{
		foreach ($dataPegawai as $pegawai)
		{
			$sql = "INSERT INTO absensi_pegawai VALUES 
				('".$pegawai['userid']."', '".$tanggal."', NULL, NULL, 'CT')";
			$result = $db->dbExecuteQuery($sql);
		}
	}
}
?>