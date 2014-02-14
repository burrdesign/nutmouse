<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $this->_['title']; ?></title>
</head>

<body class="default">
	<?php 
		$view = new View();
		$view->setTemplate($this->_['template']);
		$view->assignArray($this->_);
		try {
			echo $view->loadTemplate();
		} catch(Exception $e){
			echo "<h1>Exception caught!</h1><p>Message:</p><pre>{$e->getMessage()}</pre><p>Trace:</p><pre>{$e->getTraceAsString()}</pre>";
		}
	?>
</body>
</html>