<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">.
	<!-- <link rel="stylesheet" type="text/css" href="<?php //base_url('css/date-picker.css') ?>"> -->
	<!-- <link rel="stylesheet" type="text/css" href="<?php //base_url('css/jquery-ui.css')?>"> -->
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/bootstrap.min.css')?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/style.css')?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/app-style.css')?>">
	<?php if($this->router->fetch_class() == 'auth'): ?>
		<link rel="stylesheet" type="text/css" href="<?=base_url('assets/css/set-admin-background.css')?>">
	<?php endif; ?>
	<title>Admin Panel</title>
</head>
<body>
 <div class="container">
 	<?php $this->load->view('common/messages'); ?>  
<!-- Header starts -->
