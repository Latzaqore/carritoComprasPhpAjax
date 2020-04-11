<?php
	
	session_start();
	include 'app/functions.php';

	switch ($_POST['action']) {
		case 'POST':
			$cart = get_cart();
			$html = '';

			if(!empty($cart['productos'])) {
				$html .= '<div class="table-responsive">
					<table class="table table-striped table-sm">
						<thead class="text-center">
							<tr>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Subtotal</th>
								<th></th>
							</tr>
						</thead>

						<tbody class="text-center">';
						foreach($cart['productos'] as $producto):
							$html .= '<tr><td class="align-middle">
								'. $producto['nombre'] .'
								<small class="d-block text-muted">COD '. $producto['codigo'] .'</small>
							</td>

							<td class="align-middle" width="4%;">
								<input type="text" data-id="'. $producto['id'] .'" class="form-control form-control-sm text-center input_cantidad" value="'. $producto['cantidad'] .'">
							</td>

							<td class="align-middle">'. format_price( floatval($producto['cantidad'] * $producto['precio']) ) .'</td>

							<td class="align-middle"><span data-id="'. $producto['id'] .'" class="fas fa-times text-danger btn_detele_product" style="cursor: pointer"></span></td></tr>';
						endforeach;
					 $html .= '</tbody>
					</table>

					<button class="btn btn-danger btn-sm btn_vaciar_carro">Vaciar carro</button>
				</div>';
			} else {
				$html .= '<div class="text-center">
				<img class="img-fluid" src="assets/images/cart_empty.png" style="width: 190px; height: 170px">
				<p class="text-muted">El carrito está vacío</p>
				</div>';
			}

			$html     .= '<div class="table-responsive mt-4">
					<table class="table table-sm">
						<tr>
							<th class="border-0">Subtotal</th>
							<td class="text-right border-0 text-success" style="font-size: 17px;">'. format_price($cart['cart_totals']['subtotal']).'</td>
						</tr>

						<tr>
							<th>Envío</th>
							<td class="text-right text-success" style="font-size: 17px;">'. format_price($cart['cart_totals']['envio']).'</td>
						</tr>

						<tr>
							<th style="font-size: 18px;">Total</th>
							<td class="text-right font-weight-bold text-success" style="font-size: 22px">'. format_price($cart['cart_totals']['total']).'</td>
						</tr>
					</table>
				</div>

				<div>
					<h3>Completa el formulario</h3>
					<form id="formulario">
						<div class="form-group">
							<label for="nombres" class="col-form-label col-form-label-sm">Nombres y apellidos</label>
							<input type="text" id="nombres" class="form-control form-control-sm" name="nombres" placeholder="Ingrese su nombre">
						</div>

						<div class="form-group row">
							<div class="col-6">
								<label for="dni" class="col-form-label col-form-label-sm">DNI:</label>
								<input type="text" class="form-control form-control-sm" name="dni" placeholder="Ingrese su DNI">
							</div>

							<div class="col-6">
								<label for="email" class="col-form-label col-form-label-sm">E-mail:</label>
								<input type="text" class="form-control form-control-sm" name="email" placeholder="Ingrese su correo electrónico">
							</div>

						</div>';
					if(empty($cart['productos'])){
						$html .= '<div class="form-group">
							<button class="btn btn-info btn-block btn_pagar" disabled>Pagar ahora</button>
						</div>';
					} else {
						$html .= '<div class="form-group">
							<button class="btn btn-info btn-block btn_pagar">Pagar ahora</button>
						</div>';
					}
					$html .= '</form>
				</div>'; 

			echo json_encode(['status' => true, 'data' => $html]);
			break;

		case 'ADD':
		if(!isset($_POST['id_producto'] , $_POST['cantidad'] ) ) {
			echo json_encode(['status'  => false]);
		}

		if(!add_product( (int) $_POST['id_producto'], (int) $_POST['cantidad'] )) {
			echo json_encode(['status' => false]);
		}

		echo json_encode(['status' => true]);
		break;
		

		case 'DELETED':
		if(!delete_product( (int) $_POST['id'] )) {
			echo json_encode(['status' => false]);
		}

		else {
			echo json_encode(['status' => true]);
		}
		break;


		case 'DESTRUIR':
		if(varciar_carro()) {
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false]);
		}
		break;	


		case 'UPDATED':
		if(!update_input( (int) $_POST['id'] , (int) $_POST['cantidad'] )){
			echo json_encode(['status' => false]);
		}

		echo json_encode(['status' => true]);
		break;


		case 'PAGAR':

		parse_str($_POST['formulario'], $_POST);

		$nombres = $_POST['nombres'];
		$dni     = $_POST['dni'];
		$email   = $_POST['email'];


		$cliente = 
		[
			'nombres' => $nombres,
			'dni'     => $dni,
			'email'   => $email
		];

		$_SESSION['cart']['cliente'] = $cliente; 

		$cart_resumen = $_SESSION['cart'];

		$data_modal = 
			'<div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal_resumen" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">Resumen de compra</h5>
		        <button type="button" class="close btn_close_modal" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <div class="text-center">
		        	<img class="img-fluid" src="assets/images/shopping_basket.jpg" style="width: 150px; height 150px">
		        </div>
		        <p class="d-inline">Gracias por tu compra: '. $cart_resumen['cliente']['nombres'] .'</p>

		        <table class="table table-hover table-striped table-sm">
		        	<thead class="text-center">
						<th class="text-left">Producto</th>
						<th class="text-center">Cantidad</th>
						<th class="text-right">Subtotal</th>
					</thead>

					<tbody>';
					foreach($cart_resumen['productos'] as $producto ):
					$data_modal .='
						<tr>
							<td class="text-left">'. $producto['nombre'] .'</td>
							<td class="text-center">'. $producto['cantidad'] .'</td>
							<td class="text-right">'. format_price( floatval($producto['cantidad'] * $producto['precio']) ) .'</td>
						</tr>';
					endforeach;
					$data_modal .= '
				<tr>
					<td class="text-left" colspan="2">Subtotal</td>
					<td class="text-right" colspan="1">'. format_price( floatval($cart_resumen['cart_totals']['subtotal']) ) .'</td>
				</tr>

				<tr>
					<td class="text-left" colspan="2">Envío</td>
					<td class="text-right" colspan="1">'. format_price( floatval($cart_resumen['cart_totals']['envio']) ) .'</td>
				</tr>

				<tr>
					<td class="text-left" colspan="2">Total</td>
					<td class="text-right font-weight-bold" colspan="1">'. format_price( floatval($cart_resumen['cart_totals']['total']) ) .'</td>
				</tr>

				</tbody>
		        </table>
		      </div>
		    </div>
		  </div>
		</div>';

		echo json_encode(['status' => true, 'data' => $data_modal]);
		break;


		case 'DESTRUIRCARRO':
		if(varciar_carro()) {
			echo json_encode(['status' => true]);
		}
		break;


		default:
			
			break;
	}