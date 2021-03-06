<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-user-image"><img src="<?php echo SERVERURL.'view/img/male-user.png'; ?>"></div>
              <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Iniciar Sesion</h1>
                  </div>
                <form class="formAjax form-group text-center user" action="<?php echo SERVERURL; ?>ajax/loginAjax.php" method="POST" data-form="login" autocomplete="off">

                    <div class="form-group">
                  <input type="text" class="form-control 
                  form-control-user
                  form-control-user" id="aliasUser" name="aliasUser" placeholder="Alias de Usuario"
                  required
                  minlength = '5' maxlength = '20'  

                  >
                    </div>
                    <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Contraseña"
                  required
                  minlength = '8' maxlength = '20'  
                    >



                    </div>

                  <button class="btn btn-primary btn-user btn-block" type="submit" value="loginSession" name="login">Ingresar</button>
                    
                    <div class="responseProcessAjax"></div>

                    <div class="msgBackendProcess"></div>

                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="<?php echo SERVERURL; ?>forgotPassword/">¿Se me olvido la contraseña?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="<?php echo SERVERURL; ?>registerUser/">Crear una cuenta</a>
                  </div>
  
                    <?php require "./view/inc/linkRestartUser.php"; ?>
      
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>
