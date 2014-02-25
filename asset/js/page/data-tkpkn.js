$(document).ready(function(){
	loadGrid(1, $("#tanggal").val());
	$("#tanggal").datepicker({dateFormat: 'dd-mm-yy'})
	getBagian($("#unit_biro").val());
	$("#unit_biro").change(function(){
		getBagian($(this).val());
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
		url: "ajax/ajax-post.php?f=getTKPKN",
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