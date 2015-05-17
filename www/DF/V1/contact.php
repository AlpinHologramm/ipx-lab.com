<?php
  session_start();
  ?>
  <!doctype html>
  <html>
  <head>
  <meta charset="utf-8">
  <title>Formulaire de contact - Version minimale</title>
  <!-- call bootstrap -->
  <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">

</head>


  <div class="row">
  <div class="col-md-12">
  <p><b>INFO:</b> La newsletter est publiée deux fois par mois. </p>
<hr>
  </div>
  </div>
  </div>
<!-- CONTENT -->
  <div class="container">
  <?php if(array_key_exists('errors',$_SESSION)): ?>
  <div class="alert alert-danger">
  <?= implode('<br>', $_SESSION['errors']); ?>
  </div>
  <?php endif; ?>
  <?php if(array_key_exists('success',$_SESSION)): ?>
  <div class="alert alert-success">
  Votre email à bien été transmis !
  </div>
  <?php endif; ?>
<form action="send_form.php" method="post">
  <div class="row">
<div class="col-md-6">
  <div class="form-group">
  <label for="inputname">Votre nom</label>
  <input required type="text" name="name" class="form-control" id="inputname" value="<?php echo isset($_SESSION['inputs']['name'])? $_SESSION['inputs']['name'] : ''; ?>">
  </div><!--/*.form-group-->
  </div><!--/*.col-md-6-->
<div class="col-md-6">
  <div class="form-group">
  <label for="inputemail">Votre email</label>
  <input required type="email" name="email" class="form-control" id="inputemail" value="<?php echo isset($_SESSION['inputs']['email'])? $_SESSION['inputs']['email'] : ''; ?>">
  </div><!--/*.form-group-->
  </div><!--/*.col-md-6-->
<div class="col-md-12">
  <div class="form-group">
  <label for="inputmessage">Votre message</label>
  <textarea required id="inputmessage" name="message" class="form-control"><?php echo isset($_SESSION['inputs']['message'])? $_SESSION['inputs']['message'] : ''; ?></textarea>
  </div><!--/*.form-group-->
  </div><!--/*.col-md-12-->
<div class="col-md-12"></div><!--/*.col-md-12-->
<div class="col-md-12">
  <button type='submit' class='btn btn-primary'>Envoyer</button>
  </div><!--/*.col-md-12-->
</div><!--/*.row-->
  </form>
</div><!--/*.container-->
  <!-- END CONTENT -->
</body>
  </html>
  <?php
unset($_SESSION['inputs']); // on nettoie les données précédentes
  unset($_SESSION['success']);
  unset($_SESSION['errors']);