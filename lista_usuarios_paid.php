<?
   session_cache_limiter('private, must-revalidate');
   session_start();
   if (empty($_SESSION['admin_valido'])) {
     include('default.php');
	 return;
   }
   extract($_SESSION); extract($_POST); extract($_GET);

   include('../lib.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Test Vioniko.com | Usuarios</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="icon" href="../images/favicon.ico" type="image/x-icon" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
<link href="../css/style2.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="../js/setup.js"></script>
<script type="text/javascript" src="../scripts_lib.js"></script>
<script type="text/javascript" src="../lista.js"></script>
<SCRIPT LANGUAGE="JavaScript">

  function buscar() {
	document.forma.numpag.value=1;
	document.forma.action='lista_usuarios.php';
	document.forma.target='_self';
    document.forma.submit();
  }

  function ir(form, Pag) {
    form.numpag.value = Pag;
    form.action='lista_usuarios.php';
	form.target='_self';
    form.submit();
  }

  function ordena(orden) {
    document.forma.ord.value = orden;
	document.forma.numpag.value = 1;
    document.forma.action='lista_usuarios.php';
	document.forma.target='_self';
    document.forma.submit();
  }

  function activa(id) {
    document.forma.usuario.value = id;
    document.forma.action='activar_usuario.php';
	document.forma.target='_self';
    document.forma.submit();
  }

  function reporte_excel() {
    document.forma.action='lista_usuarios_excel.php';
	document.forma.target='_blank';
    document.forma.submit();
  }

</SCRIPT>
<!--start slider -->
    <link rel="stylesheet" href="../css/fwslider.css" media="all">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/css3-mediaqueries.js"></script>
    <script src="../js/fwslider.js"></script>
<!--end slider -->
<!--nav-->
<script>
		$(function() {
			var pull 		= $('#pull');
				menu 		= $('nav ul');
				menuHeight	= menu.height();

			$(pull).on('click', function(e) {
				e.preventDefault();
				menu.slideToggle();
			});

			$(window).resize(function(){
        		var w = $(window).width();
        		if(w > 320 && menu.is(':hidden')) {
        			menu.removeAttr('style');
        		}
    		});
		});
</script>
<!--[if !IE]><!-->
	<style>

	/*
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 570px
	and also iPads specifically.
	*/
	@media
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {

		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr {
			display: block;
		}

		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr {
			position: absolute;
			top: -9999px;
			left: -9999px;
		}

		tr { border: 1px solid #ccc; }

		td {
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			position: relative;
			padding-left: 50%;
		}

		td:before {
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%;
			padding-right: 10px;
			white-space: nowrap;
		}

		/*
		Label the data
		*/
		td:nth-of-type(1):before { content: "Nombre"; }
		td:nth-of-type(2):before { content: "Fecha"; }
		td:nth-of-type(3):before { content: "E-mail"; }
		td:nth-of-type(4):before { content: "Prospectos"; }
		td:nth-of-type(5):before { content: "Puntos meta"; }
		td:nth-of-type(6):before { content: "Puntos ganados"; }
		td:nth-of-type(7):before { content: "Subdominio"; }
		td:nth-of-type(8):before { content: "Categor�as"; }
		td:nth-of-type(9):before { content: "Permisos"; }
		td:nth-of-type(10):before { content: "Visitas"; }
		td:nth-of-type(11):before { content: "Presentaciones"; }
		td:nth-of-type(12):before { content: "Activo"; }
		td:nth-of-type(13):before { content: "Opciones"; }

	}

	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		body {
			padding: 0;
			margin: 0;
			width: 320px; }
		}

	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		body {
			width: 495px;
		}
	}

	</style>
	<!--<![endif]-->
</head>
<body>
<?


   if (empty($ver)) $ver='10';
   if (empty($numpag)) $numpag='1';
   if (empty($ord)) $ord='nombre';

   if     ($ord=='nombre') $orden='ORDER BY usuario.nombre,usuario.apellidos';
   elseif ($ord=='pagcorp') $orden='ORDER BY usuario.pagina_corporativa';
   elseif ($ord=='fecha') $orden='ORDER BY usuario.fecha_registro DESC,usuario.nombre,usuario.apellidos';
   elseif ($ord=='email') $orden='ORDER BY usuario.email';
   elseif ($ord=='subdominio') $orden='ORDER BY usuario.subdominio';

   include('../conexion.php');

   // obtener el total de registros que coinciden...
  // y establecer algunas variables


	 // construir la condici�n de b�squeda
	 $condicion = "WHERE 1=1 ";

	 if (!empty($texto))
	   $condicion .= " AND (usuario.nombre LIKE '%$texto%' OR usuario.apellidos LIKE '%$texto%' OR usuario.pagina_corporativa LIKE '%$texto%' OR usuario.email LIKE '%$texto%')";

	 if (!empty($categoria))
	   $condicion .= " AND usuario.categorias_herramientas LIKE '% $categoria,%'";

	   $resultadotot= mysql_query("SELECT * FROM usuario $condicion",$conexion);
	   $totres = mysql_num_rows ($resultadotot);
	   $totpags = ceil($totres/$ver);
	   if ($totres==0)
		  $numpag = 0;

?>
<?php echo $conexion;?>
<? include('header.php'); ?>
<!-- start mian -->
<div class="main_bg">
<div class="wrap">
<div class="main">

<div align="right" class="menu2">
<a href="principal.php">
<img src="../images/icon-home.png" width="16" height="11" alt="">&nbsp;Inicio</a>
| <a href="lista_usuarios_en.php">
<img src="../images/flags/gb.png" width="16" height="11" alt="">&nbsp;English</a>
| <a href="logout.php">
<img src="../images/icon-logout.png" width="16" height="11" alt="">&nbsp;Salir</a>
</div>

	 	 <div class="contact">

				  <div class="contact-form">
			 	 <div class="content">
		 	 	<h2 class="style">Listado de Usuarios</h2>

                <br>
                <p>Criterios de b&uacute;squeda</p>
		 	 </div>
             <br>
			 <form action="lista_usuarios.php" method="post" name="forma" id="forma">

             <div>
			 <label>Texto:</label>
			 <input name="texto" type="text" class="row2" id="texto" value="<?= $texto; ?>" size="70" maxlength="50">
             <p>El texto es buscado en el Nombre, Apellidos, P&aacute;gina Corporativa, E-mail</p>
			 </div>

             <div>
             <p>Categor&iacute;a de herramientas:</p>
			 <select name="categoria" class="campo" id="categoria">
                        <option value="">Cualquier categor&iacute;a...</option>
                        <?  $resCAT= mysql_query("SELECT * FROM cat_herramienta ORDER BY nombre",$conexion);
                    while ($rowCAT = mysql_fetch_array($resCAT)) {
			          echo '<option value="'.$rowCAT['clave'].'"';
				      if ($rowCAT['clave']==$categoria) echo ' selected';
				      echo '>'.$rowCAT['nombre'].'</option>';
			        }
		        ?>
                    </select>
			 </div>

             <div>
             <p>Ver:</p>
             <select name="ver" class="campo" id="ver">
                        <option <? if ($ver==10) echo 'selected'; ?>>10</option>
                        <option <? if ($ver==20) echo 'selected'; ?>>20</option>
                        <option <? if ($ver==50) echo 'selected'; ?>>50</option>
                        <option <? if ($ver==100) echo 'selected'; ?>>100</option>
                    </select>
                    usuarios por p&aacute;gina
             </div>


			 <div>
			 <input type="submit" name="Submit" class="" value="Buscar" onClick="buscar();">
             </div>

             <div>
             Resultado de la b&uacute;squeda
             <input name="condicion_print" type="hidden" id="condicion_print" value="<?= $condicion; ?>">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onClick="javascript:reporte_excel();"><img src="../images/icon_excel.png" alt="Exportar a Excel" width="30" height="30" border="0" align="absmiddle"></a>
             </div>

             <div>
             Usuarios encontrados: <b><?= number_format($totres,0,'.',','); ?></b>
             </div>

             <div align="right">
                    <input name="show_criterio" type="hidden" id="show_criterio" value="0">
                    <input name="prospecto" type="hidden" id="prospecto">
                    <input name="ord" type="hidden" id="ord" value="<?= $ord; ?>">
                    <input name="numpag" type="hidden" id="numpag" value="<?= $numpag; ?>">
                    <?


                     $regini = ($numpag * $ver) - $ver;
					 if ($regini<0) $regini=0;

                     // poner flechitas anterior, primero, &uacute;ltimo, etc.
                     if ($numpag<=1) {
                         echo '<img src="../images/primera_off.png" width="20" height="15" align="absmiddle">&nbsp;';
                         echo '<img src="../images/anterior_off.png" width="20" height="15" align="absmiddle">';
                     } else {
                         echo '<a href="javascript:ir(document.forma,1);"><img src="../images/primera_on.png" border="0" width="20" height="15" align="absmiddle" alt="Primera p&aacute;gina"></a>&nbsp;';
                         echo '<a href="javascript:ir(document.forma,'.($numpag-1).');"><img src="../images/anterior_on.png" border="0" width="20" height="15" align="absmiddle" alt="P&aacute;gina anterior"></a>';
                     }
                     echo '&nbsp;&nbsp;<span class="texto">';
                     echo "P&aacute;gina ".$numpag." de ".$totpags;
                     echo "&nbsp;</span>&nbsp;";
                     if ($numpag>=$totpags) {
                         echo '<img src="../images/siguiente_off.png" width="20" height="15" align="absmiddle">&nbsp;';
                         echo '<img src="../images/ultima_off.png" width="20" height="15" align="absmiddle">';
                     } else {
                         echo '<a href="javascript:ir(document.forma,'.($numpag+1).');"><img src="../images/siguiente_on.png" border="0" width="20" height="15" align="absmiddle" alt="Siguiente p&aacute;gina"></a>&nbsp;';
                         echo '<a href="javascript:ir(document.forma,'.$totpags.');"><img src="../images/ultima_on.png" border="0" width="20" height="15" align="absmiddle" alt="&Uacute;ltima p&aacute;gina"></a>&nbsp;';
                     }
              ?>
                  </div>

                  <table>
                  <thead>
                <tr>
                  <th><a href="javascript:ordena('nombre');" style="color:#FFF;">Nombre</a><? if ($ord=='nombre') echo ' <img src="../images/orden.png" width="15" height="15" align="absmiddle">'; ?></th>

                  <th><a href="javascript:ordena('fecha');" style="color:#FFF;">Fecha <br>registro </a><? if ($ord=='fecha') echo ' <img src="../images/orden.png" width="15" height="15" align="absmiddle">'; ?></th>
                  <th><a href="javascript:ordena('email');" style="color:#FFF;">E-mail </a><? if ($ord=='email') echo ' <img src="../images/orden.png" width="15" height="15" align="absmiddle">'; ?></th>
				  <!----------subroto-19-feb-2017-start---------->
					 <th>
						<a href="javascript:void(0);" style="color:#FFF;">Link More Emails</a>
					</th>
					<!----------subroto-19-feb-2017-end---------->
                  <th><img src="../images/tabla_prospectos.jpg" width="22" height="206" alt=""></th>
                  <th><img src="../images/tabla_puntosmeta.jpg" width="22" height="206" alt=""></th>
                  <th><img src="../images/tabla_puntosganados.jpg" width="22" height="206" alt=""></th>
                  <th><a href="javascript:ordena('subdominio');" style="color:#FFF;">Subdominio</a>
                      <? if ($ord=='subdominio') echo ' <img src="../images/orden.png" width="15" height="15" align="absmiddle">'; ?></th>
<th >File Size</th>
                  <th >Categor&iacute;as <br>Herramientas</th>
                  <th >Permisos</th>
                  <th >Template</th>
                  <th >Visitas</th>
                  <th >Payment</th>
                  <th >Activo</th>
                  <th >Opciones</th>
                </tr>
                 </thead>
		<tbody>
           <?
		     $renglon=0;

/*
             if ($ord=='fecha')
               $resultado= mysql_query("SELECT MAX(evento.fechahora) AS fechamax, prospecto.* FROM prospecto LEFT JOIN evento ON evento.prospecto = prospecto.clave $condicion GROUP BY prospecto.clave ORDER BY fechamax ASC LIMIT $regini,$ver",$conexion);
			 elseif ($ord=='meta')
               $resultado= mysql_query("SELECT SUM(tipo_evento.puntos) AS total_puntos, prospecto.* FROM prospecto LEFT JOIN evento ON evento.prospecto = prospecto.clave LEFT JOIN tipo_evento ON tipo_evento.clave = evento.tipo $condicion GROUP BY prospecto.clave ORDER BY total_puntos ASC LIMIT $regini,$ver",$conexion);
             elseif ($ord=='nombre' OR $ord=='fecha')
*/


			   $resultado= mysql_query("SELECT * FROM usuario $condicion $orden LIMIT $regini,$ver",$conexion);
			//    echo "Subroto";
			//    $sql = "SELECT * FROM usuario $condicion $orden LIMIT $regini,$ver";
			//    var_dump($sql);
			//    var_dump($conexion);

			//    $result = mysqli_query($conexion,"SHOW DATABASES"); 
			//    while ($row = mysqli_fetch_array($result)) { 
			// 	   echo $row[0]."<br>"; 
			//    }

             while ($row = mysql_fetch_array($resultado)) {

			   $usuario = $row['clave'];
$totalsize = 0;
			   $resFileUploads = mysql_query("SELECT * FROM prospect_file_uploads WHERE usuario = '".$usuario."' ",$conexion);
				while ($rowFileUploads = mysql_fetch_array($resFileUploads))
				{
					$totalsize += $rowFileUploads['size'];
				}
if($totalsize > 0){
$totalsize = number_format($totalsize , 2, '.', '');
}

//$totalsize = floatval($totalsize);

			   $resPRO= mysql_query("SELECT clave FROM prospecto WHERE usuario='$usuario'",$conexion);
			   $total_prospectos= mysql_num_rows($resPRO);

			   // calcular total de puntos meta
			   $resPM = mysql_query("SELECT SUM(puntos) AS total_puntos FROM tipo_evento LEFT JOIN evento ON tipo_evento.clave = evento.tipo WHERE evento.usuario='$usuario'",$conexion);
               $rowPM = mysql_fetch_array($resPM);

               // calcular total de puntos ganados
			   $resPG = mysql_query("SELECT SUM(puntos) AS total_puntos FROM tipo_evento LEFT JOIN evento ON tipo_evento.clave = evento.tipo WHERE evento.usuario='$usuario' AND evento.realizado=1",$conexion);
               $rowPG = mysql_fetch_array($resPG);

			   // CUENTA VISITAS AL SUBDOMINIO
				$resHIT= mysql_query("SELECT SUM(visitas) AS total_visitas, SUM(retornos) AS total_retornos FROM template_hits WHERE usuario=$usuario",$conexion);
				$rowHIT= mysql_fetch_array($resHIT);
				if ($rowHIT['total_visitas']>0) $retorno = $rowHIT['total_retornos']/$rowHIT['total_visitas'];
				else $retorno=0;

			   $renglon++;
          ?>

                <tr valign="top" <?= color_lista($renglon); ?>>
                  <td ><?= $row['nombre'].' '.$row['apellidos']; ?></td>

                  <td ><?= date('d/m/Y',strtotime($row['fecha_registro'])); ?></td>
                  <td >
					<p style="padding:5px;text-align:center;background:#D1D1D1;border:2px solid cornflowerblue">
						<a href="mailto:<?= $row['email']; ?>">
							<?= $row['email']; ?>
						</a>
					<p>
					
					<?php
					//----------subroto-19-feb-2017-start----------//
					//echo "SELECT * FROM usuario WHERE clave IN (SELECT linked_user_id from usuario_linked_accounts WHERE user_id = '".$usuario."')";
					$resMo = mysql_query("SELECT * FROM usuario WHERE clave IN (SELECT linked_user_id from usuario_linked_accounts WHERE user_id = '".$usuario."')",$conexion);
					while ($rowMoh = mysql_fetch_array($resMo))
					{
						echo '<br/>
							<p style="padding:5px;text-align:center;background:#D1D1D1;border:2px solid cornflowerblue">
								<button type="button" href="javascript:void(0)" class="deleteLinkedEmail" style="color:red" linked-id='.$rowMoh['clave'].' user-id='.$usuario.'>
									Unlink
								</button>
								<br/>
								<a href="mailto:'.$rowMoh['email'].'">'.$rowMoh['email'].'</a>
							</p>';
					}
					
					//----------subroto-19-feb-2017-end----------//
					?>
					
				</td>
				<!----------subroto-19-feb-2017-start---------->
				<td>
					<input type="text" class="extra_email" id="extra_email_<?= $usuario?>">
					<button type="button" class="addEmailButton" value="extra_email_<?= $usuario?>" current-user="<?= $usuario?>">
						Link Email
					</button>
				 </td>
				 <!----------subroto-19-feb-2017-end---------->
                  <td ><? if ($total_prospectos>0) echo number_format($total_prospectos,0,'.',','); else echo '&nbsp'; ?></td>
                  <td ><?= $rowPM['total_puntos']; ?></td>
                  <td ><?= $rowPG['total_puntos']; ?></td>
                  <td ><?= $row['subdominio']; ?></td>
<td ><?= $totalsize." MB" ; ?></td>
                  <td >
                  <?  $resCAT= mysql_query("SELECT * FROM cat_herramienta ORDER BY nombre",$conexion);
					  while ($rowCAT = mysql_fetch_array($resCAT)) {
						$cat_buscar = ' '.$rowCAT['clave'].',';
						if (strstr($row['categorias_herramientas'],$cat_buscar)) echo '-'.$rowCAT['nombre'].'<br />';
                      }
                  ?>
                  [<a href="javascript:openWindow('abc_cat_usuario.php?usuario=<?= $row['clave']; ?>','info','no','yes',500,460);">Editar</a>]</td>

                  <td>
				  	<? if ($row['subdominio_custom']==1) echo '-Config. subdominio<br />';
				  	   if ($row['tour_custom']==1) echo '-Config. tour virtual<br />';
				  	   if ($row['autoresponder_custom']==1) echo '-Config. autoresponder<br />';
				  	   if ($row['chat_custom']==1) echo '-Config. chat<br />';
					   if ($row['comments_custom']==1) echo '-Config. comments<br />';
					   if ($row['fbcomments_custom']==1) echo '-Config. comments Facebook<br />';
					   if ($row['fbpixel_custom']==1) echo '-Config. Pixel de conversi�n Facebook<br />';
                       if ($row['webminar_permission']==1) echo '-Config. Webinar<br />';
                       if($row['agenda_permission']==1){ echo '- Agenda '; }
					   if ($row['pagado']==0)
					   		echo '[<a href="javascript:openWindow(\'abc_permisos_usuario.php?usuario='.$row['clave'].'\',\'info\',\'no\',\'yes\',500,460);">Editar</a>]';
					   else echo '<strong>PAGANDO</strong><br>'.date('d/m/Y',strtotime($row['fecha_pago']));
					?>



							</td>
                  <td><? if ($row['template']) {
				  			$template = $row['template'];
							 $resTEM= mysql_query("SELECT * FROM landing_page WHERE clave=$template",$conexion);
			 				 $rowTEM= mysql_fetch_array($resTEM);
				  			 echo $rowTEM['nombre'].'<br>';
				  		}
					   if ($row['pagado2']==0)
					   		echo '[<a href="javascript:openWindow(\'abc_template_usuario.php?usuario='.$row['clave'].'\',\'info\',\'no\',\'yes\',500,460);">Editar</a>]';
					   else echo '<strong>PAGANDO</strong><br>'.date('d/m/Y',strtotime($row['fecha_pago2']));

				   ?>
                    </td>
                  <td><?= $rowHIT['total_visitas'].'/'.number_format($retorno,2,'.',',').'%'; ?></td>
               
<!-- Payment activation subroto 04 Sep 2022 Start -->
				  <!-- <td>
<div class="tool_tip">
<ul class="tt-wrapper">
<? if ($row['presentaciones']==1) { ?>
<img src="../images/icon_activo.png" width="30" height="30">
<li><a onClick="return confirm('&iquest;Est&aacute;s seguro que deseas Desactivar\nlas Presentaciones Autom&aacute;ticas\nal Usuario?')" href="desactivar_presentaciones.php?usuario=<?= $row['clave']; ?>">
<img src="../images/icon_desactivar.png" alt="Desactivar Presentaciones Autom&aacute;ticas" width="30" height="30" border="0">
<span>Desactivar presentaciones autom&aacute;ticas</span>
</a></li>
<? } else { ?>
<li><a onClick="return confirm('&iquest;Est&aacute;s seguro que deseas Activar\nlas Presentaciones Autom�ticas\nal Usuario?')" href="activar_presentaciones.php?usuario=<?= $row['clave']; ?>">
<img src="../images/icon_activar.png" alt="Activar Presentaciones Autom�ticas" width="30" height="30" border="0">
<span>Activar presentaciones autom&aacute;ticas</span>
</a></li>
<? } ?>
</ul>
</div></td> -->

<td>
<?php 
	$paymentStatus= mysql_query("select paymentDate from tracking_Payment WHERE userid=" . $usuario, $conexion);
	$paymentStatus= mysql_fetch_array($paymentStatus);
	
	if( $paymentStatus['paymentDate'] !=null ) {
		echo "<p class='paymentDetails'>Paid on " . $paymentStatus['paymentDate'] . "</p>";
	} else {
?>
	<a id="activePayment" class="paymentDetails<?= $usuario; ?>" data-userid="<?php echo $usuario ?>" href="#">Active Payment</a>
<?php }?>
</td>
<!-- Payment activation subroto 04 Sep 2022 Start -->

                  <td>
<div class="tool_tip">
<ul class="tt-wrapper">
<? if ($row['activo']==1) { ?>
<img src="../images/icon_activo.png" width="30" height="30">
<? } else { ?>
<li><a onClick="return confirm('Ests seguro que deseas\nActivar el Usuario?')" href="activar_usuario.php?usuario=<?= $row['clave']; ?>">
<img src="../images/icon_activar.png" alt="Activar Usuario" width="30" height="30" border="0">
<span>Activar Usuario</span>
</a></li>
<? } ?>
</ul>
</div></td>
                  <td > 
<div class="tool_tip">
<ul class="tt-wrapper">
<? if ($total_prospectos>0) { ?>
<li><a href="lista_prospectos.php?usuario=<?= $row['clave']; ?>">
<img src="../images/icon_prospectos.png" width="30" height="30" border="0" alt="Ver Prospectos">
<span>Ver prospectos</span>
</a> </li>
<? } else echo '<img src="../images/spacer.gif" width="59" height="18">'; ?>
<li><a href="javascript:openWindow('info_usuario.php?usuario=<?= $row['clave']; ?>','info','no','yes',500,460);">
<img src="../images/icon_infoprospecto.png" alt="Ver informaci�n del Usuario" width="30" height="30" border="0">
<span>Ver informaci&oacute;n del usuario</span>
</a></li>
//1
	<?php echo '<img src="../images/spacer.gif" width="59" height="18">'; ?>
	<li><a href="javascript:openWindow('usuario_clon.php?usuario=<?= $row['clave']; ?>','info','no','yes',500,460);">
			<img src="../images/icon_infoprospecto.png" alt="Ver informaci�n del Usuario" width="30" height="30" border="0">
			<span>Ver informaci&oacute;n del usuario</span>
		</a></li>
</ul>
</div></td>
                </tr>
            <?
                 } // WHILE
              ?>
              </tbody>
              </table>

				    </form>
				    </div>
  				<div class="clear"> </div>
			  </div>
		</div>
</div>
</div>

<? include('footer.php'); ?>
</body>

<!------subroto-19-feb-2014-start---------->
 <script src="../js/addEmail.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
 <script>
	// Voca Details will show on button click 44
	$( document ).on( 'click', 'a#activePayment', function(evt) {
	evt.preventDefault();
	var values = $(this).data('userid');
	var url = "activePayment.php?q=" + values;
	var paymentDetailClass = this.className;
	alert(paymentDetailClass); 

	$.get( url, function( data ) {
		$('#activePayment').hide();
		$( "." + paymentDetailClass ).show( );
		$( "." + paymentDetailClass ).html( data );
	});
	});
 </script>
<!------subroto-19-feb-2014-end----------> 
</html>