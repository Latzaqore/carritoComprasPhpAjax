$(document).ready(function() {


	function load_cart() {
		var wrapper = $('#wrapper'),	
			action  = 'POST';

		$.ajax({
			url      : 'ajax.php',
			method   : 'POST',
			data     : { action : action },
			success  : function(r) {
				if(r.status) {
					wrapper.html(r.data);
				}
			},
			dataType : 'json'
		});

		return false;
	}

	load_cart();


	$('.btn_add_product').on('click', function(ebtn_add_product) {
		ebtn_add_product.preventDefault();

		var id_producto = $(this).data('id'),
			cantidad    = $(this).data('cantidad'),
			action      = 'ADD',
			wrapper     = $('#wrapper');

			$.ajax({
				url:  'ajax.php',
				method: 'POST',
				cache : false,
				data  : { id_producto, cantidad, action},
				success: function(radd){
					if(radd.status){
						load_cart();
					}
				},
				dataType: 'json'
			});

			return false;


	});


	$('body').on('click', '.btn_detele_product', function(ebtn_detele_product) {
		ebtn_detele_product.preventDefault();
		var     id = $(this).data('id'),
			action = 'DELETED';
		
		$.ajax({
				url   :  'ajax.php',
				method: 'POST',
				cache : false,
				data  : { id, action},
				success: function(rdeleted){
					if(rdeleted.status){
						load_cart();
					}
				},

				dataType: 'json'
			});

			return false;

	});

	//btn_vaciar_carro
		$('body').on('click', '.btn_vaciar_carro', function(ebtn_vaciar_carro) {
		ebtn_vaciar_carro.preventDefault();
		
		var pregunta = confirm('¿Está seguro que quiere vaciar el carro?');
		if(pregunta == false) {
			return 0;
		} 

		var action = 'DESTRUIR';
		
		$.ajax({
				url   :  'ajax.php',
				method: 'POST',
				cache : false,
				data  : { action:  action},
				success: function(rdestruir){
					if(rdestruir.status){
						load_cart();
					}
				},

				dataType: 'json'
			});

			return false;

	});

	$('body').on('click', '.btn_pagar', function(ebtn_pagar){
		ebtn_pagar.preventDefault();

		var formulario = $('#formulario').serialize(),
			action     = 'PAGAR',
			wrapper    = $('#wrapper');

			$.ajax({
				url   :  'ajax.php',
				method: 'POST',
				cache : false,
				data  : { formulario, action},
				success: function(rpagar){
					if(rpagar.status){
						wrapper.waitMe({
							effect   : 'ios',
							waitTime : 3000,
							onClose  : function() {
								$('#modal_pago').html(rpagar.data);
								$('#modal_resumen').modal('show');
							} 
						});
					}
				},

				dataType: 'json'
			});

			return false;

	});


	$('body').on('blur', '.input_cantidad', function() {
		var input    = $(this),
			id       = input.data('id'),
			cantidad = parseInt(input.val()),
			action   = 'UPDATED';

		if(Math.floor(cantidad) !== cantidad) {
			cantidad = 1;
		}

		if(cantidad == 0) {
			cantidad = 1;
		} else if(cantidad >= 99) {
			cantidad = 99;
		}

		$.ajax({
			url   :  'ajax.php',
			method: 'POST',
			cache : false,
			data  : { id, cantidad, action},
			success: function(rupdate){
				if(rupdate.status){
					load_cart();
				}
			},

			dataType: 'json'
		});

		return false;
	});


	$('body').on('click', '.btn_close_modal', function(ebtn_close_modal){
		ebtn_close_modal.preventDefault();
		var action = 'DESTRUIRCARRO';

		$.ajax({
			url   :  'ajax.php',
			method: 'POST',
			cache : false,
			data  : { action :  action},
			success: function(rdestruircarro){
				if(rdestruircarro.status){
					$('#modal_resumen').modal('hide');
					load_cart();
				}
			},

			dataType: 'json'
		});

		return false;	
	});

});