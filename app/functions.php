<?php 

	// Get all products 
	function get_all_products() {
		include 'products.php';
		return $products;
	}


	// Format price
	function format_price($number , $symbol = 'S/') {
		if(!is_float($number) && !is_integer($number) ) {
			$number = 0;
		}

		return $symbol . ' ' . number_format($number, 2, '.', "'");
	}


	// Get product by id 
	function get_product_by_id( $id ) {
		include 'products.php';
		foreach ($products as $index => $product) {
			if($product['id'] == $id ) {
				return $products[$index];
			}
		}

		return false; 
	}

	// Cart 

	function get_cart() {
		if(isset($_SESSION['cart'])) {
			$_SESSION['cart']['cart_totals'] = calculate_totals();
			return $_SESSION['cart'];
		}

		$cart = 
		[
			'productos'   => [],
			'cart_totals' => calculate_totals()
		];

		$_SESSION['cart'] = $cart; 
		return $cart;
	}

	// Calculate totals
	function calculate_totals() {
		if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['productos']) ) {
			$cart_totals =
			[
				'subtotal' => 0,
				'envio'    => 0,
				'total'    => 0
			];

			return $cart_totals;

		}

		// Si existe carro o hay producto cuenta que si hay y loopeamos
		$subtotal = 0;
		$envio    = 99.9;
		$total    = 0;

		foreach($_SESSION['cart']['productos'] as $product ) {
			$_subtotal = $product['precio'] * $product['cantidad'];
			$subtotal  = floatval($subtotal + $_subtotal);
		}

		$total         = floatval($subtotal + $envio);
		$cart_totals =
		[
			'subtotal' => $subtotal,
			'envio'    => $envio,
			'total'    => $total
		];

		return $cart_totals;
	}


	// Add product to cart
	function add_product($id , $cantidad = 1) {
		$one_product = get_product_by_id( $id );

		$new_product = 
		[
			'id'       => NULL,
			'codigo'   => NULL,
			'nombre'   => NULL,
			'precio'   => NULL,
			'cantidad' => NULL,
			'imagen'   => NULL
		];

		if(!$one_product) {
			return false; 
		}

		$new_product = 
		[
			'id'       => $one_product['id'],
			'codigo'   => $one_product['codigo'],
			'nombre'   => $one_product['nombre'],
			'precio'   => $one_product['precio'],
			'cantidad' => $cantidad,
			'imagen'   => $one_product['imagen']
		];

		// Si es que no existe carrito o si hay pero está vacío
		if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['productos']) ) {
			$_SESSION['cart']['productos'][] = $new_product;

			return true;
		}

		// En caso de haber loopeamos
		foreach($_SESSION['cart']['productos'] as $index => $producto){
			if($producto['id'] == $id) {
				$producto['cantidad']                  = $producto['cantidad'] + $cantidad;
				$_SESSION['cart']['productos'][$index] = $producto; 

				return true;
			}
		}

		$_SESSION['cart']['productos'][] = $new_product;
		return true;
	}


	// Delete product
	function delete_product($id) {
		if(!isset($_SESSION['cart']) || empty($_SESSION['cart']['productos']) ) {
			return false; 
		}


		foreach($_SESSION['cart']['productos'] as $index => $producto) {
			if($producto['id'] == $id) {
				unset($_SESSION['cart']['productos'][$index]);
				return true;
			}
		}

	}

	// Update input

	function update_input($id, $cantidad) {
		
		foreach($_SESSION['cart']['productos'] as $index => $producto) {
			if($producto['id'] ==  $id ) {
				if($producto['cantidad'] !== $cantidad ) {
					$producto['cantidad']                  = $cantidad;
					$_SESSION['cart']['productos'][$index] = $producto;
					return true;
				}
			}
		}
	}

	// Vaciar carro
	function varciar_carro() {
		unset($_SESSION['cart']);
		return true;
	}