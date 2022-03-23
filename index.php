<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health application</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/customscrollbar.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
				$("#sidebar").mCustomScrollbar({
					theme: "minimal"
				});

				$('#sidebarCollapse').on('click', function () {
					$('#sidebar, #content').toggleClass('active');
					$('.collapse.in').toggleClass('in');
					$('a[aria-expanded=true]').attr('aria-expanded', 'false');
				});
			});
		</script>
</head>

<body>

	<div class="wrapper">
		<!-- Sidebar  -->
		<nav id="sidebar">
			<div class="sidebar-header">
				<h3>My health application</h3>
			</div>
			<ul class="list-unstyled components">
				<p>Dummy Heading</p>
				<li class="active"><a href="?page=home">Home</a></li>
				<li>
					<a href="#pageSubmenu" class="dropdown-toggle" data-bs-toggle="collapse" aria-expanded="false">Pages</a>
					<ul class="collapse list-unstyled" id="pageSubmenu">
						<li><a href="?page=home">Page 1</a></li>
						<li><a href="?page=home">Page 2</a></li>
						<li><a href="?page=home">Page 3</a></li>
					</ul>
				</li>
				<li><a href="?page=about">About</a></li>
				<li><a href="?page=home">Register</a></li>
				<li><a href="?page=home">Login</a></li>
			</ul>

			<!--ul class="list-unstyled CTAs">
				<li><a href="#" class="download">Button 1</a></li>
				<li><a href="#" class="article">Button 2</a></li>
			</ul-->
		</nav>

		<!-- Page Content  -->
		<div id="content">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="container-fluid">
					<button type="button" id="sidebarCollapse" class="btn btn-light">
						<i class="bi bi-menu-button-fill"></i>
						<span></span>
					</button>
					<button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<i class="fas fa-align-justify"></i>
					</button>

					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="nav navbar-nav ms-auto">
							<li class="nav-item">
								<a class="nav-link active" href="#">Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Page</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Page</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>

			<?php
				if (isset($_GET['page'])) {
					include("pages/".$_GET['page'].".php");
				} else {
					include('pages/home.php');
				} 	/* otherwise, include the default page */
			?>

		</div>
	</div>
</body>
</html>
