<html>
    <head>
        <title>Web Services</title>
    </head>
<body>

    <form method="get" action="<?php echo base_url()?>index.php/Web_service/login" enctype="multipart/form-data">

    <table>
    <tr>
        <td><h3>Web service for Login(login)</h3></td></tr>
    <tr>
        <td>Email <input type="text" name="email">email</td></tr>
    <tr>
        <td>Phone Number<input type="text" name="phone">phone</td>
    </tr>

    <tr>    
        <td><input type="submit" value="Login" name="login"></tr></table>
</form>
***********************************************************************************************************