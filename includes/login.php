<?php if ( ! defined('CONFIF_FILE')) exit('No direct script access allowed');?>
<?php
if(!isset($_POST['user'], $_POST['password']))
{
    $message = '';
}else{
if ($_POST['user']==USER &&  $_POST['password']==PASSWORD){
    $_SESSION[AUTHENTICATION_SESSION_NAME] = true;
    header('Location: index.php');
}else{
    $message = '<div class="alert alert-danger">Please enter a valid username and password</div>';
}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Signin</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="robots" content="NOINDEX,NOFOLLOW,NONE,NOARCHIVE" />
<!-- jquery jquery-2.0.3.min.js not suppported by ie8-->
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<!-- /jquery-->

<!-- Boostrap-->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
<!-- /Boostrap-->
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
    var lang='<?php echo LANG ?>';
    var DEBUG_MODE = '<?php echo DEBUG_MODE; ?>';
$(document).ready(function(){
        i18n.init({
        lng: lang,
        debug: true,
        fallbackLng: 'en',
        load:'unspecific',
        resGetPath: "lng/__ns__-__lng__.json",
        ns: {
        namespaces: ['translation'],
        defaultNs: 'translation'
        }
    }, function(t) {
        $("#all").i18n();
    });
    });

</script>
<script type='text/javascript' src='js/i18next-1.7.3.min.js'></script>
</head>
<body id="all">

    <div class="container">
<div class="col-lg-4 col-md-4  col-sm-4 col-xs-4  col-lg-offset-4 col-md-offset-4 col-sm-offset-4 col-xs-offset-4">  
      <form role="form" action="" method="post">
        <h2 data-i18n='login.pleasesignin'></h2>
        <input type="email" name="user" class="form-control" placeholder="Email address" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <button class="btn btn-lg btn-primary btn-block" type="submit"  data-i18n='login.signin'></button>
        <?php echo $message ?>
      </form>
 </div>
    </div>
  </body>
</html>