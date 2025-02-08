

<div class="clearfix"></div>
<div class="check" style="margin: 5% 10% 5% 10%;">

<?php
  




$keyId = 'rzp_live_Jf3B5Ng9EkFH6l';
$keySecret = 'M6Kh0C3QjbtfelIe5KON7oTQ';
$displayCurrency = 'INR';
include('razorpay/Razorpay.php');



use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

if($_POST['pay']!='')
{
//
// We create an razorpay order using orders api
// Docs: https://docs.razorpay.com/docs/orders
//

$username=$_POST['username'];
$email=$_POST['email'];
$mobileno=$_POST['mobileno'];
$subscribe=$_POST['subscribe'];
$referralcode=$_POST['referralcode'];

// send_sms($mobileno,'Dear Customer Thank you for Being a membership with us. be support');
     
$register_query="INSERT INTO member_details SET referral_code='".$referralcode."',username='".$username."',email='".$email."',mobileno='".$mobileno."',amount='".$subscribe."',insert_date=NOW()"; 
        $register_query_insert=mysqli_query($conn,$register_query);

 
 //customerdate
$customerData = [
    'name'         => $username,
    'email'          => $email, 
    'contact'        => $mobileno,
    'fail_existing'=> '0',
];

 $razorpayCustomer = $api->customer->create($customerData);

 $razorpayCustomerId = $razorpayCustomer['id'];



$orderData = [
    'amount'          => $subscribe * 0, // 2000 rupees in paise
    'currency'        => 'INR',
    'method'=>'emandate',
    'payment_capture'=> 1,
    'customer_id'=> $razorpayCustomerId,
    'token'=>[
       'auth_type'=> 'netbanking',
        'max_amount'=>$subscribe * 100,
        ],
    
];

$razorpayOrder = $api->order->create($orderData);

 $razorpayOrderId = $razorpayOrder['id']; 
?>


 <script src = "https://checkout.razorpay.com/v1/checkout.js"> </script>
  <script>


    var options = {
      "key": "<?php echo $keyId; ?>",
      "order_id": "<?php echo $razorpayOrderId; ?>",
      "customer_id": "<?php echo $razorpayCustomerId; ?>",
      "recurring": "1",
      "image": "images/razor-logo.png",
      "handler": function (response) {
        alert(response.razorpay_payment_id);
        alert(response.razorpay_order_id);
        alert(response.razorpay_signature);
      },
      "theme": {
        "color": "#2ac5f1"
      }
    };
    var rzp1 = new Razorpay(options);
     rzp1.open();
      e.preventDefault();
  /*  document.getElementById('rzp-button1').onclick = function (e) {
     
    }*/
  </script>
  <?php } ?>

<form id="razorpayform" method="POST" action="razorpay.php">
  
<div class="row">
      <aside class="col-md-12">
        <lable>Referral Code</lable>
        <input type="text" class="form-control" placeholder="Referral Code" name="referralcode" required>
        
    </aside>
    
    <aside class="col-md-4">
        <lable>Username</lable>
        <input type="text" class="form-control" placeholder="Username" name="username" required>
        
    </aside>
    <aside class="col-md-4">
        
        <lable>Email</lable>
        <input type="text" class="form-control" placeholder="Email" name="email" required>
        
    </aside>
    <aside class="col-md-4">
        
        <lable>Mobile Number</lable>
        <input type="text" class="form-control" placeholder="Mobile Number" name="mobileno" required>
        
    </aside>
    
     </div>
     
     <div class="row" style="margin-top:30px;">
    <aside class="col-md-12">
        
        <label class="radio-inline"><input type="radio" name="subscribe" required value="100" >Membership - 100</label>
<label class="radio-inline"><input type="radio" name="subscribe" required value="500" >Membership - 500</label>
<label class="radio-inline"><input type="radio" name="subscribe" required value="1000"> Membership - 1000</label>


    </aside>

    
     </div>


<div class="row" style="margin-top:30px;">
    
    <aside class="col-md-3">

    </aside>
    <aside class="col-md-3">

        
        <input type="submit" name="pay" class="theme-btn btn-style-one" id="pay" style="height: 50px;text-align:center;" value="Membership Now" class="give-btn" />
    </aside>
    <aside class="col-md-3">
        
    </aside>
    </div>

</form>



</div>



           