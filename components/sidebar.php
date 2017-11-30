<?php 
	
    if(!isset($_SESSION['user_ID']))
	  {
		 header('Location: logout.php');
	  }
      
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>You are so done!!!</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<!-- Datatables -->
	<link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
    <style>
      .videoWrapper {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 */
        padding-top: 25px;
        height: 0;
    }
    .videoWrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		<div class="col-md-3 left_col">
			  <div class="left_col scroll-view">
				<div class="navbar nav_title" style="border: 0;">
				  <span class="site_title">Self Learning Module</span>
				</div>

				<div class="clearfix"></div>

				<br />

				<!-- sidebar menu -->
				<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
				  <div class="menu_section">
					<ul class="nav side-menu">
					  <li id="modulelist.php"><a href="modulelist.php"><i class="fa fa-home"></i>Attempt Questions</a></li>
                      <li id="changepassword.php"><a href="changepassword.php"><i class="fa fa-user"></i>Change Password</a></li>
                      <?php if ($_SESSION['role'] == 'admin') { 
								echo '<li><a><i class="fa fa-gears"></i> Admin Panel <span class="fa fa-chevron-down"></span></a>';
								echo '<ul class="nav child_menu">';
                                echo '<li id="adminmodulelist.php"><a href="adminmodulelist.php">Edit Questions</a></li>' ;
								echo '<li id="adduser.php"><a href="adduser.php">Add User</a></li>' ; 
								echo '<li id="userlist.php"><a href="userlist.php">Edit User</a></li>' ;
								echo '<li id="logs.php"><a href="logs.php">Logs</a></li>';
								echo '</ul>';
								echo '</li>';
                      }?>
					</ul>   
				  </div>
				  

				</div>
				<!-- /sidebar menu -->

			  </div>
			</div>

			<!-- top navigation -->
			<div class="top_nav">
			  <div class="nav_menu">
				<nav>
				  <div class="nav toggle">
					<a id="menu_toggle"><i class="fa fa-bars"></i></a>
				  </div>

				  <ul class="nav navbar-nav navbar-right">
					<li class="">
					  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<?php echo $_SESSION['username']?>
						<span class=" fa fa-angle-down"></span>
					  </a>
					  <ul class="dropdown-menu dropdown-usermenu pull-right">
						<li><a href="logout.php"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
					  </ul>
					</li>

					
				  </ul>
				</nav>
			  </div>
			</div>
			<!-- /top navigation -->
