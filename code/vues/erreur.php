<head>
    <title>TDL Public</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/navBar.css">
    <link rel="stylesheet" href="css/homeList.css">


</head>

<body>
<?php
    require ($rep.$vues['navBar']);
?>

<div class="container">
    <div class="row align-items-center">
        <div class="alert alert-danger" role="alert" >
            <h4 class="alert-heading">Un erreur est survenue</h4>
            <?php
            if (isset($dVueEreur)) {
                foreach ($dVueEreur as $value){
                    echo '<p>'.$value.'</p>';
                }
            }
            ?>
            <hr>
            <p class="mb-0">Désolé pour cet erreur</p>
        </div>
    </div>

</div>


 </body>
</html>