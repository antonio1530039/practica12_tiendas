<?php
  //instancia de la clase controlador
  $controller_dashboard = new MVC();
  //se verifica que se haya iniciado sesion
  $controller_dashboard->verificarLoginController();

?>

<head>
    <title>Dashboard</title>
  </head>
  <body>
  <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6 ">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo $controller_dashboard->setQueryControllerGetNumber("productos", "stock", "<=",0); ?></h3>
                <p>Productos sin stock</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="index.php?action=movimiento_inventario" class="small-box-footer">Ir a Movimientos de Inventario <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?php echo $controller_dashboard->setQueryControllerGetNumber("productos", "deleted", "=",0); ?></h3>
                <p>Productos registrados</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="index.php?action=productos" class="small-box-footer">Ir a Gestión de Productos <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $controller_dashboard->setQueryControllerGetNumber("usuarios", "deleted", "=",0); ?></h3>
                <p>Usuarios registrados</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
          <a href="index.php?action=usuarios" class="small-box-footer">Ir a Gestión de Usuarios <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $controller_dashboard->setQueryControllerGetNumber("categorias", "deleted", "=",0); ?></h3>
                <p>Categorías registradas</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="index.php?action=categorias" class="small-box-footer">Ir a Gestión de Categorias <i class="fa fa-arrow-circle-right"></i></a>
             </div>
          </div>
          <!-- ./col -->
        </div>
                <div class="card-body">
                  <?php
                  $controller_dashboard->showDashboard(); 
                  ?>
                </div>
              </form>
            </div>    
        </div>
    </div>
  </div>
</section>
  </body>
