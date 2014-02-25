$(document).ready(function(){
	loadGrid(1);
	$("#unit_biro").change(function(){
		getBagian($(this).val());
	})
	$("#btnSetRangeAbsen").click(function(){
		var selectedId = $(this).attr("selectedId");
		//alert(selectedId)
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data pegawai yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-post.php?f=getSetRangeAbsen",
				type: "POST",
				datatype: "JSON",
				data: {nip: selectedId},
				success: function(hasil)
				{
				 	$("#SetRangeAbsenForm").html(hasil);
					$("#tanggalMulai").datepicker({dateFormat: "dd-mm-yy"});
					$("#tanggalAkhir").datepicker({dateFormat: "dd-mm-yy"});
				}
			})
			$("#SetRangeAbsenForm").dialog({
				modal: true,
				title: "Set Range Absensi",
				width: 600,
				height: 200,
				resizable: false
			});
		}
	})
	$("#btnEdit").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data pegawai yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-post.php?f=getDataPegawai",
				type: "POST",
				datatype: "JSON",
				data: {nip: selectedId},
				success: function(hasil)
				{
					$("#pegawaiForm").html(hasil)
					
				}
			})
			$("#pegawaiForm").dialog({
				modal: true,
				title: "Ubah Data Pegawai",
				width: 600,
				height: 200,
				resizable: false
			});
		}
	})
})

function loadGrid(page, qtype, query)
{
	var eselon_2 = $("#unit_biro").val();
	var eselon_3 = $("#unit_bagian").val();
	$(".maingrid tr").each(function(){
		$(this).remove()
	})
	$(".maingrid tfoot").remove();
	var loader = '<tr id="loader"><td colspan="4">Memuat data, harap tunggu.... <img src="asset/img/grid/load.gif" ></td></tr>';
	$(loader).insertAfter("#gridColumn");
	$.ajax({
		url: "ajax/ajax-post.php?f=getPegawai",
		type: "POST",
		data: {page:page, qtype: qtype, query: query, eselon_2:eselon_2, eselon_3: eselon_3},
		success: function(hasil)
		{
			$(".maingrid #loader").remove();
			$(hasil).insertAfter("#gridColumn");
		}
	})
}

function cariPegawai()
{
	var qtype = $("#qtype").val();
	var query = $("#query").val();
	loadGrid(1, qtype, query);
}

function goToPage(toPage)
{
	var qtype = $("#qtype").val();
	var query = $("#query").val();
	loadGrid(toPage, qtype, query);
}

function selectRow(rowIndex, selectedRow)
{
	$("#btnEdit").attr("selectedId", selectedRow);
	$("#btnHapus").attr("selectedId", selectedRow);
	$("#btnSetRangeAbsen").attr("selectedId", selectedRow);
	$(".maingrid tr").each(function(){
		$(this).removeClass("selected");
	});
	$(".maingrid #"+rowIndex).addClass("selected");
}

function getBagian(biro)
{
	$.ajax({
		url: "ajax/ajax-post.php?f=getBagianCombo",
		data: {biro: biro},
		type: "POST",
		success: function(hasil)
		{
			$("#unit_bagian").html(hasil);
		}
	})
}

function simpanPegawai(nip)
{
	var biro = $("#unit_biro").val();
	var bagian = $("#unit_bagian").val();
	var subbagian = $("#unit_subbagian").val();
	$.ajax({
		url: "ajax/ajax-crud.php?f=simpanDataPegawai",
		data: {nip: nip, unit_biro: biro, unit_bagian: bagian, unit_subbagian: subbagian},
		type: "POST",
		success: function(hasil)
		{
			$("#pegawaiForm").dialog("close");
			cariPegawai();
		}
	})
}

function simpanRangeAbsen(nip)
{
	var nip = nip;
	var keterangan = $("#keterangan").val();
	var tanggalMulai = $("#tanggalMulai").val();
	var tanggalAkhir = $("#tanggalAkhir").val();
	var CardNo = $("#CardNo").val();
	$.ajax({
		url: "ajax/ajax-crud.php?f=simpanDataRangeAbsen",
		data: {nip: nip, CardNo: CardNo, keterangan: keterangan, tanggalMulai: tanggalMulai, tanggalAkhir: tanggalAkhir},
		type: "POST",
		success: function(hasil)
		{
			$("#SetRangeAbsenForm").dialog("close");
			alert('Data tersimpan');
			cariPegawai();
		}
	})
}