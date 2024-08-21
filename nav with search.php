<?php
include("connection.php");

$id = $_SESSION['user_id'];
 
$select="SELECT * FROM `project` JOIN `user` ON `project`.`project_id`=`user`.`user_id`
 WHERE `user`.`user_id`=$id";
$runselect= mysqli_query($connect,$select);

if(isset($_POST['search'])){
    $text=$_POST['text'];
    $select_search="SELECT * FROM `project` WHERE `project_name`= '$text' 
    or `description`= '$text' ";
    $run_select_search= mysqli_query($connect,$select_search);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <!-- bootstrap link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <!-- style link  -->
    <link rel="stylesheet" href="./T4.css/nav.css">
</head>
<body>
    <!-- start nav bar -->

    <nav class="navbar navbar-dark navbar-expand-lg bg-dark">
        <div class="container-fluid " style="background-color: none; width: 67%;" >1
          <a class="navbar-brand" href="landing.php" style="font-size: 35px; " >PlanSync</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="landing.php" >Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#"style="color: aliceblue;">Link</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"style="color: aliceblue;">
                  Dropdown
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" aria-disabled="true">Disabled</a>
              </li>
            </ul>
            <form class="d-flex" role="search" method="post">
              <input class="form-control me-2" type="search" name="text" placeholder="Search" aria-label="Search" >
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter" viewBox="0 0 16 16">
                <path d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
              </svg>
              <button class="btn " class="" type="submit" name="search" style="color: #ed7d31; border-color: #ed7d31;">Search</button>
            </form>
            <?php if(isset($_POST['search'])){ ?>
                <table>
            
                    <thead>
            
                        <tr>
                            <th>product name</th>
                            <th>product price</th>
                        </tr>
            
                    </thead>
                    <tbody>
                        <?php foreach($run_select_search as $key) { ?>
                            <tr>
                                <td><?php echo $key['project_name']; ?></td>
                                <td><?php echo $key['description']; ?></td>
                          
                            </tr>
                        <?php } ?>
                    </tbody>
                </table> 
               
                 <?php } else{  ?> 
            
            <thead>
            
                <tr>
                    <th>product name</th>
                    <th>product price</th>
                </tr>
            
            </thead>
            <tbody>
                <?php foreach($runselect as $key) { ?>
                    <tr>
                        <td><?php echo $key['project_name']; ?></td>
                        <td><?php echo $key['description']; ?></td><br>
                  
                    </tr>
                <?php } ?>
            </tbody>
            </table> 
            <?php } ?>
            
          </div>
        </div>
      </nav>
     <!-- end nav bar -->
      <!-- start side nav bar -->
      <div id="sideNav" class="side-nav ">
        <ul>
            <li><a href="landing.php">Home</a></li>
            <li><a href="allprojects.php">Projects details</a></li>
            <li><a href="">Sprint</a></li>
            <li><a href="signup.php">Sign up</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="">Contact</a></li>
            <li><a href="">Reviews</a></li>
            <li><a href="">Settings</a></li>
        </ul>

    </div>
       <!-- end side nav bar -->
        <!--  start my content -->
         <div class="main_content">
            
         </div>
           <!--  end my content -->
        <!-- start footer -->
        <footer class="text-center text-lg-start bg-body-tertiary text-muted"
        style="background-color: #4f4a45 !important; margin-top: 80px; width: 83%; margin: 50px; position: absolute; left: 13.79%; bottom: -70%;">
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <!-- Left -->
            <div class="me-5 d-none d-lg-block" style="color: white !important;">
                <span>Get connected with us on social networks:</span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-facebook-f" style="color: white !important;"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-twitter" style="color: white !important;"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-google" style="color: white !important;"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-instagram" style="color: white !important;"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-linkedin" style="color: white !important;"></i>
                </a>
                <a href="" class="me-4 text-reset">
                    <i class="fab fa-github" style="color: white !important;"></i>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold mb-4" style="color: white !important;">
                            <i class="fas fa-gem me-3" style="color: white !important;"></i>Company name
                        </h6>
                        <p style="color: white;">
                            Here you can use rows and columns to organize your footer content. Lorem ipsum
                            dolor sit amet, consectetur adipisicing elit.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4" style="color: white !important;">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Products
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Angular</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">React</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Vue</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Laravel</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4" style="color: white !important;">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Useful links
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Pricing</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Settings</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Orders</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Help</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4" style="color: white !important;">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                        <p><i class="fas fa-home me-3"></i> New York, NY 10012, US</p>
                        <p>
                            <i class="fas fa-envelope me-3"></i>
                            info@example.com
                        </p>
                        <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                        <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="text-center p-4" style="color: white !important;">
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>

         <!-- end footer -->
   

         <!-- bootstrap js link -->
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

         <!-- my js -->
          <script src="./T4.js/nav.js"></script>
</body>
</html>