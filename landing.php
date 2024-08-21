<?php
include "connection.php";
// $id=$_SESSION['user_id'];
// $_SESSION['user_id']=$id;
$user_id = 20;
$select1="SELECT * FROM `subscription` WHERE `subscription_id`=1" ;
$runselect1=mysqli_query($connect,$select1);


$select2="SELECT * FROM `subscription` WHERE `subscription_id`=2" ;
$runselect2=mysqli_query($connect,$select2);
$fetchdata=mysqli_fetch_assoc($runselect2);
$subscription_id2=$fetchdata['subscription_id'];
$name2=$fetchdata['subscription_name'];
$price2=$fetchdata['price'];
$capacity2=$fetchdata['capacity'];

$select3="SELECT * FROM `subscription` WHERE `subscription_id`=3" ;
$runselect3=mysqli_query($connect,$select3);
$fetchdata=mysqli_fetch_assoc($runselect3);
$subscription_id3=$fetchdata['subscription_id'];
$name3=$fetchdata['subscription_name'];
$price3=$fetchdata['price'];
$capacity3=$fetchdata['capacity'];

?>


<html>
   
<!-- designer -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Sedan+SC&display=swap"
        rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IM+Fell+English+SC&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/landing.Css">
</head>

<body>

    <!-- start nav bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary" style="background-color: rgba(50, 50, 50, 0.848) !important;">
  <div class="container-fluid">
    <a class="navbar-brand" href="landing.php">PlanSync</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <div class="items">
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="landing.php">Home</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="profilepage.php">Profile</a>
        </li>
        <li class="nav-item" style="margin-right: 40px;">
          <a class="nav-link" aria-current="page" href="my_subscriptions.php">my subscriptions</a>
        </li>
       
        </div>

      </ul>
      <!-- <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn" type="submit">Search</button>
      </form> -->
    </div>
  </div>
</nav>
  <!-- end nav bar -->

  <div class="home">
        <div class="home-content">
            <div class="content-text">
                <h1 class="name">PlanSync</h1>
                <div class="tex">
                    <P class="paragraph"> We are here to help you with designing and organizing your site. Whether you need
                        help
                        with layout, content, or functionality, feel free to ask. What are you envisioning for your
                        website?
                        Feel free to adjust this template based on your specific needs and the scale of your project.
                    </P>
                </div>
                <!-- <button>
                    <span class="shadow"></span>
                    <span class="edge"></span>
                    <span class="front text"> Learn More
                    </span>
                </button> -->
            </div>

            <div class="content-img">
                <img src="./Img/WhatsApp Image 2024-08-01 at 12.18.25 PM.jpeg">



            </div>
        </div>
        <div class="wave">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#6c5f5b" fill-opacity="1"
                    d="M0,224L120,197.3C240,171,480,117,720,117.3C960,117,1200,171,1320,197.3L1440,224L1440,0L1320,0C1200,0,960,0,720,0C480,0,240,0,120,0L0,0Z">
                </path>
            </svg>
        </div>

        <!--section2-->


        <!--<div class="main-contanier">
<div class="carddd">
<div class="upper-card">
    <div class="pricingtable-header">
    <h3 class="heading">Standard</h3>
    <span class="price-value">
        <span class="currency">$</span> 10
        <span class="month">/mo</span>
    </span>

</div>
</div>
<div class="lower-card"></div>

<div class="pricing-content">
    <ul>
        <li>50GB Disk Space</li>
        <li>50 Email Accounts</li>
        <li>50GB Monthly Bandwidth</li>
        <li>10 Subdomains</li>
        <li>15 Domains</li>
    </ul>
    <a href="#" class="read">sign up</a>
</div>

</div>


</div>-->
        <div class="pricing-table">
            <div class="details">
                <h2> pricing your way !</h2>
                <div class="divp">
                    <p>The content of the table depend on the type of products or services you're offering. Here’s a
                        general
                        guide to help you set up a pricing table ,Make sure the differences between plans are clear to
                        help
                        users understand the value of each option.</p>
                </div>
            </div>
            <div class="grid">
                <div class="box">
                    <?php foreach($runselect1 as $da1){ ?>
                    <div class="title"> <?php echo $da1['subscription_name'] ;?></div>

                    <div class="price">
                        <b>$ <?php echo $da1['price'];?></b>
                        <span>Per month</span>
                    </div>
                    <div class="features">

                        <div> <?php echo $da1['capacity'] ?> members</div>
                        <!-- <hr>
                        <div> 50 Gb storage</div>
                        <hr>
                        <div> 2 users allowed</div> -->
                        <!-- <hr> -->
                         <!-- bro what does that even mean ? -->
                        <!-- <div> send up</div> -->
                    </div>
                    <div class="button">
                    <button><a  class="a" href="bayment.php?subid=<?php echo $da1['subscription_id']?>">buy package</a></button>
                    </div>
                    <?php } ?>
                </div>
            
           
                <!-- try -->
                
                <div class="box Professional">
                    <div class="title"><?php echo $name2 ;?></div>
                    <div class="price">
                        <b>$ <?php echo $price2 ;?></b>
                        <span>Per month</span>
                    </div>
                    <div class="features">

                        <div> <?php echo $capacity2 ;?> members</div>
                        <!-- <hr>
                        <div> 200 Gb storage</div>
                        <hr> 
                        <div> 2 users allowed</div>
                        <hr>
                        <div> send up</div> -->
                    </div> 

                    <div class="button-1">
                       <button> <a class="al"  href="bayment.php?subid=<?php echo $subscription_id2?>">buy package</a></button>
                    </div> 

                </div>
               

                <div class="box">
                    <div class="title"> <?php echo $name3 ;?></div>

                    <div class="price">
                        <b>$ <?php echo $price3 ;?></b>
                        <span>Per month</span>
                    </div>
                    <div class="features">

                        <div> <?php echo $capacity3 ;?> members</div>
                        <!-- <hr>
                        <div> 1 TB storage</div>
                        <hr> 
                        <div> 2 users allowed</div>
                        <hr>
                        <div> send up</div> -->
                    </div>
                    <div class="button">
                        <button> <a class="a" href="bayment.php?subid=<?php echo $subscription_id3?>">buy package</a></button>
                    </div>
                    
                </div>
            </div>
            
        </div>

        <!-- section 3 -->
        <div class="features-section">
            <table class="table table-striped-columns">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>team tracker</th>
                        <th>Group Genius</th>
                        <th>corporate coordinator</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td>Ability to create Team</td>

                        <!-- <td><i class="fa-solid fa-x"></i></td> -->
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Ability to Change Team</td>

                        <td><i class="fa-solid fa-x"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Number of Partcipants to add</td>

                        <td>0</td>
                        <td>10</td>
                        <td>15+</td>
                    </tr>
                    <tr>
                        <td>Ability to leave Team</td>

                        <td><i class="fa-solid fa-x"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Ability to move from Team to another</td>

                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>change team leader</td>

                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Ability to remove Team</td>

                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-x"></i></td>
                    </tr>
                    <tr>
                        <td>Ability to add member</td>

                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                        <td><i class="fa-solid fa-check"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!--section4-->
        <div class="heading">
            <img src="./Img/img 2.jpeg.png" alt="">
            <p>Our story started in 20th with plan sync and a vision to do anything easy. What began as planner has
                grown into current status or achievements. We’re proud to be a team of , talented professionals,
                creative thinkers, passionate experts, all working together to easy work for your pepole.

            </p>
        </div>
        <div class="contanier">
            <section class="about">
                <div class="about-image">
                    <img src="./Img/img3.jpeg" alt="">

                </div>
                <div class="about-content">
                    <div class="box">
                        <h2 class="head">Our values</h2>
                        <ul class="li">

                            <li>integrity </li>
                            <li> innovation</li>
                            <li> excellence</li>
                            <li> customer-centricity</li>
                        </ul>
                    </div>







                </div>
            </section>
        </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>






</html>