<?php
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$app->get("/", function ($req, $res) {
	$params = $req->getQueryParams();
	return $this->renderer->render($res, "home.phtml", $params);
});

$app->post("/sendmail", function ($req, $res) {
	$params = $req->getParsedBody();
	if (strlen(trim($params["g-recaptcha-response"])) == 0) {
		$finalMsg = "Por favor, verifica que no eres un robot";
		return $res->withRedirect("/?emailSent=" . $finalMsg);
	}
	
	$to = "javier.rodriguez@teambits.mx";
	$subject = "Correo de contacto de Teambits";
	$message = "<html><head><title></title></head><body>Nombre: " . $params["name"] . "<br>".
		"Empresa: ". $params["business"] . "<br>".
		"Teléfono: ". $params["phone"] . "<br>".
		"Email: ". $params["mail"] . "<br>".
		"Servicio: ". $params["service"] . "<br>".
		"Regresar llamada: ". $params["returnCall"] . "<br>".
		"Mensaje: ". $params["message"] . "<br>".
		"Horario: ". $params["schedule"]. "<br>".
		"</body></html>"
	;
	$headers = "MIME-Version: 1.0\r\n"; 
	$headers .= "Content-type: text/html; ccharset=UTF-8\r\n"; 
	
	//dirección del remitente 
	$headers .= "From: Usuario teambits <". $params["mail"] .">\r\n"; 
	
	//direcciones que recibián copia 
	$headers .= "Cc: alfredo.torres@teambits.mx\r\n"; 
	
	//direcciones que recibirán copia oculta 
	//$headers .= "Bcc: pepe@pepe.com,juan@juan.com\r\n"; 
	$finalMsg = "";
	if (mail($to, $subject, $message, $headers)) {
		$finalMsg = "Envío exitoso";
	} else {
		$finalMsg = "Error en el envio";
	}
	return $res->withRedirect("/?emailSent=" . $finalMsg);
});

$app->post("/curriculum", function ($req, $res) {
	$nombreArchivo = "No envió archivo";
	if (isset($_FILES["archivo"])) {
        $file_tmp = $_FILES["archivo"]["tmp_name"];
        $file_name = $_FILES["archivo"]["name"];
		$destination = "/home/adminteambits/public_ftp/curriculums/";
		$nombreArchivo = $file_name;
		//move_uploaded_file($file_tmp, $destination . $file_name);
	}

	$params = $req->getParsedBody();
	
	$message = "Nombre: " . $params["nombre"] . "\n".
		"Archivo: ".$nombreArchivo . "\n".
		"Nombre: ". $params["nombre"] . "\n".
		"Correo: ". $params["correo"] . "\n".
		"Teléfono: ". $params["telefono"] . "\n".
		"Fecha: ". $params["fecha"] . "\n".
		"País: ". $params["pais"] . "\n".
		"Ubicación: ". $params["ubicacion"] . "\n".
		"Ganar desde: ". $params["ganardesde"]. "\n".
		"Hasta: ". $params["ganarhasta"]. "\n".
		"Monto deseado: ". $params["ganar"]. "\n".
		"Grado de estudios: ". $params["gradoestudios"]. "\n".
		"Carrera: ". $params["carrera"]. "\n".
		"Puesto deseado: ". $params["puesto"]. "\n".
		"Habilidades: ". $params["habilidades"]. "\n".
		"Experiencia: ". $params["experiencia"]. "\n".
		"Carta de presentación: ". $params["carta"]. "\n"
	; 

	$email = new PHPMailer(true);
	$email->Host = localhost;

	$mail->CharSet = 'UTF-8';
	$email->From = $params["correo"];
	$email->FromName = $params["nombre"];
	$email->Subject = "Curriculum por usuario Teambits.mx";
	$email->Body = utf8_decode($message);
	$email->AddAddress("javier.rodriguez@teambits.mx");
	if (strlen(trim($file_tmp)) != 0 && strlen(trim($file_name)) != 0) {
		$email->AddAttachment($file_tmp, $file_name);
	}

	if ($email->Send()) {
		$msg = "¡Se ha enviado tu curriculum con exito!";
	} else {
		$msg = "Error al enviar tu curriculum";
	}

	return $res->withRedirect("/board?emailSent=" . $msg);
});

$app->get("/about", function ($req, $res) {
	return $this->renderer->render($res, "about.phtml");
});

$app->get("/services", function ($req, $res) {
	return $this->renderer->render($res, "services.phtml");
});

$app->get("/services/training", function ($req, $res) {
	return $this->renderer->render($res, "training.phtml");
});

$app->get("/services/development", function ($req, $res) {
	return $this->renderer->render($res, "development.phtml");
});

$app->get("/services/outsourcing", function ($req, $res) {
	return $this->renderer->render($res, "outsourcing.phtml");
});

$app->get("/products", function ($req, $res) {
	return $this->renderer->render($res, "products.phtml");
});

$app->get("/products/b4c", function ($req, $res) {
	return $this->renderer->render($res, "b4c.phtml");
});

$app->get("/products/erp", function ($req, $res) {
	return $this->renderer->render($res, "erp.phtml");
});

$app->get("/products/dpl", function ($req, $res) {
	return $this->renderer->render($res, "dpl.phtml");
});

$app->get("/projects", function ($req, $res) {
	return $this->renderer->render($res, "projects.phtml");
});

$app->get("/board", function ($req, $res) {
	$params = $req->getQueryParams();
	return $this->renderer->render($res, "board.phtml", $params);
});