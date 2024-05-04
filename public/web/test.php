<!DOCTYPE html> 
    <head>
        <title>Bootstrap 3 Timepicker demo</title>
        <script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" rel="stylesheet"> 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="col-md-12 well">
                <div class="input-group bootstrap-timepicker">
                    <input id="timepicker1" type="text" class="input-small">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            // $('#timepicker1').timepicker();
            
        $('#timepicker1').timepicker({
          defaultTime: 'current',
          showInputs: false, 
          showMeridian: false 
        });
        </script>
    </body>
</html>

<?php 
  // require_once __DIR__ . '/menu.php';  
  
  // // splashBerhasil("on Developing");  
  
  // // $sLinkRedirect = __DIR__ . '../node_role.php';
  // $sLinkRedirect = '/node_role.php';
  // // echo   $sLinkRedirect  ;
  // splashBerhasil( $sLinkRedirect,$sLinkRedirect);
?> 
<!-- <br>
<button onclick="tes1()">On Developing</button> 

<br>
<a href="javascript:DoPost()">Click Here</a> 

<form name='myForm' action='node_role_form.php' method='post'>
  <input type="hidden" name="nrid" value=1/>
</form> -->


<script language="javascript"> 

  function tes1(){
    alert("on Develope feature Test");
  }

  function DoPost(){//==pause 
    document.forms["myForm"].submit();
    // $.post('node_role_form.php', {nrid: 1}, function() { window.location.href = 'node_role_form.php' }); //pakai jQuery
  }

</script>
<!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
