<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Latza shop</title>
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/bootswatch/bootstrap.css">
  <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/waitMe/waitMe.min.css">


  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <script src="assets/waitMe/waitMe.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

</head>
<body>

  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container m-0">
        <a href="#" class="navbar-brand">Latza shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="https://blog.bootswatch.com">
                Carrito <span class="fas fa-shopping-cart"></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 col-xl-8">
				<h2>Productos</h2>

				<div class="row justify-content-center">
					<div class="col-6 col-md-4 col-xl-3 mb-2">
						<div class="card" style="width: 11rem;">
							<img src="assets/images/<?php echo $product['imagen']; ?>" class="card-img-top" alt="...">
							<div class="card-body">
								<span class="float-left font-weight-bold text-primary text-truncate">
									Nombre product
								</span><br>
					
								<small class="font-weight-bold">S/ 199.99</small> 

								<button class="btn btn-success btn-sm float-right btn_add_product">
									Agregar al carro <span class="fas fa-plus"></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-xl-4">
				<h2>Carrito</h2>
				
				<div id="wrapper">
								
				</div>

			</div>

		<div id="modal_pago">
			
		</div>


		</div>
	</div>

<?php include 'includes/inc_footer.php'; ?>