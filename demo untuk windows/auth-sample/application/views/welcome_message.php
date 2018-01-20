<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>
	<link rel="stylesheet" href="<?= base_url('assets/bs.min.css') ?>">
	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	.reg {
		border: 1px solid #ccc;
		padding: 10px;
		width: 50%; 
	}
	</style>
	<script src="<?= base_url('assets/jquery.min.js') ?>"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div id="container">
	<h1>Demo Satpam Auth Library!</h1>

	<div id="body">

		<?php if($this->session->flashdata('success') != null) { ?>
	        <div class="alert alert-success alert-dismissable">
	            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	            <?php echo $this->session->flashdata('success'); ?>
	        </div>
	    <?php } if($this->session->flashdata('fail') != null) { ?>
	        <div class="alert alert-danger alert-dismissable">
	            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	            <?php echo $this->session->flashdata('fail'); ?>
	        </div>
	    <?php }; ?>

		<p><strong><a href="<?= base_url('welcome/member_area') ?>">Member Area</a> || <a id="login" href="#">Login</a> || <a id="register" href="#">register</a></strong></p>
		<p> coba akses Halaman member area untuk status login anda</p>

		<div id="log" class="reg" style="display: none;">
		 <form method="post" action="<?= site_url('welcome/login') ?>">
			<h4>Login form</h4>
			<div class="form-group">
				<input type="text" name="email" placeholder="email">
				<input type="text" name="pass" placeholder="password">
			</div>
			<div class="form-group">
				<input type="submit" value="Login">
			</div>
		</form>
		</div>

		<div id="reg" class="reg" style="display: none;">
		 <form method="post" action="<?= site_url('welcome/daftar') ?>">
			<h4>Register form</h4>
			<div class="form-group">
				<input type="text" name="user_name" placeholder="username">
				<input type="text" name="email" placeholder="email">
				<input type="text" name="pass" placeholder="password">
			</div>
			<div class="form-group">
				<label>Hak Akses</label>
				<select name="role">
					<option selected="selected" disabled="">--pilih--</option>
					<option value="admin">admin</option>
					<option value="root">root</option>
					<option value="user">user</option>
				</select>
			</div>
			<div class="form-group">
				<input type="checkbox" name="autologin" value="1">
				<label>auto login</label><small> **jika di centang, otomatis login setelah daftar sukses</small>
			</div>
			<div class="form-group">
				<input type="submit" value="Daftar">
			</div>
		</form>
		</div>

		
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>

<script type="text/javascript">

	$('#login').click(function(){
		$('#reg').hide();
		$('#log').show();
	})

	$('#register').click(function(){
		$('#log').hide();
		$('#reg').show();
	})
</script>

</body>
</html>