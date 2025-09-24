<html>
	<head>
       <title>UTA</title>
	   <link rel="stylesheet" href="css/style.css">
	    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.11.3/themes/bootstrap/easyui.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.11.3/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="jquery-easyui-1.11.3/themes/color.css">
    <script type="text/javascript" src="jquery-easyui-1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="jquery-easyui-1.11.3/jquery.easyui.min.js"></script>

	</head>


	<body>
		<header>
			<image src="images/banner.jpg" width="50%"></image>
		</header>
		<nav> 
			<ul> 
            <li> <a href="index.php?action=inicio">Prueba </a> </li>
			<li> <a href="index.php?action=nosotros">Nosotros</a></li>
			<li> <a href="index.php?action=servicios">Servicios</a></li>
			<li> <a href="index.php?action=contactanos">Contactanos</a></li>
			</ul>
		</nav>
		<section>
            <?php 
            $mvc= new MvcController();
            $mvc-> enlacesPaginasController();
            ?>
		</section>
		<footer> 
			Derechos reservados @Cuarto Software
		</footer>
	</body>

</html>