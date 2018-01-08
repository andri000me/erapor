<script type="text/javascript">
	$(document).ready(function() {
		manageGuru();
	});

	function manageGuru(){
		$('.main-container').html('<?php echo $this->load->view('admin/guru/table', '', TRUE); ?>');
		refreshTabelGuru();
	}

	function refreshTabelGuru(){
		$('#tabel-guru').DataTable({
			destroy: true,
			ajax : '<?php echo base_url('guru/getAllGuru'); ?>' , 
			deferRender: true,
			columns: [
				{data: 'nama_guru'},
				{data: 'nik'},
				{data: 'jk_guru'},
				{data: 'telp_guru'},
				{data: 'alamat_guru'}
			],
			columnDefs: [
				{
					targets: 2,
					render: function(data, type, full){
						return jk[data];
					}
				},{
					data:'id_guru',
					targets: 5,
					render: function(data, type, full) {
					return '<button onclick="showModalMapelGuru('+data+')" data-target="#modal" data-toggle="modal" class="btn btn-warning"><i class="fa fa-eye"></i>&nbsp;Lihat</button>';
					}
				},
				{
					targets: 6,
					data: 'id_guru',
					render: function(data, type, full){
						return '<div class="d-flex">\
							<button onclick="showModalEditGuru('+data+')" data-target="#modal" data-toggle="modal" class="btn-primary text-white mr-1"><i class="fa fa-pencil"></i>&nbsp;Edit </button>\
							<button onclick="showModalDeleteGuru('+data+')" data-target="#modal" data-toggle="modal" class="btn-danger text-white"> <i class="fa fa-trash"></i></button>\
							</div>';
					}
				}
			]
		});
	}

	function showModalMapelGuru(id) {
		body = '<div class="row">\
				  <div class="col-sm-12">\
				    <div class="form-group">\
				      <select name="mapel[]" class="form-control select2" multiple="multiple" id="mapel"></select>\
				    </div>\
				  </div>\
				</div>';
		updateModal('List Mapel', body, '<?php echo base_url('mapel_guru/editMapel'); ?>', 'editMapelGuru', id, 'md', 'warning');

		refreshPilihanMapel(id);
	}

	function editMapelGuru(id) {
		event.preventDefault();
		$.ajax({
			url: $('.modal-form').attr('action'),
			type: 'POST',
			dataType: 'json',
			data: $('.modal-form').serialize()+'&id='+id,
			success: function(r) {
				if (r.status) {
			    	toastr.remove();
			      	toastr["success"]("Data mapel berhasil diedit");
			      	refreshTabelGuru();
			      	$('.modal').modal('hide');
			    } else {
			      	toastr.remove();
			     	toastr["error"](r.error);
			    }
			}
		});
	}

	function refreshPilihanMapel(id) {
		$.ajax({
			url: '<?php echo base_url('mapel/getAllMapel'); ?>',
			type: 'GET',
			dataType: 'json',
			success: function(r) {
				html = '';
				r = groupBy(r.data,'nama_jenis_mapel');
				$.each(r, function(key,data) {
					html += '<optgroup label="'+key+'">';
					$.each(data, function(key,data) {
						html += '<option value="'+data.id_mapel+'">'+data.nama_mapel+'</option>';
					});
					html += '</optgroup>';
				});
				$('#mapel').html(html);
				$('.select2').select2();
  				$('.select2').css('width','100%');

  				$.ajax({
  					url: '<?php echo base_url('mapel_guru/getMapelByGuruId') ?>',
  					type: 'GET',
  					dataType: 'json',
  					data: 'id='+id,
  					success: function(r) {
  						val = [];
  						$.each(r, function(key,data) {
  							val.push(data.id_mapel);
  						});
  						$('#mapel').val(val).trigger('change');
  					}
  				});
			}
		});
	}

	function showModalAddGuru(){
		body = '<?php echo $this->load->view('admin/guru/modal_body', '', TRUE); ?>';
			updateModal('Tambah Guru', body, '<?php echo base_url('guru/addGuru'); ?>', 'addGuru', null, 'md', 'success');
	}

	function showModalEditGuru(idGuru){
		body = '<?php echo $this->load->view('admin/guru/modal_body', '', TRUE); ?>';
		updateModal('Edit Guru', body, '<?php echo base_url('guru/editGuru'); ?>', 'editGuru', idGuru, 'md', 'primary');

		setTimeout(function(){
			$.ajax({
				url: '<?php echo base_url('guru/getGuruById'); ?>',
				type: 'GET',
				dataType: 'json',
				data: 'id='+idGuru,
				success: function(r){
					$('#nik').val(r.nik);
					$('#nama').val(r.nama_guru);
					$('#jk_guru').val(r.jk_guru);
					$('#telepon').val(r.telp_guru);
					$('#alamat').val(r.alamat_guru);
				}
			});
		}, 100);
	}

	function showModalDeleteGuru(idGuru){
		updateModal('Delete Guru?', '', '<?php echo base_url('guru/deleteGuru'); ?>', 'deleteGuru', idGuru, 'sm', 'danger', 'Yes');
	}

	function addGuru(){
		event.preventDefault();
		$.ajax({
			url: $('.modal-form').attr('action'),
			type: 'POST',
			dataType: 'json',
			data: $('.modal-form').serialize(),
			success: function(r) {
				if(r.status){
					toastr.remove();
					toastr["success"]("Data guru berhasil ditambahkan");
					refreshTabelGuru();
					$('.modal').modal('hide');
				}else{
					toastr.remove();
					toastr["error"](r.error);
				}
			}
		});
	}

	function editGuru(idGuru){
		event.preventDefault();
		$.ajax({
			url: $('.modal-form').attr('action'),
			type: 'POST',
			dataType: 'json',
			data: $('.modal-form').serialize()+'&id='+idGuru,
			success: function(r){
				if (r.status) {
					toastr.remove();
					toastr["success"]("Data Guru berhasil diedit");
					refreshTabelGuru();
					$('.modal').modal('hide');
				}else{
					toastr.remove();
					toastr["error"](r.error);
				}
			}
		});
	}

	function deleteGuru(idGuru){
		event.preventDefault();
		$.ajax({
			url: '<?php echo base_url('guru/deleteGuru'); ?>',
			type: 'POST',
			dataType: 'json',
			data: 'id='+idGuru,
			success: function(r){
				if (r.status) {
					toastr.remove();
					toastr["success"]("Data guru berhasil dihapus");
					refreshTabelGuru();
					$('.modal').modal('hide');
				} else {
					toastr.remove();
					toastr["error"](r.error);						
				}
			}
		});
	}

</script>