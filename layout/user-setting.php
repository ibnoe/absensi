<?php
@session_start();
if($_SESSION['userlevel']!="admin")
{
	die("Fitur ini hanya dapat digunakan oleh pengguna dengan hak akses administrator.");	
}
?>
<script>
$(document).ready(function(){
	loadGrid(1);
	$("#btnEdit").click(function(){
		var selectedId = $(this).attr("selectedId");
		if(selectedId == undefined)
		{
			alert("Anda belum memilih data pengguna yang akan dirubah datanya.")
		}
		else
		{
			$.ajax({
				url: "ajax/ajax-post.php?f=getDataPengguna",
				type: "POST",
				datatype: "JSON",
				data: {id: selectedId, edit: true},
				success: function(hasil)
				{
					$("#pegawaiForm").html(hasil)
					
				}
			})
			$("#pegawaiForm").dialog({
				modal: true,
				title: "Ubah Data Pengguna",
				width: 600,
				height: 200,
				resizable: false
			});
		}
	})
	
	$("#btnAdd").click(function(){
		$.ajax({
			url: "ajax/ajax-post.php?f=getDataPengguna",
			type: "POST",
			datatype: "JSON",
			data: {id: "", add: true},
			success: function(hasil)
			{
				$("#pegawaiForm").html(hasil)
				
			}
		})
		$("#pegawaiForm").dialog({
			modal: true,
			title: "Tambah Data Pengguna",
			width: 600,
			height: 200,
			resizable: false
		});
	})
})

function loadGrid(page, qtype, query)
{
	$(".maingrid tr").each(function(){
		$(this).remove()
	})
	$(".maingrid tfoot").remove();
	var loader = '<tr id="loader"><td colspan="4">Memuat data, harap tunggu.... <img src="asset/img/grid/load.gif" ></td></tr>';
	$(loader).insertAfter("#gridColumn");
	$.ajax({
		url: "ajax/ajax-post.php?f=getPengguna",
		type: "POST",
		data: {page:page, qtype: qtype, query: query},
		success: function(hasil)
		{
			$(".maingrid #loader").remove();
			$(hasil).insertAfter("#gridColumn");
		}
	})
}

function cariPengguna()
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
	$(".maingrid tr").each(function(){
		$(this).removeClass("selected");
	});
	$(".maingrid #"+rowIndex).addClass("selected");
}

function simpanPengguna(userid, type)
{
	var username = $("#username").val();
	var password_1 = $("#password_1").val();
	var password_2 = $("#password_2").val();
	var level = $("#level").val();
	if (password_1 == password_2)
	{
		$.ajax({
			url: "ajax/ajax-crud.php?f=simpanDataPengguna",
			data: {id: userid, username: username, password: password_1, level: level},
			type: "POST",
			success: function(hasil)
			{
				$("#pegawaiForm").dialog("close");
				cariPengguna();
			}
		})
	}
	else
	{
		$("#formMsg").html("Password 1 dan password 2 tidak sama!");
		setInterval('$("#formMsg").html("")', 3000);
	}
}
</script>
<br /><br />
<table width="80%" cellspacing="0" cellpadding="0" class="maingrid">
	<thead>
    	<td colspan="3" class="gridheader1">
        	<input type="button" id="btnEdit" value="Edit Data Pengguna" />
            &nbsp;
            <input type="button" id="btnAdd" value="Tambah Data Pengguna" />
            &nbsp;
            <input type="button" id="btnDelete" value="Hapus Data Pengguna" />
        </td>
    </thead>
	<thead align="center" id="gridColumn">
    	<td width="10px" class="gridheader">No.</td>
        <td width="100px" class="gridheader">Username</td>
        <td width="150px" class="gridheader">Level</td>
    </thead>
</table>
<div id="pegawaiForm" style="display:none"></div>




