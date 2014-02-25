$(document).ready(function(){
	loadGrid(1, $("#tanggal").val());
	getBagian($("#unit_biro").val());
	$("#unit_biro").change(function(){
		getBagian($(this).val());
	})
	$("#tanggal").datepicker({dateFormat: 'dd-mm-yy'})
	$("#btnEdit").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data pegawai yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-post.php?f=getDataAbsensi",
				type: "POST",
				datatype: "JSON",
				data: {id: selectedId, tanggal: $("#tanggal").val()},
				success: function(hasil)
				{
					$("#pegawaiForm").html(hasil)
					
				}
			})
			$("#pegawaiForm").dialog({
				modal: true,
				title: "Ubah Data Absensi Pegawai",
				width: 300,
				height: 200,
				resizable: false
			});
		}
	})
})

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

function loadGrid(page, tanggal, qtype, query)
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
		url: "ajax/ajax-post.php?f=getAbsensi",
		type: "POST",
		data: {page:page, qtype: qtype, query: query, tanggal: tanggal, eselon_2:eselon_2, eselon_3: eselon_3},
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
	var tanggal = $("#tanggal").val();
	loadGrid(1, tanggal, qtype, query);
}

function goToPage(toPage)
{
	var qtype = $("#qtype").val();
	var query = $("#query").val();
	var tanggal = $("#tanggal").val();
	loadGrid(toPage, tanggal, qtype, query);
}

function selectRow(rowIndex, selectedRow)
{
	$("#btnEdit").attr("selectedId", selectedRow);
	$("#btnHapus").attr("selectedId", selectedRow);
	$(".maingrid tr").each(function(){
		$(this).removeClass("selected");
	});
	$(".maingrid #"+rowIndex).addClass("selected");
}

function simpanAbsensi(fscardno, tanggal)
{
	var h_masuk = $("#h_masuk").val()
	var m_masuk = $("#m_masuk").val()
	var s_masuk = $("#s_masuk").val()
	var h_keluar = $("#h_keluar").val()
	var m_keluar = $("#m_keluar").val()
	var s_keluar = $("#s_keluar").val()
	var keterangan = $("#keterangan").val()
	$.ajax({
		url: "ajax/ajax-crud.php?f=simpanDataAbsensi",
		data: {id: fscardno, tanggal: tanggal, h_masuk: h_masuk, m_masuk: m_masuk, s_masuk: s_masuk, h_keluar: h_keluar, m_keluar: m_keluar, s_keluar: s_keluar, keterangan: keterangan},
		type: "POST",
		success: function(hasil)
		{
			$("#pegawaiForm").dialog("close");
			cariPegawai(1, tanggal);
		}
	})
}