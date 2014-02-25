$(document).ready(function(){
	$( "#form" ).accordion({autoHeight: false});
	$( "#form" ).css({"width":"100%", "height":"auto"})
	$("#save_time").click(function(){
		var masuk_start = $("#h_masuk1").val()+":"+$("#m_masuk1").val()+":"+$("#s_masuk1").val();
		var keluar_start = $("#h_keluar1").val()+":"+$("#m_keluar1").val()+":"+$("#s_keluar1").val();
		var masuk_end = $("#h_masuk2").val()+":"+$("#m_masuk2").val()+":"+$("#s_masuk2").val();
		var keluar_end = $("#h_keluar2").val()+":"+$("#m_keluar2").val()+":"+$("#s_keluar2").val();
		$.ajax({
			url: "ajax/ajax-crud.php?f=simpanTimeSetting",
			type: "post",
			data: {masuk_start: masuk_start, keluar_start: keluar_start, masuk_end: masuk_end, keluar_end: keluar_end},
			success: function(hasil)
			{
				location.reload(true);
			}
		})
	})
	loadGrid(1);
	loadGrid2(1);
	$("#btnEdit").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-post.php?f=getDataHariLibur",
				type: "POST",
				datatype: "JSON",
				data: {id: selectedId, edit: true},
				success: function(hasil)
				{
					$("#pegawaiForm").html(hasil)
					$("#tanggal").datepicker({ dateFormat: 'dd-mm-yy' });
				}
			})
			$("#pegawaiForm").dialog({
				modal: true,
				title: "Ubah Data Hari Libur",
				width: 600,
				height: 200,
				resizable: false
			});
		}
	})
	
	$("#btnDelete").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-crud.php?f=hapusHariLibur",
				data: {id: selectedId},
				type: "POST",
				success: function(hasil)
				{
					cariHariLibur();
				}
			})
		}
	})
	
	$("#btnAdd").click(function(){
		$.ajax({
			url: "ajax/ajax-post.php?f=getDataHariLibur",
			type: "POST",
			datatype: "JSON",
			data: {id: "", add: true},
			success: function(hasil)
			{
				$("#pegawaiForm").html(hasil)
				$("#tanggal").datepicker({ dateFormat: 'dd-mm-yy' });
				
			}
		})
		$("#pegawaiForm").dialog({
			modal: true,
			title: "Tambah Data Hari Libur",
			width: 600,
			height: 200,
			resizable: false
		});
	})
	
	$("#btnEditCuti").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-post.php?f=getDataCutiBersama",
				type: "POST",
				datatype: "JSON",
				data: {id: selectedId, edit: true},
				success: function(hasil)
				{
					$("#pegawaiForm").html(hasil)
					$("#tanggal").datepicker({ dateFormat: 'dd-mm-yy' });
				}
			})
			$("#pegawaiForm").dialog({
				modal: true,
				title: "Ubah Data Cuti Bersama",
				width: 600,
				height: 200,
				resizable: false
			});
		}
	})
	
	$("#btnAddCuti").click(function(){
		$.ajax({
			url: "ajax/ajax-post.php?f=getDataCutiBersama",
			type: "POST",
			datatype: "JSON",
			data: {id: "", add: true},
			success: function(hasil)
			{
				$("#pegawaiForm").html(hasil)
				$("#tanggal").datepicker({ dateFormat: 'dd-mm-yy' });
				
			}
		})
		$("#pegawaiForm").dialog({
			modal: true,
			title: "Tambah Data Cuti Bersama",
			width: 600,
			height: 200,
			resizable: false
		});
	})
	
	$("#btnDeleteCuti").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-crud.php?f=hapusCutiBersama",
				data: {id: selectedId},
				type: "POST",
				success: function(hasil)
				{
					//$("#pegawaiForm").dialog("close");
					cariCutiBersama();
				}
			})
		}
	})
	
	$("#btnLoad").click(function(){
		$("#loadMsg").html("<div>Loading Data, please wait ... </div><img src='asset/img/grid/load.gif'>");
		$.ajax({
			url: "ajax/ajax-import.php",
			type: "post",
			success: function(hasil)
			{
				$("#loadMsg").html("Load data success");
				setInterval("$('loadMsg').html('')", 3000);
			}
		})
	})
})

function loadGrid(page, qtype, query)
{
	$(".maingrid tr").each(function(){
		$(this).remove()
	})
	$(".maingrid tfoot").remove();
	var loader = '<tr id="loader"><td colspan="4">Memuat data, harap tunggu.... <img src="asset/img/grid/load.gif" ></td></tr>';
	$(loader).insertAfter(".maingrid #gridColumn");
	$.ajax({
		url: "ajax/ajax-post.php?f=getHariLibur",
		type: "POST",
		data: {page:page, qtype: qtype, query: query},
		success: function(hasil)
		{
			$(".maingrid #loader").remove();
			$(hasil).insertAfter(".maingrid #gridColumn");
		}
	})
}

function loadGrid2(page, qtype, query)
{
	$(".maingridcuti tr").each(function(){
		$(this).remove()
	})
	$(".maingridcuti tfoot").remove();
	var loader = '<tr id="loader"><td colspan="4">Memuat data, harap tunggu.... <img src="asset/img/grid/load.gif" ></td></tr>';
	$(loader).insertAfter(".maingridcuti #gridColumn");
	$.ajax({
		url: "ajax/ajax-post.php?f=getCutiBersama",
		type: "POST",
		data: {page:page, qtype: qtype, query: query},
		success: function(hasil)
		{
			$(".maingridcuti #loader").remove();
			$(hasil).insertAfter(".maingridcuti #gridColumn");
		}
	})
}

function cariHariLibur()
{
	var qtype = $("#qtype").val();
	var query = $("#query").val();
	loadGrid(1, qtype, query);
}

function cariCutiBersama()
{
	var qtype = $("#qtype").val();
	var query = $("#query").val();
	loadGrid2(1, qtype, query);
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
	$("#btnDelete").attr("selectedId", selectedRow);
	$(".maingrid tr").each(function(){
		$(this).removeClass("selected");
	});
	$(".maingrid #"+rowIndex).addClass("selected");
}

function goToPage2(toPage)
{
	var qtype = $("#qtype").val();
	var query = $("#query").val();
	loadGrid2(toPage, qtype, query);
}

function selectRow2(rowIndex, selectedRow)
{
	$("#btnEditCuti").attr("selectedId", selectedRow);
	$("#btnDeleteCuti").attr("selectedId", selectedRow);
	$(".maingridcuti tr").each(function(){
		$(this).removeClass("selected");
	});
	$(".maingridcuti #"+rowIndex).addClass("selected");
}

function simpanHariLibur(id, type)
{
	var tanggal = $("#tanggal").val();
	var keterangan = $("#keterangan").val();
	var level = $("#level").val();
	$.ajax({
		url: "ajax/ajax-crud.php?f=simpanHariLibur",
		data: {id: id, tanggal: tanggal, keterangan: keterangan},
		type: "POST",
		success: function(hasil)
		{
			$("#pegawaiForm").dialog("close");
			cariHariLibur();
		}
	})
}

function simpanCutiBersama(id, type)
{
	var tanggal = $("#tanggal").val();
	var keterangan = $("#keterangan").val();
	var level = $("#level").val();
	$.ajax({
		url: "ajax/ajax-crud.php?f=simpanCutiBersama",
		data: {id: id, tanggal: tanggal, keterangan: keterangan},
		type: "POST",
		success: function(hasil)
		{
			$("#pegawaiForm").dialog("close");
			cariCutiBersama();
		}
	})
}