<?php
session_start();
require_once("../class/database.class.php");
$db = new database;
switch ($_GET['f'])
{
	case "getPegawai":
		getPegawai();
		break;
	
	case "getPengguna":
		getPengguna();
		break;
	
	case "getHariLibur":
		getHariLibur();
		break;
	
	case "getAbsensi":
		getAbsensi();
		break;
		
	case "getTKPKN":
		getTKPKN();
		break;
	
	case "getBagian":
		getBagian();
		break;
		
	case "getPegawaiList":
		getPegawaiList();
		break;
	
	case "laporan_harian":
		laporan_harian();
		break;
		
	case "laporan_absensi":
		laporan_absensi();
		break;
	
	case "laporan_ketidakhadiran":
		laporan_ketidakhadiran();
		break;
		
	case "laporan_uangmakan":
		laporan_uangmakan();
		break;
		
	case "laporan_pegawai":
		laporan_pegawai();
		break;
		
	case "login":
		login();
		break;
	
	case "logout":
		logout();
		break;
	
	case "getDataAbsensi":
		getDataAbsensi();
		break;
		
	case "getDataPegawai":
		getDataPegawai();
		break;
	
	case "getDataPengguna":
		getDataPengguna();
		break;
	
	case "getDataHariLibur":
		getDataHariLibur();
		break;
	
	case "getBagianCombo":
		getBagianCombo();
		break;
		
	case "getSubBagianCombo":
		getSubBagianCombo();
		break;
}

function getPegawai()
{
	global $db;
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	
	$sortname = (!$_POST['query']) ? 'nama' : $_POST['qtype'];
	if (!$sortorder) $sortorder = 'asc';
	
	
	if (!$page) $page = 1;
	if (!$rp) $rp = 10;
	
	$start = (($page-1) * $rp);
	
	$limit = "LIMIT $start, $rp";
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	
	$where = "";
	if ($query) $where = " WHERE $qtype LIKE '%$query%' ";
	$sort = "ORDER BY $sortname $sortorder";
	
	$sql = "SELECT a.nip, a.nama, c.nama_id
			FROM person a
			JOIN kepegawaian_unitkerja b ON a.nip = b.nip
			JOIN unit_organisasi c ON c.ID = (CASE WHEN b.unit_subbagian IS NOT NULL THEN b.unit_subbagian
											 WHEN b.unit_bagian IS NOT NULL THEN b.unit_bagian
											 WHEN b.unit_biro IS NOT NULL THEN b.unit_biro END) ".$where;
	$sql = "SELECT a.nip, nama, 
				   b.unit_biro,
				   b.unit_bagian,
				   b.unit_subbagian,
				   (CASE WHEN e.v_uraian IS NOT NULL THEN e.v_uraian
				   WHEN d.v_uraian IS NOT NULL THEN d.v_uraian
				   ELSE c.v_uraian END ) as nama_id
			FROM pegawai a
			LEFT JOIN pegawai_unitkerja b ON a.nip = b.nip
			LEFT JOIN t_par_instansi c ON b.unit_biro = c.c_kode
			LEFT JOIN t_par_unitkerja d ON b.unit_bagian = d.c_kode
			LEFT JOIN t_par_subunitkerja e ON b.unit_subbagian = e.c_kode
			".$where." 
			ORDER BY b.unit_subbagian DESC, nama ASC";
	
	$total = $db->dbCountRow($sql);
	$totalPage = ceil($total/$rp);
	$prev = ($page==1) ? 1 : $page-1;
	$next = ($page==$totalPage) ? $totalPage : $page+1;
	$sql .= " ".$limit;
	$result = $db->dbFetchArray($sql);
	$output = "";
	$rowNumber = ($page==1) ? 1 : $start+1;
	if($result=="null")
	{
		die("Data tidak ada.");	
	}
	foreach($result as $row)
	{
		$rowClass = ($rowNumber%2==0) ? "rowEven" : "";
		$output .= '<tr align="left" class="'.$rowClass.'" id="row_'.$rowNumber.'" onclick="selectRow(\'row_'.$rowNumber.'\', \''.$row['nip'].'\')">
						<td class="gridrow">'.$rowNumber.'</td>
						<td class="gridrow">'.$row['nip'].'</td>
						<td class="gridrow">'.$row['nama'].'</td>
						<td class="gridrow">'.$row['nama_id'].'</td>
					</tr>';
		$rowNumber++;
	}
	
	$output .= '<tr>
					<td colspan="4" class="gridfooter">
						<div class="button goFirst" onclick="goToPage(1)">&nbsp;</div>
						<div class="button goPrev" onclick="goToPage('.$prev.')">&nbsp;</div>
						<div class="pageInfo">Halaman '.$page.' dari total '.$totalPage.' halaman</div>
						<div class="button goNext" onclick="goToPage('.$next.')">&nbsp;</div>
						<div class="button goLast" onclick="goToPage('.$totalPage.')"">&nbsp;</div>
					</td>
				</tr>';
	echo $output;
}

function getPengguna()
{
	global $db;
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	
	$sortname = (!$_POST['query']) ? 'nama' : $_POST['qtype'];
	if (!$sortorder) $sortorder = 'asc';
	
	
	if (!$page) $page = 1;
	if (!$rp) $rp = 10;
	
	$start = (($page-1) * $rp);
	
	$limit = "LIMIT $start, $rp";
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	
	$where = "";
	if ($query) $where = " WHERE $qtype LIKE '%$query%' ";
	$sort = "ORDER BY $sortname $sortorder";
	
	$sql = "SELECT * FROM user_access ORDER BY username";
	
	$total = $db->dbCountRow($sql);
	$totalPage = ceil($total/$rp);
	$prev = ($page==1) ? 1 : $page-1;
	$next = ($page==$totalPage) ? $totalPage : $page+1;
	$sql .= " ".$limit;
	$result = $db->dbFetchArray($sql);
	$output = "";
	$rowNumber = ($page==1) ? 1 : $start+1;
	if($result=="null")
	{
		die("Data tidak ada.");	
	}
	foreach($result as $row)
	{
		$rowClass = ($rowNumber%2==0) ? "rowEven" : "";
		$output .= '<tr align="left" class="'.$rowClass.'" id="row_'.$rowNumber.'" onclick="selectRow(\'row_'.$rowNumber.'\', \''.$row['id'].'\')">
						<td class="gridrow">'.$rowNumber.'</td>
						<td class="gridrow">'.$row['username'].'</td>
						<td class="gridrow">'.$row['type'].'</td>
					</tr>';
		$rowNumber++;
	}
	
	$output .= '<tr>
					<td colspan="3" class="gridfooter">
						<div class="button goFirst" onclick="goToPage(1)">&nbsp;</div>
						<div class="button goPrev" onclick="goToPage('.$prev.')">&nbsp;</div>
						<div class="pageInfo">Halaman '.$page.' dari total '.$totalPage.' halaman</div>
						<div class="button goNext" onclick="goToPage('.$next.')">&nbsp;</div>
						<div class="button goLast" onclick="goToPage('.$totalPage.')"">&nbsp;</div>
					</td>
				</tr>';
	echo $output;
}

function getHariLibur()
{
	global $db;
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	
	$sortname = (!$_POST['query']) ? 'nama' : $_POST['qtype'];
	if (!$sortorder) $sortorder = 'asc';
	
	
	if (!$page) $page = 1;
	if (!$rp) $rp = 10;
	
	$start = (($page-1) * $rp);
	
	$limit = "LIMIT $start, $rp";
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	
	$where = "";
	if ($query) $where = " WHERE $qtype LIKE '%$query%' ";
	$sort = "ORDER BY $sortname $sortorder";
	
	$sql = "SELECT id, DATE_FORMAT(tanggal, '%d-%m-%Y') as tanggal, keterangan FROM libur_nasional ORDER BY tanggal";
	
	$total = $db->dbCountRow($sql);
	$totalPage = ceil($total/$rp);
	$prev = ($page==1) ? 1 : $page-1;
	$next = ($page==$totalPage) ? $totalPage : $page+1;
	$sql .= " ".$limit;
	$result = $db->dbFetchArray($sql);
	$output = "";
	$rowNumber = ($page==1) ? 1 : $start+1;
	if($result=="null")
	{
		die();	
	}
	foreach($result as $row)
	{
		$rowClass = ($rowNumber%2==0) ? "rowEven" : "";
		$output .= '<tr align="left" class="'.$rowClass.'" id="row_'.$rowNumber.'" onclick="selectRow(\'row_'.$rowNumber.'\', \''.$row['id'].'\')">
						<td class="gridrow">'.$rowNumber.'</td>
						<td class="gridrow">'.$row['tanggal'].'</td>
						<td class="gridrow">'.$row['keterangan'].'</td>
					</tr>';
		$rowNumber++;
	}
	
	$output .= '<tr>
					<td colspan="3" class="gridfooter">
						<div class="button goFirst" onclick="goToPage(1)">&nbsp;</div>
						<div class="button goPrev" onclick="goToPage('.$prev.')">&nbsp;</div>
						<div class="pageInfo">Halaman '.$page.' dari total '.$totalPage.' halaman</div>
						<div class="button goNext" onclick="goToPage('.$next.')">&nbsp;</div>
						<div class="button goLast" onclick="goToPage('.$totalPage.')"">&nbsp;</div>
					</td>
				</tr>';
	echo $output;
}

function getAbsensi()
{
	global $db;
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	
	if (!$page) $page = 1;
	if (!$rp) $rp = 10;
	
	$start = (($page-1) * $rp);
	
	$limit = "LIMIT $start, $rp";
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	$tanggal = ($_POST['tanggal'] == 0) ? date('d-m-Y', mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))) : $_POST['tanggal'];
	$where = "";
	if ($query) $where = " AND $qtype LIKE '%$query%' ";
	
	$sql = "SELECT c.fscardno, b.userid, a.nip, a.nama, 
				   (CASE WHEN g.v_uraian IS NOT NULL THEN g.v_uraian
				   WHEN f.v_uraian IS NOT NULL THEN f.v_uraian
				   ELSE e.v_uraian END ) as nama_id,
				   DATE_FORMAT(FROM_UNIXTIME(c.masuk), '%H:%i:%s') as masuk, 
       				DATE_FORMAT(FROM_UNIXTIME(c.keluar), '%H:%i:%s') as keluar, 
				   c.keterangan
			FROM pegawai a
			JOIN pegawai_registry b ON a.nip = b.nip
			JOIN absensi_pegawai c ON b.userid = c.fscardno
			LEFT JOIN pegawai_unitkerja d ON a.nip = d.nip
			LEFT JOIN t_par_instansi e ON d.unit_biro = e.c_kode
			LEFT JOIN t_par_unitkerja f ON d.unit_bagian = f.c_kode
			LEFT JOIN t_par_subunitkerja g ON d.unit_subbagian = g.c_kode
			WHERE DATE_FORMAT(c.tanggal, '%d-%m-%Y') = '".$tanggal."' ".$where." 
			ORDER BY d.unit_biro DESC, d.unit_bagian DESC, d.unit_subbagian DESC, nama ASC";

	$total = $db->dbCountRow($sql);
	if($total==0) die();
	$totalPage = ceil($total/$rp);
	$prev = ($page==1) ? 1 : $page-1;
	$next = ($page==$totalPage) ? $totalPage : $page+1;
	$sql .= " ".$limit;
	$result = $db->dbFetchArray($sql);
	$output = "";
	$rowNumber = ($page==1) ? 1 : $start+1;
	if($result=="null")
	{
		die("Data tidak ada.");	
	}
	foreach($result as $row)
	{
		$rowClass = ($rowNumber%2==0) ? "rowEven" : "";
		$output .= '<tr align="left" class="'.$rowClass.'" id="row_'.$rowNumber.'" onclick="selectRow(\'row_'.$rowNumber.'\', \''.$row['fscardno'].'\')">
						<td class="gridrow">'.$rowNumber.'</td>
						<td class="gridrow">'.$row['nip'].'</td>
						<td class="gridrow">'.$row['nama'].'</td>
						<td class="gridrow">'.$row['nama_id'].'</td>
						<td class="gridrow">'.$row['masuk'].'</td>
						<td class="gridrow">'.$row['keluar'].'</td>
						<td class="gridrow">'.$row['keterangan'].'</td>
					</tr>';
		$rowNumber++;
	}
	
	$output .= '<tr>
					<td colspan="7" class="gridfooter">
						<div class="button goFirst" onclick="goToPage(1)">&nbsp;</div>
						<div class="button goPrev" onclick="goToPage('.$prev.')">&nbsp;</div>
						<div class="pageInfo">Halaman '.$page.' dari total '.$totalPage.' halaman</div>
						<div class="button goNext" onclick="goToPage('.$next.')">&nbsp;</div>
						<div class="button goLast" onclick="goToPage('.$totalPage.')"">&nbsp;</div>
					</td>
				</tr>';
	echo $output;
}

function getTKPKN()
{
	global $db;
	$time_i = $_SESSION['dataMasuk']['to'];
	$time_o = $_SESSION['dataKeluar']['from'];
	$page = $_POST['page'];
	$rp = $_POST['rp'];
	$sortname = $_POST['sortname'];
	$sortorder = $_POST['sortorder'];
	
	if (!$sortname) $sortname = 'nama';
	if (!$sortorder) $sortorder = 'asc';
	
	$sort = "ORDER BY $sortname $sortorder";
	
	if (!$page) $page = 1;
	if (!$rp) $rp = 10;
	
	$start = (($page-1) * $rp);
	
	$limit = "LIMIT $start, $rp";
	
	$query = $_POST['query'];
	$qtype = $_POST['qtype'];
	$tanggal = ($_POST['tanggal'] == 0) ? date('d-m-Y', mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))) : $_POST['tanggal'];
	$tanggal = explode("-", $tanggal);
	$tanggal = $tanggal[2]."-".$tanggal[1]."-".$tanggal[0];
	
	$where = "";
	if ($query) $where = " AND $qtype LIKE '%$query%' ";
	
	$sql = "SELECT a.tanggal, c.nip, c.nama, 
				   DATE_FORMAT(FROM_UNIXTIME(a.masuk), '%H:%i:%s') as masuk, 
				   DATE_FORMAT(FROM_UNIXTIME(a.keluar), '%H:%i:%s') as keluar, 
				   a.keterangan,
				   TIMESTAMPDIFF(MINUTE, '".$tanggal." ".$time_i."', FROM_UNIXTIME(a.masuk)) as masuk_diff,
				   TIMESTAMPDIFF(MINUTE, FROM_UNIXTIME(a.keluar), '".$tanggal." ".$time_o."') as keluar_diff,
				   (CASE WHEN g.v_uraian IS NOT NULL THEN g.v_uraian
				   WHEN f.v_uraian IS NOT NULL THEN f.v_uraian
				   ELSE e.v_uraian END ) as nama_id
			FROM absensi_pegawai a
			JOIN pegawai_registry b ON a.fscardno = b.userid
			JOIN pegawai c ON b.nip = c.nip
			LEFT JOIN pegawai_unitkerja d ON c.nip = d.nip
			LEFT JOIN t_par_instansi e ON d.unit_biro = e.c_kode
			LEFT JOIN t_par_unitkerja f ON d.unit_bagian = f.c_kode
			LEFT JOIN t_par_subunitkerja g ON d.unit_subbagian = g.c_kode
			WHERE a.tanggal = '".$tanggal."' ".$where." 
			ORDER BY d.unit_biro DESC, d.unit_bagian DESC, d.unit_subbagian DESC, nama ASC";
	$total = $db->dbCountRow($sql);
	if($total==0) die();
	$totalPage = ceil($total/$rp);
	$prev = ($page==1) ? 1 : $page-1;
	$next = ($page==$totalPage) ? $totalPage : $page+1;
	$sql .= " ".$limit;
	$result = $db->dbFetchArray($sql);
	$output = "";
	$rowNumber = ($page==1) ? 1 : $start+1;
	if($result=="null")
	{
		die("Data tidak ada.");	
	}
	foreach($result as $row)
	{
		$rowClass = ($rowNumber%2==0) ? "rowEven" : "";
		if($row['masuk_diff']!="" && $row['keluar_diff']!="")
		{
			$tl = hitungPotongan($row['masuk_diff'], $row['keterangan'], 'TL');
			$psw = hitungPotongan($row['keluar_diff'], $row['keterangan'], 'PSW');
			$type = $tl['type'].' '.$psw['type'];
		}
		else
		{
			$tl['num'] = ($row['masuk_diff']=="" && $row['keterangan']=="") ? 2.5 : 0;
			$psw['num'] = ($row['keluar_diff']=="" && $row['keterangan']=="") ? 2.5 : 0;
			$type = ($row['keterangan']=="") ? "Tidak hadir" : $row['keterangan'];
		}
		
		$tkpkn = $tl['num']+$psw['num'];
		
		$output .= '<tr align="left" class="'.$rowClass.'" id="row_'.$rowNumber.'" onclick="selectRow(\'row_'.$rowNumber.'\', \''.$row['fscardno'].'\')">
						<td class="gridrow">'.$rowNumber.'</td>
						<td class="gridrow">'.$row['nip'].'</td>
						<td class="gridrow">'.$row['nama'].'</td>
						<td class="gridrow">'.$row['nama_id'].'</td>
						<td class="gridrow">'.$tkpkn.'</td>
						<td class="gridrow">'.$type.'</td>
					</tr>';
		$rowNumber++;
	}
	
	$output .= '<tr>
					<td colspan="7" class="gridfooter">
						<div class="button goFirst" onclick="goToPage(1)">&nbsp;</div>
						<div class="button goPrev" onclick="goToPage('.$prev.')">&nbsp;</div>
						<div class="pageInfo">Halaman '.$page.' dari total '.$totalPage.' halaman</div>
						<div class="button goNext" onclick="goToPage('.$next.')">&nbsp;</div>
						<div class="button goLast" onclick="goToPage('.$totalPage.')"">&nbsp;</div>
					</td>
				</tr>';
	echo $output;
}

function hitungPotongan($minuteDiff, $keterangan, $type)
{
	$tkpkn['num'] = 0;
	if($keterangan=="")
	{
		if($minuteDiff>1 && $minuteDiff<=30)
		{
			$tkpkn['num'] = $tkpkn['num'] + 0.5;
			$tkpkn['type'] = $type.".1";
		}
		elseif($minuteDiff>31 && $minuteDiff<=60)
		{
			$tkpkn['num'] = $tkpkn['num'] + 1;
			$tkpkn['type'] = $type.".2";
		}
		elseif($minuteDiff>61 && $minuteDiff<=90)
		{
			$tkpkn['num'] = $tkpkn['num'] + 1.25;
			$tkpkn['type'] = $type.".3";
		}
		elseif($minuteDiff>91)
		{
			$tkpkn['num'] = $tkpkn['num'] + 2.5;
			$tkpkn['type'] = $type.".4";
		}
	}
	else
	{
		$tkpkn['type'] = $keterangan;
	}
	
	return $tkpkn;
}

function laporan_harian()
{
	global $db;
	extract($_GET);
	$tglArr = explode('-', $tgl_mulai);
	$tgl_mulai = $tglArr[2]."-".$tglArr[1]."-".$tglArr[0];
	$tglArr = explode('-', $tgl_selesai);
	$tgl_selesai = $tglArr[2]."-".$tglArr[1]."-".$tglArr[0];
	
	$sqlPegawai = "SELECT a.fsidno, b.nama, d.nama_id, CONCAT(b.nama,'<br>', b.nip) as nama_nip
					FROM temployees a
					JOIN person b ON a.fsidno = b.nip
					JOIN kepegawaian_unitkerja c ON b.nip = c.nip
					JOIN unit_organisasi d ON d.id = (CASE WHEN c.unit_bagian IS NOT NULL THEN c.unit_bagian
													 WHEN c.unit_biro IS NOT NULL THEN c.unit_biro END)
					WHERE d.id = '".$unit_bagian."'
					ORDER BY b.nama";
	//$totalPegawai = $db->dbCountRow($sqlPegawai);
	$dataPegawaix = $db->dbFetchArray($sqlPegawai);
	$totalPegawai = count($dataPegawaix);
	$rowperpage = 25;
	$totalPage = ceil($totalPegawai/$rowperpage);
	
	$sqlPeriode = "SELECT DATE_FORMAT(tanggal, '%d-%m-%Y') as tanggal, DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal2
				  FROM hari_kerja
				  WHERE UNIX_TIMESTAMP(tanggal) BETWEEN UNIX_TIMESTAMP('".$tgl_mulai."') AND UNIX_TIMESTAMP('".$tgl_selesai."')
				  GROUP BY tanggal";
	$dataPeriode = $db->dbFetchArray($sqlPeriode);
	
	$output = "";
	$output .= "<link href='../asset/css/report.css' rel='stylesheet'>";
	$num = 1;
	for ($page=1; $page<($totalPage+1); $page++)
	{
		$offset = ($page==1) ? 0 : ($rowperpage*($page-1));
		$limit = ($page==1) ? $rowperpage : ($rowperpage*$page);
		$queryLimit = " limit ".$offset.", ".$rowperpage;
		
		$dataPegawai = $db->dbFetchArray($sqlPegawai.$queryLimit);
		
		$output .= "<div class='report-body' style='width:800px;'>";
		$output .= "<h3>BAPEPAMLK</h3>";
		$output .= "<h3>DAFTAR ABSENSI HARIAN PEGAWAI</h3>";
		$output .= "<h4>
						<div class='label'>Unit Kerja</div>
						<div class='value'>: ".$dataPegawai[0]['nama_id']."</div>
					</h4>";
		$output .= "<h4>
						<div class='label'>Periode</div>
						<div class='value'>: ".$_GET['tgl_mulai']." s/d ".$_GET['tgl_selesai']."</div>
					</h4>";

		$output .= "<table cellpadding='0' cellspacing='-1' width='800px' align='left'>";
		$output .= "<tr class='gridheader'>";
		$output .= "<td rowspan='2' width='10px'>No.</td>";
		$output .= "<td rowspan='2'>Nama / NIP</td>";
		if ($dataPeriode!="null")
		{
			foreach ( $dataPeriode as $periode )
			{
				$thisDay = date("D", getUnixformat($periode['tanggal']));
				if ( $thisDay != "Sun" && $thisDay != "Sat" )
				{
					$output .= "<td colspan='2'>".dateFormat($periode['tanggal'])."</td>";
				}
			}
		}
		
		$output .= "</tr>";
		$output .= "<tr>";
		if ($dataPeriode!="null")
		{
			foreach ( $dataPeriode as $periode )
			{
				$output .= "<td align='center'>Masuk</td>";
				$output .= "<td align='center'>Pulang</td>";
			}
		}
		$output .= "</tr>";
		
		
		foreach ( $dataPegawai as $pegawai )
		{
			$class = ($num%2==0)? "even" : "odd";
			$output .= "<tr>";
			$output .= "<td class='".$class."' width='10px'>".$num."</td>";
			$output .= "<td class='".$class."' width='100px'>".$pegawai['nama_nip']."</td>";
			if ($dataPeriode!="null")
			{
				foreach ( $dataPeriode as $periode )
				{
					/*
					$sqlMasuk = "SELECT DATE_FORMAT(fttime, '%d-%m-%Y') as tanggal, DATE_FORMAT(fttime, '%H:%i') as absen
								FROM tactivities
								WHERE UNIX_TIMESTAMP(DATE_FORMAT(fttime, '%Y-%m-%d')) = UNIX_TIMESTAMP('".$periode['tanggal2']."')
								AND fcdirflag = '1'
								AND fscardno = '".$pegawai['fscardno']."'
								GROUP BY tanggal";
					$dataMasuk = $db->dbFetchArray($sqlMasuk);
					$masuk = ($dataMasuk!="null") ? $dataMasuk[0]['absen'] : "&nbsp;";
					$sqlPulang = "SELECT DATE_FORMAT(fttime, '%d-%m-%Y') as tanggal, DATE_FORMAT(fttime, '%H:%i') as absen
								FROM tactivities
								WHERE UNIX_TIMESTAMP(DATE_FORMAT(fttime, '%Y-%m-%d')) = UNIX_TIMESTAMP('".$periode['tanggal2']."')
								AND fcdirflag = '0'
								AND fscardno = '".$pegawai['fscardno']."'
								GROUP BY tanggal";
					$dataPulang = $db->dbFetchArray($sqlPulang);
					$pulang = ($dataPulang!="null") ? $dataPulang[0]['absen'] : "&nbsp;";
					*/
					$output .= "<td class='".$class."' align='center' width='50px'>".$masuk."</td>";
					$output .= "<td class='".$class."' align='center' width='50px'>".$pulang."</td>";
				}
			}
			$output .= "</tr>";
			$num++;
		}
		
		$output .= "</table>";
		$output .= "</div>";
		$output .= '<div class="footer">
						<div class="date">'.date('d/m/Y').'</div>
						<div class="page">Hal: '.$page.' dari total '.$totalPage.'</div>
					</div>';
		$output .= '<div style="page-break-after:always;">.</div>';
	}
	//echo $output;
	renderPDF($output, "landscape", "Laporan Absensi Harian");
}

function renderPDF($output, $orientation="landscape", $filename="dokumen")
{
	require_once("../class/dompdf/dompdf_config.inc.php");
	$dompdf = new DOMPDF();
	if ( get_magic_quotes_gpc() )
		$output = stripslashes($output);
	$dompdf->set_paper("A4", $orientation);
	$dompdf->load_html($output);
	$dompdf->render();
	$dompdf->stream($filename.".pdf", array("Attachment"=>0));
}

function getUnixformat($date)
{
	$dateArr = explode("-", $date);
	$unix = mktime(0, 0, 0, $dateArr[1], $dateArr[0], $dateArr[2]);
	return $unix;
}

function dateFormat($date)
{
	$unix = getUnixformat($date);
	$day = date("D", $unix);
	$days = array(	"Sun"=>"Minggu", 
					"Mon"=>"Senin",
					"Tue"=>"Selasa",
					"Wed"=>"Rabu",
					"Thu"=>"Kamis",
					"Fri"=>"Jumat",
					"Sat"=>"Sabtu");
	return $days[$day]." Tgl. ".str_replace("-", "/", $date);
}

function getBagian()
{
	global $db;
	$sql = "SELECT a.unit_bagian, b.v_uraian as nama_id
			FROM pegawai_unitkerja a
			JOIN t_par_unitkerja b ON a.unit_bagian = b.c_kode
			WHERE a.unit_biro = '".$_GET['biro']."'
			GROUP BY unit_bagian";
	$bagianArr = $db->dbFetchArray($sql);
	$option = "";
	foreach ( $bagianArr as $bagian )
	{
		$option .= "<option value='".$bagian['unit_bagian']."'>".$bagian['nama_id']."</option>";
	}
	
	echo $option;
}

function getPegawaiList()
{
	global $db;
	$sql = "SELECT b.nip, b.nama
			FROM pegawai_unitkerja a
			JOIN pegawai b ON a.nip = b.nip
			WHERE a.unit_bagian = '".$_GET['bagian']."'
			GROUP BY nama";
	$pegawaiArr = $db->dbFetchArray($sql);
	$option = "";
	foreach ( $pegawaiArr as $pegawai )
	{
		$option .= "<option value='".$pegawai['nip']."'>".$pegawai['nama']." / ".$pegawai['nip']."</option>";
	}
	
	echo $option;
}

function laporan_ketidakhadiran()
{
	global $db;
	$tempdate = explode('-', $_GET['tgl_mulai']);
	$sdate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$tempdate = explode('-', $_GET['tgl_selesai']);
	$edate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$sql = "SELECT fsidno, fsname, COUNT(tanggal),
				   SUM(CASE WHEN masuk IS NULL AND keluar IS NULL AND keterangan IS NULL THEN 1 ELSE 0 END) as tidakhadir,
				   SUM(CASE WHEN masuk IS NOT NULL OR keluar IS NOT NULL AND keterangan IS NULL THEN 1 ELSE 0 END) as hadir,
				   SUM(CASE WHEN keterangan = 'DL' THEN 1 ELSE 0 END) as dl,
				   SUM(CASE WHEN keterangan = 'CT' THEN 1 ELSE 0 END) as ct,
				   SUM(CASE WHEN keterangan = 'TB' THEN 1 ELSE 0 END) as tb,
				   SUM(CASE WHEN keterangan = 'ST' THEN 1 ELSE 0 END) as st,
				   SUM(CASE WHEN keterangan = 'I' THEN 1 ELSE 0 END) as 'i',
				   SUM(CASE WHEN keterangan = 'CBL' THEN 1 ELSE 0 END) as cbl,
				   SUM(CASE WHEN keterangan = 'CB' THEN 1 ELSE 0 END) as cb,
				   SUM(CASE WHEN keterangan = 'CP' THEN 1 ELSE 0 END) as cp,
				   SUM(CASE WHEN keterangan = 'CP' THEN 1 ELSE 0 END) as cs,
				   SUM(CASE WHEN keterangan = 'SC' THEN 1 ELSE 0 END) as sc,
				   SUM(CASE WHEN keterangan = 'BT' THEN 1 ELSE 0 END) as bt,
				   golongan,
				   SUM(CASE WHEN masuk_diff < 1 THEN 0
				   		WHEN (masuk_diff > 1 AND masuk_diff < 30 
								AND keterangan NOT IN ('DL', 'CT', 'TB', 'ST', 'SC', 'I', 'CBL', 'CB', 'CP', 'CS', 'SC', 'BT')
								AND tanggal NOT IN (SELECT tanggal FROM libur_nasional) )
							OR keterangan = 'TL.1' THEN 0.25
				   		WHEN (masuk_diff > 31 AND masuk_diff
								AND keterangan NOT IN ('DL', 'CT', 'TB', 'ST', 'SC', 'I', 'CBL', 'CB', 'CP', 'CS', 'SC', 'BT')
								AND tanggal NOT IN (SELECT tanggal FROM libur_nasional) )
							OR keterangan = 'TL.2' < 60 THEN 0.5
						WHEN (masuk_diff > 61 AND masuk_diff < 90 
								AND keterangan NOT IN ('DL', 'CT', 'TB', 'ST', 'SC', 'I', 'CBL', 'CB', 'CP', 'CS', 'SC', 'BT')
								AND tanggal NOT IN (SELECT tanggal FROM libur_nasional) )
							OR keterangan = 'TL.3' THEN 1.25
						ELSE 2.5 END) as masuk,
					SUM(CASE WHEN keluar_diff < 1 THEN 0
				   		WHEN (keluar_diff > 1 AND keluar_diff < 30 
								AND keterangan NOT IN ('DL', 'CT', 'TB', 'ST', 'SC', 'I', 'CBL', 'CB', 'CP', 'CS', 'SC', 'BT')
								AND tanggal NOT IN (SELECT tanggal FROM libur_nasional) )
							OR keterangan = 'PSW.1' THEN 0.25
				   		WHEN (keluar_diff > 31 AND keluar_diff < 60 
								AND keterangan NOT IN ('DL', 'CT', 'TB', 'ST', 'SC', 'I', 'CBL', 'CB', 'CP', 'CS', 'SC', 'BT')
								AND tanggal NOT IN (SELECT tanggal FROM libur_nasional) )
							OR keterangan = 'PSW.2' THEN 0.5
						WHEN (keluar_diff > 61 AND keluar_diff < 90 
								AND keterangan NOT IN ('DL', 'CT', 'TB', 'ST', 'SC', 'I', 'CBL', 'CB', 'CP', 'CS', 'SC', 'BT')
								AND tanggal NOT IN (SELECT tanggal FROM libur_nasional) )
							OR keterangan = 'PSW.3' THEN 1.25
						ELSE 2.5 END) as keluar
			FROM (
			SELECT a.tanggal, b.nip as fsidno, c.nama as fsname, 
				DATE_FORMAT(FROM_UNIXTIME(a.masuk), '%H:%i:%s') as masuk,
				DATE_FORMAT(FROM_UNIXTIME(a.keluar), '%H:%i:%s') as keluar,
				a.keterangan,
				TIMESTAMPDIFF(MINUTE, CONCAT(a.tanggal, ' ".$_SESSION['dataMasuk']['time_end']."'), FROM_UNIXTIME(a.masuk)) as masuk_diff,
				TIMESTAMPDIFF(MINUTE, FROM_UNIXTIME(a.keluar), CONCAT(a.tanggal, ' ".$_SESSION['dataKeluar']['time_start']."')) as keluar_diff,
				h.v_uraian as golongan,
				(CASE WHEN d.jabatan = '-' OR d.jabatan IS NULL THEN 99999999 ELSE d.jabatan END) as jabatan
			FROM absensi_pegawai a
			JOIN pegawai_registry b ON a.fscardno = b.userid
			JOIN pegawai c ON b.nip = c.nip
			LEFT JOIN pegawai_unitkerja d ON c.nip = d.nip
			LEFT JOIN t_par_instansi e ON d.unit_biro = e.c_kode
			LEFT JOIN t_par_unitkerja f ON d.unit_bagian = f.c_kode
			LEFT JOIN t_par_subunitkerja g ON d.unit_subbagian = g.c_kode 
			LEFT JOIN t_par_golongan h ON d.golongan = h.c_kode 
			WHERE UNIX_TIMESTAMP(a.tanggal) BETWEEN ".$sdate." AND ".$edate."
			AND d.unit_bagian = '".$_GET['unit_bagian']."'
			ORDER BY tanggal, d.jabatan, c.nip ) a
			GROUP BY fsname
			ORDER BY jabatan, fsidno";
	$data = $db->dbFetchArray($sql);
	$sql = "SELECT v_uraian as nama_id FROM t_par_unitkerja WHERE c_kode = '".$_GET['unit_bagian']."'";
	$unit = $db->dbFetchArray($sql);
	
	$output = "";
	if($data!="null")
	{
		$totalRow = count($data);
		$rowPerPage = 20;
		$totalPage = ceil($totalRow/$rowPerPage);
		
		#report style start here
		$output .= '<style>';
		$output .= '.tableHeader{font-weight:bold; border:1px solid; text-align:center; vertical-align:middle; font-size:10px;}';
		$output .= '.tableRow{border:1px solid; text-align:center; vertical-align:top; padding:2px; font-size:10px;}';
		$output .= '.name{text-align:left;}';
		$output .= '</style>';
		#report style end here
		
		for($i=0; $i<$totalPage; $i++)
		{
			#report title start here
			$output .= '<table align="center" width="90%" style="margin-top:75px;">';
			$output .= '<tr>';
			$output .= '<td style="padding:5px; font-size:9px;">
						KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>
						DIREKTORAT JENDERAL KEKAYAAN NEGARA
						</td>';
			$output .= '<td style="padding:5px; text-align:right; font-size:9px;">
						[TKPKN]<br>
						Tanggal: '.date('d-m-Y').'<br>
						Halaman: '.($i+1).'
						</td>';
			$output .= '</tr>';
			$output .= '<tr align="center">';
			$output .= '<td colspan="2">REKAPITULASI ABSENSI BULANAN</td>';
			$output .= '</tr>';
			$output .= '<tr align="center">';
			$output .= '<td colspan="2">UNIT: '.$unit[0]['nama_id'].'</td>';
			$output .= '</tr>';
			$output .= '<tr align="center">';
			$output .= '<td colspan="2">PERIODE: '.$_GET['tgl_mulai'].' s/d '.$_GET['tgl_selesai'].'</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td width="350px">Jumlah Pegawai Seluruhnya</td>';
			$output .= '<td>: '.$totalRow.'</td>';
			$output .= '</tr>';
			$output .= '</table><br><br>';
			#report title end here
			
			#report table start here
			#table header start here
			$output .= '<table cellspacing="-1" align="center" width="90%">';
			$output .= '<tr>';
			$output .= '<td rowspan="3" class="tableHeader">No.</td>';
			$output .= '<td rowspan="3" class="tableHeader">Nama/NIP</td>';
			$output .= '<td rowspan="3" class="tableHeader">Gol.</td>';
			$output .= '<td colspan="15" class="tableHeader">Tidak Hadir</td>';
			$output .= '<td rowspan="3" class="tableHeader">TL (%)</td>';
			$output .= '<td rowspan="3" class="tableHeader">PSW (%)</td>';
			$output .= '<td rowspan="3" class="tableHeader">Total Potongan TKPKN (%)</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td class="tableHeader" colspan="4">Tanpa Potongan</td>';
			$output .= '<td class="tableHeader" colspan="11">Dengan Potongan</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td class="tableHeader">DL</td>';
			$output .= '<td class="tableHeader">CT</td>';
			$output .= '<td class="tableHeader">TB</td>';
			$output .= '<td class="tableHeader">JML</td>';
			$output .= '<td class="tableHeader">X</td>';
			$output .= '<td class="tableHeader">ST</td>';
			$output .= '<td class="tableHeader">SC</td>';
			$output .= '<td class="tableHeader">I</td>';
			$output .= '<td class="tableHeader">CBL</td>';
			$output .= '<td class="tableHeader">CB</td>';
			$output .= '<td class="tableHeader">CP</td>';
			$output .= '<td class="tableHeader">CS</td>';
			$output .= '<td class="tableHeader">SC</td>';
			$output .= '<td class="tableHeader">BT</td>';
			$output .= '<td class="tableHeader">Jml</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td class="tableHeader">1</td>';
			$output .= '<td class="tableHeader">2</td>';
			$output .= '<td class="tableHeader">3</td>';
			$output .= '<td class="tableHeader">4</td>';
			$output .= '<td class="tableHeader">5</td>';
			$output .= '<td class="tableHeader">6</td>';
			$output .= '<td class="tableHeader">7</td>';
			$output .= '<td class="tableHeader">8</td>';
			$output .= '<td class="tableHeader">9</td>';
			$output .= '<td class="tableHeader">10</td>';
			$output .= '<td class="tableHeader">11</td>';
			$output .= '<td class="tableHeader">12</td>';
			$output .= '<td class="tableHeader">13</td>';
			$output .= '<td class="tableHeader">14</td>';
			$output .= '<td class="tableHeader">15</td>';
			$output .= '<td class="tableHeader">16</td>';
			$output .= '<td class="tableHeader">17</td>';
			$output .= '<td class="tableHeader">18</td>';
			$output .= '<td class="tableHeader">19</td>';
			$output .= '<td class="tableHeader">20</td>';
			$output .= '<td class="tableHeader">21</td>';
			$output .= '</tr>';
			#table header end here
			for($j=0; $j<$rowPerPage; $j++)
			{
				$rowIndex = ($rowPerPage*$i) + $j;
				if($rowIndex<$totalRow)
				{
					$jumlahTidakhadir = $data[$rowIndex]['tidakhadir']+$data[$rowIndex]['st']+$data[$rowIndex]['i']+$data[$rowIndex]['cbl']+$data[$rowIndex]['cb']+$data[$rowIndex]['cp']+$data[$rowIndex]['cs']+$data[$rowIndex]['cln'];
					$jumlahTanpaPotongan = $data[$rowIndex]['dl']+$data[$rowIndex]['ct']+$data[$rowIndex]['tb'];
					$output .= '<tr>';
					$output .= '<td class="tableRow">'.($rowIndex+1).'</td>';
					$output .= '<td class="tableRow name">'.$data[$rowIndex]['fsname'].' '.$data[$rowIndex]['jabatan'].'<br>'.$data[$rowIndex]['fsidno'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['golongan'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['dl'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['ct'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tb'].'</td>';
					$output .= '<td class="tableRow">'.$jumlahTanpaPotongan.'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tidakhadir'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['st'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['sc'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['i'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cbl'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cb'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cp'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cs'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['sc'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['sc'].'</td>';
					$output .= '<td class="tableRow">'.$jumlahTidakhadir.'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['masuk'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['keluar'].'</td>';
					$output .= '<td class="tableRow">'.($data[$rowIndex]['masuk']+$data[$rowIndex]['keluar']).'</td>';
					$output .= '</tr>';
				}
				
			}
			$output .= '</table><br><br>';
			#report table end here
			
			if(($totalPage-1) > $i)
			{
				$output .= '<div style="page-break-after:always;">&nbsp;</div>';
			}
		}
		$output .= '<br><br>';
		$output .= '<table cellspacing="-1" align="center" width="90%">';
		$output .= '<tr>';
		$output .= '<td width="60%">&nbsp;</td>';
		$output .= '<td width="40%">Kepala Bagian Kepegawaian</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>Nuning S.R Wulandari</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>NIP: 196307061991022001</td>';
		$output .= '</tr>';
		$output .= '</table>';
	}
	renderPDF($output, "portrait", "Laporan Absensi Bulanan");
}

function laporan_uangmakan()
{
	global $db;
	$tempdate = explode('-', $_GET['tgl_mulai']);
	$sdate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$tempdate = explode('-', $_GET['tgl_selesai']);
	$edate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$sql = "SELECT fsidno, fsname, COUNT(tanggal),
				   SUM(CASE WHEN masuk IS NULL AND keluar IS NULL AND keterangan IS NULL THEN 1 ELSE 0 END) as tidakhadir,
				   SUM(CASE WHEN masuk IS NOT NULL OR keluar IS NOT NULL AND keterangan IS NULL THEN 1 ELSE 0 END) as hadir,
				   SUM(CASE WHEN keterangan = 'DL' THEN 1 ELSE 0 END) as dl,
				   SUM(CASE WHEN keterangan = 'CT' THEN 1 ELSE 0 END) as ct,
				   SUM(CASE WHEN keterangan = 'TB' THEN 1 ELSE 0 END) as tb,
				   SUM(CASE WHEN keterangan = 'ST' THEN 1 ELSE 0 END) as st,
				   SUM(CASE WHEN keterangan = 'SC' THEN 1 ELSE 0 END) as sc,
				   SUM(CASE WHEN keterangan = 'I' THEN 1 ELSE 0 END) as 'i',
				   SUM(CASE WHEN keterangan = 'CBL' THEN 1 ELSE 0 END) as cbl,
				   SUM(CASE WHEN keterangan = 'CB' THEN 1 ELSE 0 END) as cb,
				   SUM(CASE WHEN keterangan = 'CP' THEN 1 ELSE 0 END) as cp,
				   SUM(CASE WHEN keterangan = 'CS' THEN 1 ELSE 0 END) as cs,
				   SUM(CASE WHEN keterangan = 'BT' THEN 1 ELSE 0 END) as bt,
				   golongan,
				   SUM(CASE WHEN keterangan = 'TL.1' OR (masuk_diff > 1 AND masuk_diff < 30) THEN 1 ELSE 0 END) as tl1,
				   SUM(CASE WHEN keterangan = 'TL.2' OR (masuk_diff > 31 AND masuk_diff < 60) THEN 1 ELSE 0 END) as tl2,
				   SUM(CASE WHEN keterangan = 'TL.3' OR (masuk_diff > 61 AND masuk_diff < 90) THEN 1 ELSE 0 END) as tl3,
				   SUM(CASE WHEN keterangan = 'TL.4' OR masuk_diff > 91 THEN 1 ELSE 0 END) as tl4,
				   SUM(CASE WHEN keterangan = 'PSW.1' OR (keluar_diff > 1 AND keluar_diff < 30) THEN 1 ELSE 0 END) as psw1,
				   SUM(CASE WHEN keterangan = 'PSW.2' OR (keluar_diff > 31 AND keluar_diff < 60) THEN 1 ELSE 0 END) as psw2,
				   SUM(CASE WHEN keterangan = 'PSW.3' OR (keluar_diff > 61 AND keluar_diff < 90) THEN 1 ELSE 0 END) as psw3,
				   SUM(CASE WHEN keterangan = 'PSW.4' OR keluar_diff > 91 THEN 1 ELSE 0 END) as psw4
			FROM (
			SELECT a.tanggal, b.nip as fsidno, c.nama as fsname, 
				DATE_FORMAT(FROM_UNIXTIME(a.masuk), '%H:%i:%s') as masuk,
				DATE_FORMAT(FROM_UNIXTIME(a.keluar), '%H:%i:%s') as keluar,
				a.keterangan,
				TIMESTAMPDIFF(MINUTE, CONCAT(a.tanggal, ' ".$_SESSION['dataMasuk']['time_end']."'), FROM_UNIXTIME(a.masuk)) as masuk_diff,
				TIMESTAMPDIFF(MINUTE, FROM_UNIXTIME(a.keluar), CONCAT(a.tanggal, ' ".$_SESSION['dataKeluar']['time_start']."')) as keluar_diff,
				h.v_uraian as golongan,
				(CASE WHEN d.jabatan = '-' OR d.jabatan IS NULL THEN 99999999 ELSE d.jabatan END) as jabatan
			FROM absensi_pegawai a
			JOIN pegawai_registry b ON a.fscardno = b.userid
			JOIN pegawai c ON b.nip = c.nip
			LEFT JOIN pegawai_unitkerja d ON c.nip = d.nip
			LEFT JOIN t_par_instansi e ON d.unit_biro = e.c_kode
			LEFT JOIN t_par_unitkerja f ON d.unit_bagian = f.c_kode
			LEFT JOIN t_par_subunitkerja g ON d.unit_subbagian = g.c_kode 
			LEFT JOIN t_par_golongan h ON d.golongan = h.c_kode 
			WHERE UNIX_TIMESTAMP(a.tanggal) BETWEEN ".$sdate." AND ".$edate."
			AND d.unit_bagian = '".$_GET['unit_bagian']."'
			ORDER BY tanggal, fsname ) a
			GROUP BY fsname
			ORDER BY jabatan, fsidno";
	$data = $db->dbFetchArray($sql);
	$sql = "SELECT v_uraian as nama_id FROM t_par_unitkerja WHERE c_kode = '".$_GET['unit_bagian']."'";
	$unit = $db->dbFetchArray($sql);
	
	$output = "";
	if($data!="null")
	{
		$totalRow = count($data);
		$rowPerPage = 20;
		$totalPage = ceil($totalRow/$rowPerPage);
		
		#report style start here
		$output .= '<style>';
		$output .= '.tableHeader{font-weight:bold; border:1px solid; text-align:center; vertical-align:middle; font-size:10px;}';
		$output .= '.tableRow{border:1px solid; text-align:center; vertical-align:top; padding:2px; font-size:10px;}';
		$output .= '.name{text-align:left;}';
		$output .= '</style>';
		#report style end here
		
		for($i=0; $i<$totalPage; $i++)
		{
			#report title start here
			$output .= '<table align="center" width="90%" style="margin-top:75px;">';
			$output .= '<tr>';
			$output .= '<td style="padding:5px; font-size:9px;">
						KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>
						DIREKTORAT JENDERAL KEKAYAAN NEGARA
						</td>';
			$output .= '<td style="padding:5px; text-align:right; font-size:9px;">
						[UANG MAKAN]<br>
						Tanggal: '.date('d-m-Y').'<br>
						Halaman: '.($i+1).'
						</td>';
			$output .= '</tr>';
			$output .= '<tr align="center">';
			$output .= '<td colspan="2">REKAPITULASI ABSENSI BULANAN</td>';
			$output .= '</tr>';
			$output .= '<tr align="center">';
			$output .= '<td colspan="2">UNIT: '.$unit[0]['nama_id'].'</td>';
			$output .= '</tr>';
			$output .= '<tr align="center">';
			$output .= '<td colspan="2">PERIODE: '.$_GET['tgl_mulai'].' s/d '.$_GET['tgl_selesai'].'</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td width="350px">Jumlah Pegawai Seluruhnya</td>';
			$output .= '<td>: '.$totalRow.'</td>';
			$output .= '</tr>';
			$output .= '</table><br><br>';
			#report title end here
			
			#report table start here
			#table header start here
			$output .= '<table cellspacing="-1" align="center" width="90%">';
			$output .= '<tr>';
			$output .= '<td rowspan="2" class="tableHeader">No.</td>';
			$output .= '<td rowspan="2" class="tableHeader">Nama/NIP</td>';
			$output .= '<td rowspan="2" class="tableHeader">Gol.</td>';
			$output .= '<td colspan="13" class="tableHeader">Tidak Hadir</td>';
			$output .= '<td colspan="9" class="tableHeader">TL/PSW</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td class="tableHeader">DL</td>';
			$output .= '<td class="tableHeader">CT</td>';
			$output .= '<td class="tableHeader">TB</td>';
			$output .= '<td class="tableHeader">A</td>';
			$output .= '<td class="tableHeader">ST</td>';
			$output .= '<td class="tableHeader">SC</td>';
			$output .= '<td class="tableHeader">I</td>';
			$output .= '<td class="tableHeader">CBL</td>';
			$output .= '<td class="tableHeader">CB</td>';
			$output .= '<td class="tableHeader">CP</td>';
			$output .= '<td class="tableHeader">CS</td>';
			$output .= '<td class="tableHeader">BT</td>';
			$output .= '<td class="tableHeader">Jml</td>';
			$output .= '<td class="tableHeader">TL.1</td>';
			$output .= '<td class="tableHeader">TL.2</td>';
			$output .= '<td class="tableHeader">TL.3</td>';
			$output .= '<td class="tableHeader">TL.4</td>';
			$output .= '<td class="tableHeader">PSW.1</td>';
			$output .= '<td class="tableHeader">PSW.2</td>';
			$output .= '<td class="tableHeader">PSW.3</td>';
			$output .= '<td class="tableHeader">PSW.4</td>';
			$output .= '<td class="tableHeader">Jml</td>';
			$output .= '</tr>';
			$output .= '<tr>';
			$output .= '<td class="tableHeader">1</td>';
			$output .= '<td class="tableHeader">2</td>';
			$output .= '<td class="tableHeader">3</td>';
			$output .= '<td class="tableHeader">4</td>';
			$output .= '<td class="tableHeader">5</td>';
			$output .= '<td class="tableHeader">6</td>';
			$output .= '<td class="tableHeader">7</td>';
			$output .= '<td class="tableHeader">8</td>';
			$output .= '<td class="tableHeader">9</td>';
			$output .= '<td class="tableHeader">10</td>';
			$output .= '<td class="tableHeader">11</td>';
			$output .= '<td class="tableHeader">12</td>';
			$output .= '<td class="tableHeader">13</td>';
			$output .= '<td class="tableHeader">14</td>';
			$output .= '<td class="tableHeader">15</td>';
			$output .= '<td class="tableHeader">16</td>';
			$output .= '<td class="tableHeader">17</td>';
			$output .= '<td class="tableHeader">18</td>';
			$output .= '<td class="tableHeader">19</td>';
			$output .= '<td class="tableHeader">20</td>';
			$output .= '<td class="tableHeader">21</td>';
			$output .= '<td class="tableHeader">22</td>';
			$output .= '<td class="tableHeader">23</td>';
			$output .= '<td class="tableHeader">24</td>';
			$output .= '<td class="tableHeader">25</td>';
			$output .= '</tr>';
			#table header end here
			for($j=0; $j<$rowPerPage; $j++)
			{
				$rowIndex = ($rowPerPage*$i) + $j;
				if($rowIndex<$totalRow)
				{
					$jumlahTidakhadir = $data[$rowIndex]['tidakhadir']+$data[$rowIndex]['st']+$data[$rowIndex]['i']+$data[$rowIndex]['cbl']+$data[$rowIndex]['cb']+$data[$rowIndex]['cp']+$data[$rowIndex]['cs']+$data[$rowIndex]['cln']+$data[$rowIndex]['dl']+$data[$rowIndex]['ct']+$data[$rowIndex]['tb'];
					
					$output .= '<tr>';
					$output .= '<td class="tableRow">'.($rowIndex+1).'</td>';
					$output .= '<td class="tableRow name">'.$data[$rowIndex]['fsname'].'<br>'.$data[$rowIndex]['fsidno'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['golongan'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['dl'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['ct'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tb'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tidakhadir'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['st'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['sc'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['i'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cbl'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cb'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cp'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['cs'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['bt'].'</td>';
					$output .= '<td class="tableRow">'.$jumlahTidakhadir.'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tl1'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tl2'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tl3'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['tl4'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['psw1'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['psw2'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['psw3'].'</td>';
					$output .= '<td class="tableRow">'.$data[$rowIndex]['psw4'].'</td>';
					$totalTL = $data[$rowIndex]['tl1']+$data[$rowIndex]['tl2']+$data[$rowIndex]['tl3']+$data[$rowIndex]['tl4'];
					$totalPSW = $data[$rowIndex]['psw1']+$data[$rowIndex]['psw2']+$data[$rowIndex]['psw3']+$data[$rowIndex]['psw4'];
					$output .= '<td class="tableRow">'.($totalTL+$totalPSW).'</td>';
					$output .= '</tr>';
				}
				
			}
			$output .= '</table><br><br>';
			#report table end here
			
			if(($totalPage-1) > $i)
			{
				$output .= '<div style="page-break-after:always;">&nbsp;</div>';
			}
		}
		$output .= '<br><br>';
		$output .= '<table cellspacing="-1" align="center" width="90%">';
		$output .= '<tr>';
		$output .= '<td width="60%">&nbsp;</td>';
		$output .= '<td width="40%">Kepala Bagian Kepegawaian</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>Nuning S.R Wulandari</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>NIP: 196307061991022001</td>';
		$output .= '</tr>';
		$output .= '</table>';
	}
	renderPDF($output, "portrait", "Laporan Absensi Bulanan");
}

function laporan_pegawai()
{
	global $db;
	$tempdate = explode('-', $_GET['tgl_mulai']);
	$sdate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$tempdate = explode('-', $_GET['tgl_selesai']);
	$edate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$sql = "SELECT DATE_FORMAT(a.tanggal, '%d %M %Y') as tanggal, 
					b.nip as fsidno, c.nama as fsname, 
				DATE_FORMAT(FROM_UNIXTIME(a.masuk), '%H:%i:%s') as masuk,
				DATE_FORMAT(FROM_UNIXTIME(a.keluar), '%H:%i:%s') as keluar,
				a.keterangan,
				TIMESTAMPDIFF(MINUTE, CONCAT(a.tanggal, ' ".$_SESSION['dataMasuk']['time_end']."'), FROM_UNIXTIME(a.masuk)) as masuk_diff,
				TIMESTAMPDIFF(MINUTE, FROM_UNIXTIME(a.keluar), CONCAT(a.tanggal, ' ".$_SESSION['dataKeluar']['time_start']."')) as keluar_diff,
				h.v_pangkatgol, e.v_uraian as eselon_2, f.v_uraian as eselon_3
			FROM absensi_pegawai a
			JOIN pegawai_registry b ON a.fscardno = b.userid
			JOIN pegawai c ON b.nip = c.nip
			LEFT JOIN pegawai_unitkerja d ON c.nip = d.nip
			LEFT JOIN t_par_instansi e ON d.unit_biro = e.c_kode
			LEFT JOIN t_par_unitkerja f ON d.unit_bagian = f.c_kode
			LEFT JOIN t_par_subunitkerja g ON d.unit_subbagian = g.c_kode 
			LEFT JOIN t_par_golongan h ON d.golongan = h.c_kode 
			WHERE UNIX_TIMESTAMP(a.tanggal) BETWEEN ".$sdate." AND ".$edate."
			AND b.nip = '".$_GET['nip']."'
			ORDER BY tanggal";
	$data = $db->dbFetchArray($sql);
	
	$sql = "SELECT tanggal FROM libur_nasional";
	$libur = $db->dbFetchArray($sql);
	$hariLibur = array();
	if($libur!="null")
	{
		foreach($libur as $val)
		{
			$hariLibur[] = $val;
		}
	}
	
	$output = "";
	if($data!="null")
	{
		$totalRow = count($data);
		$rowPerPage = 20;
		$totalPage = ceil($totalRow/$rowPerPage);
		
		#report style start here
		$output .= '<style>';
		$output .= '.tableHeader{font-weight:bold; border:1px solid; text-align:center; vertical-align:middle; font-size:10px;}';
		$output .= '.tableRow{border:1px solid; text-align:center; vertical-align:top; padding:2px; font-size:10px;}';
		$output .= '.name{text-align:left;}';
		$output .= '</style>';
		#report style end here
		
		#report title start here
		$output .= '<table align="center" width="90%" style="margin-top:75px;">';
		$output .= '<tr>';
		$output .= '<td style="padding:5px; font-size:9px;">
					KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>
					DIREKTORAT JENDERAL KEKAYAAN NEGARA
					</td>';
		$output .= '<td style="padding:5px; text-align:right; font-size:9px;">
					Tanggal: '.date('d-m-Y').'<br>
					Halaman: '.($i+1).'
					</td>';
		$output .= '</tr>';
		$output .= '<tr align="center">';
		$output .= '<td colspan="2">LAPORAN ABSENSI BULANAN PEGAWAI</td>';
		$output .= '</tr>';
		$output .= '<tr align="center">';
		$output .= '<td colspan="2">';
		$output .= '<table align="center">';
		$output .= '<tr>';
		$output .= '<td align="left">Nama: '.$data[0]['fsname'].'</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td align="left">NIP: '.$data[0]['fsidno'].'</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td align="left">Pangkat/Gol: '.$data[0]['v_pangkatgol'].'</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td align="left">Unit Eselon 2: '.$data[0]['eselon_2'].'</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td align="left">Unit Eselon 3: '.$data[0]['eselon_3'].'</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td align="left">PERIODE: '.$_GET['tgl_mulai'].' s/d '.$_GET['tgl_selesai'].'</td>';
		$output .= '</tr>';
		$output .= '</table>';
		$output .= '</td>';
		$output .= '</tr>';
		$output .= '</table><br><br>';
		#report title end here
		
		#report table start here
		#table header start here
		$output .= '<table cellspacing="-1" align="center" width="70%">';
		$output .= '<tr>';
		$output .= '<td class="tableHeader">No.</td>';
		$output .= '<td class="tableHeader">Tanggal</td>';
		$output .= '<td class="tableHeader">Uraian Absen</td>';
		$output .= '</tr>';
		#table header end here
		$num = 1;
		foreach($data as $value)
		{
			$date = strtotime($value['tanggal']);
			$day = date("D", $date);
			if($day!="Sun" && $day!="Sat" && !in_array($value['tanggal'], $hariLibur) )
			{
				if($value['keterangan']!="")
				{
					$uraian = $value['keterangan'];
				}
				if($value['keterangan'] == "" && $value['masuk'] == "" && $value['keluar'] == "")
				{
					$uraian = "Tanpa Keterangan";	
				}
				else
				{
					if($value['masuk_diff'] < 0 && $value['keluar_diff'] < 0)
					{
						$uraian = "Hadir";
					}
					else
					{
						$tl = hitungPotongan($value['masuk_diff'], "", "TL");
						$psw = hitungPotongan($value['keluar_diff'], "", "PSW");
						$uraian = $tl['type']." ".$psw['type'];
					}
				}
			}
			else
			{
				$uraian = "Akhir Pekan atau Hari Libur Nasional";
			}
			$output .= '<tr>';
			$output .= '<td class="tableRow">'.$num.'</td>';
			$output .= '<td class="tableRow">'.$value['tanggal'].'</td>';
			$output .= '<td class="tableRow">'.$uraian.'</td>';
			$output .= '</tr>';
			$num++;
		}
		$output .= '</table>';
		$output .= '<br><br>';
		$output .= '<table cellspacing="-1" align="center" width="90%">';
		$output .= '<tr>';
		$output .= '<td width="60%">&nbsp;</td>';
		$output .= '<td width="40%">Kepala Bagian Kepegawaian</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>Nuning S.R Wulandari</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>NIP: 196307061991022001</td>';
		$output .= '</tr>';
		$output .= '</table>';
		#report table end here
			
	}
	renderPDF($output, "portrait", "Laporan Absensi Bulanan Pegawai");
}

function laporan_absensi()
{
	global $db;
	$tempdate = explode('-', $_GET['tgl_mulai']);
	$sdate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$tempdate = explode('-', $_GET['tgl_selesai']);
	$edate = mktime(0,0,0, $tempdate[1], $tempdate[0], $tempdate[2]);
	$sql = "SELECT a.tanggal, b.nip as fsidno, c.nama as fsname, 
				   DATE_FORMAT(FROM_UNIXTIME(a.masuk), '%H:%i:%s') as masuk, 
				   DATE_FORMAT(FROM_UNIXTIME(a.keluar), '%H:%i:%s') as keluar, 
				   a.keterangan,
				   TIMESTAMPDIFF(MINUTE, CONCAT(a.tanggal, ' ".$_SESSION['dataMasuk']['time_end']."'), FROM_UNIXTIME(a.masuk)) as tl, 
       				TIMESTAMPDIFF(MINUTE, FROM_UNIXTIME(a.keluar), CONCAT(a.tanggal, ' ".$_SESSION['dataKeluar']['time_start']."')) as psw,
					(CASE WHEN g.v_uraian IS NOT NULL THEN g.v_uraian
				   WHEN f.v_uraian IS NOT NULL THEN f.v_uraian
				   ELSE e.v_uraian END ) as nama_id,
				   (CASE WHEN d.jabatan = '-' OR d.jabatan IS NULL THEN 99999999 ELSE d.jabatan END) as jabatan 
			FROM absensi_pegawai a 
			JOIN pegawai_registry b ON a.fscardno = b.userid 
			JOIN pegawai c ON b.nip = c.nip 
			LEFT JOIN pegawai_unitkerja d ON c.nip = d.nip
			LEFT JOIN t_par_instansi e ON d.unit_biro = e.c_kode
			LEFT JOIN t_par_unitkerja f ON d.unit_bagian = f.c_kode
			LEFT JOIN t_par_subunitkerja g ON d.unit_subbagian = g.c_kode 
			WHERE UNIX_TIMESTAMP(a.tanggal) BETWEEN ".$sdate." AND ".$edate." 
			AND d.unit_bagian = '".$_GET['unit_bagian']."' 
			ORDER BY tanggal, jabatan, fsidno";
	
	$data = $db->dbFetchArray($sql);
	
	$sql = "SELECT tanggal FROM libur_nasional";
	$libur = $db->dbFetchArray($sql);
	$hariLibur = array();
	if($libur!="null")
	{
		foreach($libur as $val)
		{
			$hariLibur[] = $val;
		}
	}
	//print_r($hariLibur);
	
	$sql = "SELECT v_uraian as nama_id FROM t_par_unitkerja WHERE c_kode = '".$_GET['unit_bagian']."'";
	$unit = $db->dbFetchArray($sql);
	$output = "";
	if($data!="null")
	{
		$fieldTanggal = array();
		$pegawaiNIP = array();
		$pegawaiNama = array();
		foreach($data as $key=>$value)
		{
			if(!in_array($value['tanggal'], $fieldTanggal))
			{
				$fieldTanggal[] = $value['tanggal'];
			}
			
			if(!in_array($value['fsidno'], $pegawaiNIP))
			{
				$pegawaiNIP[] = $value['fsidno'];
			}
			if(!in_array($value['fsname'], $pegawaiNama))
			{
				$pegawaiNama[] = $value['fsname'];
			}
		}
		
		$totalRow = count($pegawaiNIP);
		$output .= '<style>';
		$output .= '.tableHeader{font-weight:bold; border:1px solid; text-align:center; vertical-align:middle; font-size:10px;}';
		$output .= '.tableRow{border:1px solid; text-align:center; vertical-align:top; padding:2px; font-size:9px;}';
		$output .= '.name{text-align:left;}';
		$output .= '.small-text{ font-size:6px;}';
		$output .= '</style>';
		
		$output .= '<table align="center" width="900px">';
		$output .= '<tr>';
		$output .= '<td style="padding:5px;">
					KEMENTERIAN KEUANGAN REPUBLIK INDONESIA<br>
					DIREKTORAT JENDERAL KEKAYAAN NEGARA
					</td>';
		$output .= '<td style="padding:5px; text-align:right;">
					Tanggal: '.date('d-m-Y').'
					</td>';
		$output .= '</tr>';
		$output .= '<tr align="center">';
		$output .= '<td colspan="2">DAFTAR ABSENSI BULANAN PEGAWAI</td>';
		$output .= '</tr>';
		$output .= '<tr align="center">';
		$output .= '<td colspan="2">UNIT: '.$unit[0]['nama_id'].'</td>';
		$output .= '</tr>';
		$output .= '<tr align="center">';
		$output .= '<td colspan="2">PERIODE: '.$_GET['tgl_mulai'].' s/d '.$_GET['tgl_selesai'].'</td>';
		$output .= '</tr>';
		$output .= '</table><br><br>';
		
		$output .= '<table cellspacing="-1" align="center" width="980px">';
		#report title start here
		$totalColumn = count($fieldTanggal)+5;
		#report title end here
		
		#table header start here
		$output .= '<tr>';
		$output .= '<td rowspan="2" class="tableHeader">No.</td>';
		$output .= '<td rowspan="2" class="tableHeader">Nama/NIP</td>';
		$output .= '<td colspan="'.count($fieldTanggal).'" class="tableHeader">Tanggal</td>';
		$output .= '<td colspan="4" class="tableHeader">Jumlah</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		
		foreach($fieldTanggal as $tanggal)
		{
			$tanggal = explode('-', $tanggal);
			$output .= '<td class="tableHeader">'.$tanggal[2].'<br>'.$hari.'</td>';
		}
		$output .= '<td class="tableHeader">TPot</td>';
		$output .= '<td class="tableHeader">Pot</td>';
		$output .= '<td class="tableHeader">TL</td>';
		$output .= '<td class="tableHeader">PSW</td>';
		$output .= '</tr>';
		#table header end here
		
		#table row start here
		for($i=0; $i<$totalRow; $i++)
		{
			$totalPotongan = 0;
			$totalTL = 0;
			$totalPSW = 0;
			$totalTLMenit = 0;
			$totalPSWMenit = 0;
			$tanpaPotongan = 0;
			$denganPotongan = 0;
			$bgColor = ($i%2==0) ? "#FFFFFF" : "#CCCCCC";
			$output .= '<tr bgcolor="'.$bgColor.'">';
			$output .= '<td class="tableRow">'.($i+1).'</td>';
			$output .= '<td class="tableRow name" width="100px">'.$pegawaiNama[$i].'<br>NIP: '.$pegawaiNIP[$i].'</td>';
			foreach($fieldTanggal as $tanggal)
			{
				foreach($data as $value)
				{
					$absen = "";
					$tkpkn = 0;
					if($value['tanggal'] == $tanggal && $value['fsidno'] == $pegawaiNIP[$i])
					{
						//echo $value['tanggal']." ";
						$date = strtotime($tanggal);
						$hari = date("D", $date);
						if( $hari != "Sun" && $hari != "Sat" && !in_array(trim($tanggal), $hariLibur) )
						{
							if($value['tl']=="")
							{
								$absen = "-";
							}
							elseif($value['tl']>0 && $value['tl']<31)
							{
								$absen = "TL.1 <div class='small-text'>(".$value['tl']." menit);</div><br>";
								$tkpkn = $tkpkn + 0.5;
							}
							elseif($value['tl']>30 && $value['tl']<61)
							{
								$absen = "TL.2 <div class='small-text'>(".$value['tl']." menit);</div><br>";
								$tkpkn = $tkpkn + 1;
							}
							elseif($value['tl']>60 && $value['tl']<91)
							{
								$absen = "TL.3 <div class='small-text'>(".$value['tl']." menit);</div><br>";
								$tkpkn = $tkpkn + 1.25;
							}
							elseif($value['tl']>91)
							{
								$absen = "TL.4 <div class='small-text'>(".$value['tl']." menit);</div><br>";
								$tkpkn = $tkpkn + 2.5;
							}
							else
							{
								$absen = "&nbsp;";
							}
							
							if($value['psw']=="")
							{
								$absen .= "-";
							}
							elseif($value['psw']>0 && $value['psw']<31)
							{
								$absen .= "PSW.1 <div class='small-text'>(".$value['psw']." menit);</div>";
								$tkpkn = $tkpkn + 0.5;
							}
							elseif($value['psw']>30 && $value['psw']<61)
							{
								$absen .= "PSW.2 <div class='small-text'>(".$value['psw']." menit);</div>";
								$tkpkn = $tkpkn + 1;
							}
							elseif($value['psw']>60 && $value['psw']<91)
							{
								$absen .= "PSW.3 <div class='small-text'>(".$value['psw']." menit);</div>";
								$tkpkn = $tkpkn + 1.25;
							}
							elseif($value['psw']>91)
							{
								$absen .= "PSW.4 <div class='small-text'>(".$value['psw']." menit);</div>";
								$tkpkn = $tkpkn + 2.5;
							}
							else
							{
								$absen .= "&nbsp;";
							}
							
							$ketTanpaPotongan = array("DL", "CT", "TB");
							if(in_array($value['keterangan'], $ketTanpaPotongan))
							{
								$absen = $value['keterangan'];
								$tkpkn = 0;
								$tanpaPotongan++;
							}
							else
							{
								if($value['keterangan']!="")
								{
									$absen = $value['keterangan'];
									$tkpkn = countTKPKNBasedOnKet($value['keterangan']);
								}
								if ($tkpkn==0)
								{
									$tanpaPotongan++;
								}
								else
								{
									$denganPotongan++;
								}
							}
							$output .= '<td class="tableRow" width="20px">'.$absen.'</td>';
						}
						else
						{
							$output .= '<td class="tableRow" width="20px" bgcolor="#FF0000">&nbsp;</td>';
						}
						
						$totalPotongan = $totalPotongan + $tkpkn;
						$totalTL = (strstr($absen, 'TL')) ? $totalTL+1 : $totalTL+0;
						$totalPSW = (strstr($absen, 'PSW')) ? $totalPSW+1 : $totalPSW+0;
						$totalTLMenit = ($value['tl']>0) ? $totalTLMenit+$value['tl'] : $totalTLMenit+0;
						$totalPSWMenit = ($value['psw']>0) ? $totalPSWMenit+$value['psw'] : $totalPSWMenit+0;
					}
				}
				
			}
			
			$totalTLMenit = ($totalTLMenit>0) ? $totalTLMenit/60 : 0;
			$totalPSWMenit = ($totalPSWMenit>0) ? $totalPSWMenit/60 : 0;
			
			$sumJam = round($totalTLMenit)+round($totalPSWMenit);
			$sum = ($sumJam>0) ? ($sumJam/7) : 0;
			$sum = (floor($sum)>=1) ? floor($sum)*5 : 0;
			
			$output .= '<td class="tableRow">'.$tanpaPotongan.'</td>';
			$output .= '<td class="tableRow">'.$denganPotongan.'</td>';
			$output .= '<td class="tableRow">'.$totalTL.'</td>';
			$output .= '<td class="tableRow">'.$totalPSW.'</td>';
			$output .= '</tr>';
		}
		#table row end here
		$output .= '</table>';
		$output .= '<br><br>';
		$output .= '<table cellspacing="-1" align="center" width="980px">';
		$output .= '<tr>';
		$output .= '<td width="60%">&nbsp;</td>';
		$output .= '<td width="40%">Kepala Bagian Kepegawaian</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td colspan="2" style="color:#FFFFFF;">...</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>Nuning S.R Wulandari</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>&nbsp;</td>';
		$output .= '<td>NIP: 196307061991022001</td>';
		$output .= '</tr>';
		$output .= '</table>';
	}
	
	//echo $output;
	renderPDF($output, "landscape", "Laporan Absensi Bulanan");
}

function countTKPKNBasedOnKet($keterangan)
{
	switch($keterangan)
	{
		case "TL.1":
			$potongan = 0.5;
			break;
		case "TL.2":
			$potongan = 1;
			break;
		case "TL.3":
			$potongan = 1.25;
			break;
		case "TL.4":
			$potongan = 2.5;
			break;
		case "PSW.1":
			$potongan = 0.5;
			break;
		case "PSW.2":
			$potongan = 1;
			break;
		case "PSW.3":
			$potongan = 1.25;
			break;
		case "PSW.4":
			$potongan = 2.5;
			break;
	}
	return $potongan;
}

function getDataAbsensi()
{
	global $db;
	$tgl = explode("-", $_POST['tanggal']);
	$tanggal = $tgl[2]."-".$tgl[1]."-".$tgl[0];
	$sql = "SELECT fscardno, tanggal, keterangan,
					DATE_FORMAT(FROM_UNIXTIME(masuk), '%H:%i:%s') as masuk,
					DATE_FORMAT(FROM_UNIXTIME(keluar), '%H:%i:%s') as keluar
			FROM absensi_pegawai
			WHERE fscardno = '".$_POST['id']."'
			AND tanggal = '".$tanggal."'";
	$result = $db->dbFetchArray($sql);
	$masuk = ($result[0]['masuk']!="") ? explode(":", $result[0]['masuk']) : array("", "", "");
	$keluar = ($result[0]['keluar']!="") ? explode(":", $result[0]['keluar']) : array("", "", "");
	$ketOption = array("Hadir", "DL", "CT", "TB", "ST", "I", "CBL", "CB", "CP", "CS", "SC", "BT", "TL.1", "TL.2", "TL.3", "TL.4", "PSW.1", "PSW.2", "PSW.3", "PSW.4");
	$keterangan = '<select id="keterangan">';
	$keterangan .= '<option value="0">-</option>';
	foreach($ketOption as $option)
	{
		$selected = "";
		if($result[0]['keterangan']==$option)
		{
			$selected = "selected";	
		}
		$keterangan .= '<option value="'.$option.'" '.$selected.'>'.$option.'</option>';
	}
	$keterangan .= '</select>';
	$output = '<table>
				<tr>
					<td>Waktu Masuk</td>
					<td>: 
						<input type="text" id="h_masuk" size="2" value="'.$masuk[0].'">:
						<input type="text" id="m_masuk" size="2" value="'.$masuk[1].'">:
						<input type="text" id="s_masuk" size="2" value="'.$masuk[2].'">
					</td>
				</tr>
				<tr>
					<td>Waktu Keluar</td>
					<td>: 
						<input type="text" id="h_keluar" size="2" value="'.$keluar[0].'">:
						<input type="text" id="m_keluar" size="2" value="'.$keluar[1].'">:
						<input type="text" id="s_keluar" size="2" value="'.$keluar[2].'">
					</td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td>: '.$keterangan.'</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" id="btnSimpan" value="Simpan Data" onclick="simpanAbsensi(\''.$_POST['id'].'\', \''.$tanggal.'\')" />&nbsp;
						<input type="button" id="btnBatal" value="Batal" onclick="$(\'#pegawaiForm\').dialog(\'close\')" />
					</td>
				</tr>
				</table>';
	echo $output;
}

function getDataPengguna()
{
	global $db;
	$crudType = ($_POST['edit']) ? "edit" : "add";
	$sql = "SELECT *
			FROM user_access
			WHERE id = '".$_POST['id']."'";
	$result = $db->dbFetchArray($sql);
	
	$levelType = array("admin", "user");
	$cmbLevel = '<select id="level">';
	foreach($levelType as $type)
	{
		if($_POST['edit'])
		{
			$selected = ($type==$result[0]['type']) ? "selected" : "";
		}
		$cmbLevel .= '<option value="'.$type.'" '.$selected.'>'.$type.'</option>';
	}
	$cmbLevel .= '</select>';
	
	if($_POST['edit'])
	{
		$username = $result[0]['username'];
		$id = $result[0]['id'];
	}
	else
	{
		$username = "";
		$id = "";
	}
	
	$output = '<table>
				<tr>
					<td>Username</td>
					<td>: <input type="text" id="username" value="'.$username.'" /></td>
				</tr>
				<tr>
					<td>New Password</td>
					<td>: <input type="password" id="password_1" value="" /></td>
				</tr>
				<tr>
					<td>Confirm New Password</td>
					<td>: <input type="password" id="password_2" value="" /></td>
				</tr>
				<tr>
					<td>Access Level</td>
					<td>: '.$cmbLevel.'</td>
				</tr>
				<tr>
					<td colspan="2" id="formMsg"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" id="btnSimpan" value="Simpan Data" onclick="simpanPengguna(\''.$id.'\', \''.$crudType.'\')" />&nbsp;
						<input type="button" id="btnBatal" value="Batal" onclick="$(\'#pegawaiForm\').dialog(\'close\')" />
					</td>
				</tr>
				</table>';
	echo $output;
}

function getDataHariLibur()
{
	global $db;
	$crudType = ($_POST['edit']) ? "edit" : "add";
	$sql = "SELECT id, DATE_FORMAT(tanggal, '%d-%m-%Y') as tanggal, keterangan 
			FROM libur_nasional
			WHERE id = '".$_POST['id']."'";
	$result = $db->dbFetchArray($sql);
	
	if($_POST['edit'])
	{
		$tanggal = $result[0]['tanggal'];
		$id = $result[0]['id'];
		$keterangan = $result[0]['keterangan'];
	}
	else
	{
		$username = "";
		$id = "";
		$keterangan = "";
	}
	
	$output = '<table>
				<tr>
					<td>Tanggal</td>
					<td> <input type="text" id="tanggal" value="'.$tanggal.'" /></td>
				</tr>
				<tr>
					<td valign="top">Keterangan</td>
					<td> <textarea rows="3" cols="55" id="keterangan">'.$keterangan.'</textarea></td>
				</tr>
				<tr>
					<td colspan="2" id="formMsg"></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" id="btnSimpan" value="Simpan Data" onclick="simpanHariLibur(\''.$id.'\', \''.$crudType.'\')" />&nbsp;
						<input type="button" id="btnBatal" value="Batal" onclick="$(\'#pegawaiForm\').dialog(\'close\')" />
					</td>
				</tr>
				</table>';
	echo $output;
}

function getDataPegawai()
{
	global $db;
	$sql = "SELECT a.nip, a.nama,
				   b.unit_biro,
				   (SELECT v_uraian FROM t_par_instansi WHERE c_kode = b.unit_biro) as biro,
				   b.unit_bagian,
				   (SELECT v_uraian FROM t_par_unitkerja WHERE c_kode = b.unit_bagian) as bagian,
				   b.unit_subbagian,
				   (SELECT v_uraian FROM t_par_subunitkerja WHERE c_kode = b.unit_subbagian) as subbagian
			FROM pegawai a
			JOIN pegawai_unitkerja b ON a.nip = b.nip
			WHERE a.nip = '".$_POST['nip']."'";
	$result = $db->dbFetchArray($sql);
	$allBiro = getDataBiro();
	$optionBiro = '<select id="unit_biro" onchange="getBagian($(this).val())">';
	foreach($allBiro as $biro)
	{
		$selected = "";
		if($biro['id']==$result[0]['unit_biro'])
		{
			$selected = "selected";	
		}
		$optionBiro .= '<option value="'.$biro['id'].'" '.$selected.'>'.$biro['nama_id'].'</option>';
	}
	$optionBiro .= '</select>';
	
	$allBagian = getDataBagian($result[0]['unit_biro']);
	$optionBagian = '<select id="unit_bagian" onchange="getSubBagian($(this).val())">';
	$optionBagian .= '<option value="0">-</option>';
	foreach($allBagian as $bagian)
	{
		$selected = "";
		if($bagian['id']==$result[0]['unit_bagian'])
		{
			$selected = "selected";	
		}
		$optionBagian .= '<option value="'.$bagian['id'].'" '.$selected.'>'.$bagian['nama_id'].'</option>';
	}
	$optionBagian .= '</select>';
	
	$allSub = getDataSubBagian($result[0]['unit_bagian']);
	$optionSubBagian = '<select id="unit_subbagian">';
	$optionSubBagian .= '<option value="0">-</option>';
	foreach($allSub as $subbagian)
	{
		$selected = "";
		if($subbagian['id']==$result[0]['unit_subbagian'])
		{
			$selected = "selected";	
		}
		$optionSubBagian .= '<option value="'.$subbagian['id'].'" '.$selected.'>'.$subbagian['nama_id'].'</option>';
	}
	$optionSubBagian .= '</select>';
	
	$output = '<table>
				<tr><td>NIP</td><td>: '.$result[0]['nip'].'</td></tr>
				<tr>
					<td>Nama Pegawai</td>
					<td>: '.$result[0]['nama'].'</td>
				</tr>
				<tr>
					<td>Unit Eselon 2</td>
					<td>: '.$optionBiro.'</td>
				</tr>
				<tr>
					<td>Unit Eselon 3</td>
					<td>: '.$optionBagian.'</td>
				</tr>
				<tr>
					<td>Unit Eselon 4</td>
					<td>: '.$optionSubBagian.'</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" id="btnSimpan" value="Simpan Data" onclick="simpanPegawai(\''.$result[0]['nip'].'\')" />&nbsp;
						<input type="button" id="btnBatal" value="Batal" onclick="$(\'#pegawaiForm\').dialog(\'close\')" />
					</td>
				</tr>
				</table>';
	echo $output;
}

function getDataBiro()
{
	global $db;
	$sql = "SELECT c_kode as id, v_uraian as nama_id FROM t_par_instansi ORDER BY c_kode";
	$result = $db->dbFetchArray($sql);
	return $result;
}

function getDataBagian($biro)
{
	global $db;
	$sql = "SELECT c_kode as id, v_uraian as nama_id FROM t_par_unitkerja WHERE c_kode LIKE '".$biro."%' ";
	$result = $db->dbFetchArray($sql);
	return $result;
}

function getDataSubBagian($bagian)
{
	global $db;
	$sql = "SELECT c_kode as id, v_uraian as nama_id FROM t_par_subunitkerja WHERE c_kode LIKE '".$bagian."%' ";
	$result = $db->dbFetchArray($sql);
	return $result;
}

function getBagianCombo()
{
	$data = getDataBagian($_POST['biro']);
	echo createComboBox($data);
}

function getSubBagianCombo()
{
	$data = getDataSubBagian($_POST['bagian']);
	echo createComboBox($data);
}

function createComboBox($data)
{
	$option = '<option value="0">-</option>';
	foreach($data as $row)
	{
		$option .= '<option value="'.$row['id'].'">'.$row['nama_id'].'</option>';
	}
	return $option;
}

function login()
{
	global $db;
	$sql = "SELECT * FROM user_access WHERE username = '".$_POST['username']."'";
	$rs = $db->dbFetchArray($sql);
	if($rs[0]['username']==$_POST['username'])
	{
		if($rs[0]['password'] == md5($_POST['password']))
		{
			$_SESSION['user'] = $rs[0]['username'];
			$_SESSION['userlevel'] = $rs[0]['type'];
			die('3');
		}
		else
		{
			die('2');	
		}
	}
	else
	{
		die('1');
	}
}

function logout()
{
	unset($_SESSION['user']);	
}
?>