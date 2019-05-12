<html>
    <head>
        <title>GET TrueWallet Token</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
<body>
    <div class="container">
        <div class="row mt-5">
        <div class="col-3"></div>
        <div class="col-6 border">
<?php
session_start();
require_once("TrueWallet.class.php");
if(isset($_SESSION['username']) and (isset($_SESSION['password']))){
    $tw = new TrueWallet($_SESSION['username'], $_SESSION['password']);    
}
if(!isset($_GET['getotp'])){ ?>
    <form action="?getotp=1" method="post">
        <h3 class="text-center mt-3 mb-5">Verify your TrueWallet Account</h3>
        <input class="form-control mt-4 mb-2" type="email" name="username" placeholder="User Email">
        <input class="form-control mb-2" type="password" name="password" placeholder="Password">
        <input class="form-control" type="submit" value="Verify">
    </form>

<?php
} else if (isset($_POST['username']) and (isset($_POST['password']))){ 
    
    if(!isset($_SESSION['username'])){
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];
        session_write_close();
    } 
    
    $tw = new TrueWallet($_SESSION['username'], $_SESSION['password']);    
    $request = $tw->RequestLoginOTP();
    header('location:?sentotp=1&getotp=1&mobile_number='.$request['data']['mobile_number'].'&otp_ref='.$request['data']['otp_reference']);
} else if(isset($_GET['sentotp'])) { ?>

    <form action="?getprofile=1&getotp=1&mobile_number=<?=$_GET['mobile_number']?>&otp_ref=<?=$_GET['otp_ref']?>" method="post"> 
        <h4 class="mt-3">Your OTP code has been sent to <?=$_GET['mobile_number']?> </h4>
        <h4 class="mt-3">REF : <?=$_GET['otp_ref']?></h4>
        <br><br>
        <input class="form-control mb-2" name="otp_code" placeholder="Enter recieved OTP code here !">
        <input type="hidden" name="ref_code" value="<?=$_GET['otp_ref']?>">
        <input class="form-control" type="submit" value="Submit">
    </form>
    
<?php 
} else if (isset($_GET['getprofile'])){ 
    
    $tw->SubmitLoginOTP($_POST['otp_code'], $_GET['mobile_number'], $_GET['otp_ref']);
?>
            <div class="text-right">
                <div class="row">
                    <div class="col-12">
                        <h4 class="text-center mt-3 mb-3">TrueWallet Token</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        REFERENCE TOKEN :
                    </div>
                    <div class="col-8 text-left">
                        &emsp;<?=$tw->reference_token?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-3 mb-3">
                        <?='GET Data at '.date("Y-m-d h:i:sa")?>
                    </div>
                </div>
            </div>
<?php
    unset($_SESSION['username']);
    unset($_SESSION['password']);
	unlink('gettoken.php');
} ?>
</div>
    <div class="col-3"></div>
    <div class="col-3"></div>
    </div>
    </div>
</body>
</html>