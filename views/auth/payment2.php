<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */

$this->title = UserManagementModule::t('back', 'Make Payment');
$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="row" style="margin-top: -20px">
     
         <?php
      // var_dump($s);  exit;
       $locations="SELECT * from clients where code='$id'";
       $db=Yii::$app->db1;
        $command=$db->createCommand($locations);
       $company= $command->queryOne(); 
       if($company){
           $companyname=$company['companyname'];
           $companyaddress=$company['company_address'];
           $companyadminemail=$company['admin_email'];
           $companycountry=$company['country'];
           $companypackagesubscribed=$company['package'];
           $companyadminname=$company['contact_person'];
       }else{
        $companyname=NULL; 
        $companyaddress=NULL;
        $companyadminemail=NULL;
        $companycountry=NULL;
        $companypackagesubscribed=NULL;
        $companyadminname=NULL;
       }
      // var_dump($company); exit;
      $locations="SELECT * from invoices where company_name='$companyname'";
       $db=Yii::$app->db1;
        $command=$db->createCommand($locations);
       $invoices= $command->queryOne(); 
       //var_dump($invoices); exit;
      $locations="SELECT * from invoices where company_name='$companyname' AND invoice_status=0 AND type='package'";
       $db=Yii::$app->db1;
        $command=$db->createCommand($locations);
       $allinvoices= $command->queryAll(); 
       //var_dump($allinvoices); exit;
        $SQL="SELECT SUM(amount) AS total FROM invoices where company_name='$companyname' AND invoice_status=0 or invoice_status=2 AND type='package'";
       
        $db=Yii::$app->db1;
        $command=$db->createCommand($SQL);
       $sum= $command->queryOne();
      //var_dump($sum["total"]);   exit;
        $locations="SELECT * from clients where companyname='".$invoices['company_name']."'";
       $db=Yii::$app->db1;
        $command=$db->createCommand($locations);
       $packages= $command->queryOne();
       //var_dump($packages); exit;
       if($packages){
           $packag=$packages['package'];
       }else{
         $packag=NULL;  
       }
       
      ?>                   <div class="col-xl-4">
                                <!-- Personal-Information -->
                                <div class="card-box ribbon-box">
                                   <div class="ribbon ribbon-primary">Payment and Invoice</div>
                                   <div class="clearfix"></div>
                                    <h4 class="header-title mt-0 mb-4">Invoice</h4>
                                    <div class="panel-body">
                                        <p class="text-muted font-13">
                                            <p><b>Hello, <?=$companyadminname ?> from <?=$companyname ?></b></p>
                                                <p class="text-muted">Thanks a lot because you keep purchasing our products. Our company
                                                    promises to provide high quality products for you as well as outstanding
                                                    customer service 24/7. </p>
                                        </p>
                                            
                                        <hr/>
        
                                        <div class="text-left">
                                            <p class="text-muted font-13"><strong>Company Name :</strong> <span class="ml-3"><?=$companyname ?></span></p>
                                            
                                            <p class="text-muted font-13"><strong>Company Code :</strong> <span class="ml-3"><?=$id ?></span></p>
        
                                            <p class="text-muted font-13"><strong>Address :</strong><span class="ml-3"><?=$companyaddress ?></span></p>
                                            
                                            <p class="text-muted font-13"><strong>Admin Names :</strong> <span class="ml-3"><?=$companyadminname ?></span></p>
        
                                            <p class="text-muted font-13"><strong>Admin Email :</strong> <span class="ml-3"><?=$companyadminemail ?></span></p>
       
                                            <p class="text-muted font-13"><strong>Country :</strong> <span class="ml-3"><?=$companycountry ?></span></p>
                                                    
                                            <p class="text-muted font-13"><strong>Package :</strong> <span class="ml-3"> <?=$companypackagesubscribed ?></span></p>
        
                                            
        
                                        </div>
        
                                        
                                    </div>
                                    
                                    
                                      <div class="card-box">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                            <tr>
                                                
                                                <th>Package Name</th>
                                                <th>Amount</th>
                                               
                                            </tr>
                                            </thead>
                                            <tbody>
                                                 <?php foreach($allinvoices as $allinvoices){  ?>
                                            <tr>
                                               
                                                <td><?= $packag ?></td>
                                                <td><?= $allinvoices['amount'] ?></td>
                                                
                                            </tr>
                                             <?php } ?>
                                             <tr>
                                               
                                                <td>Total Invoice Amount</td>
                                                <td>$ <?= $sum["total"] ?></td>
                                                
                                            </tr>
                                           
        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                    
                                    
                                    
                                </div>
                          
                            </div>
        
        
                            <div class="col-xl-8">
       
        
                                <div class="card-box">
                                    <h4 class="header-title mt-0 mb-4">Means of Payments</h4>
                                    <div class="">
                                        <div class="">
                                           <?php if ( Yii::$app->session->hasFlash('success') ): ?>
                                                    <div class="alert alert-success text-center">
                                                            <?= Yii::$app->session->getFlash('success') ?>
                                                    </div>
                                            <?php endif; ?>
                                            <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <h4 class="header-title mb-4">Make Payment</h4>

                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a href="#home" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                                <span class="d-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                                <span class="d-none d-sm-block">NORMAL MPESA PAYMENT</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profile" data-toggle="tab" aria-expanded="true" class="nav-link">
                                                <span class="d-block d-sm-none"><i class="mdi mdi-account"></i></span>
                                                <span class="d-none d-sm-block">MPESA EXPRESS CHECKOUT</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#messages" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                                                <span class="d-none d-sm-block">PAYPAL</span>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="home">
                                            <h4><strong class="mb-2">NORMAL MPESA PAY</strong></h4><br>
                                           <figure><img src="<?= Yii::$app->getUrlManager()->getBaseUrl()."/images/mpesa.jpg"?>" alt="" class="img-circle" height="150px" width="250px"></figure>
                                            <?php
                                                $mpesapay=$sum["total"] * 100; 


                                                ?>
                                                  <h4>Paying Via mpesa steps</h4>
                                                  <ul>
                                                      1. Go to m-pesa menu, select, "lipa na m-pesa"<br>
                                                  2. Select paybill.<br>
                                                  3. Select "enter business number" or paybill number <span style="color:red"><b> 979306</b></span> and then press "ok" <br>
                                                  4. Select "enter account number" which is the reference number <span style="color:red"><b> <?php  if($invoices){
                                                           echo  $invoices['ref_no'];
                                                       }  else {
                                                        echo 'No Reference Number';   
                                                       } ?></b></span> in the above invoice and press "ok" <br>
                                                  5. Enter amount <span style="color:red"><b> Kshs <?php  echo  $mpesapay ;?></b></span><br>
                                                  6. Enter your mpesa pin and press ok
                                                  </ul>        
                                           
                                        </div>
                                        
                                        <div class="tab-pane" id="profile">
                                            <h4><strong class="mb-2">PAY VIA MPESA</strong></h4><br>
                                            <p style="color: maroon"><strong>KEEP YOUR PHONE OPEN TO INITIATE TRANSACTION.</strong></p>
                                            <form name="subscribe" method="post" action="pay">
                                            <input type="hidden" name="<?= Yii::$app->request->csrfParam;?>" value="<?= Yii::$app->request->csrfToken;?>">
				            <input style="background-color: white"type="phone" class="form-control" id="name" name="phone" placeholder="Enter Phone number" required>
					     
                                            <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-lg pull-left mt-3">Make payment</button><!-- Send Button -->
					    </form>
                                     
                                        </div>
                                        <div class="tab-pane" id="messages">
                                           <h4><strong class="mb-2">PAY VIA PAYPAL</strong></h4><br>
                                            <div  style="display: block; margin-left: auto;margin-right: auto;width: 50%;">
                                                <div id="paypal-button-container">


                                                    </div>

                                                    </div>
                                             
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                                            
                                            
                                            
                                            
                                            
                                        </div>
        
                                     
        
                                    </div>
                                </div>
        
                              
        
                            </div>
                            <!-- end col -->
        
                        </div>
                        <!-- end row -->

                        
                    </div> <!-- end container-fluid -->

                </div> <!-- end content -->


<?php
$paydollars=$sum["total"];
$refno=$invoices['ref_no'];

?>
<?php
$js = <<<JS
        
$( document ).ready(function() {
    
    
     paypal.Buttons({
    createOrder: function(data, actions) {
      return actions.order.create({
      
        purchase_units: [{
        
          amount: {
            value: '$paydollars',
            currency: 'USD'
          },
         reference_id: '$refno',
         description: 'SMART HR APPLICATION FEES PAYMENT',
         payee: {
          email: 'pm@gmail.com'
          },
         invoice_number: '$refno',
        
        }]
      });
    },
    onApprove: function(data, actions) {
      return actions.order.capture().then(function(details) {
        alert('Transaction completed Successfully');
        // Call your server to save the transaction
        return fetch('validate', {
          method: 'post',
          headers: {
            'content-type': 'application/json'
          },
          body: JSON.stringify({
            orderID: data.orderID
          })
        });
      });
    }
  }).render('#paypal-button-container');
  
});
        
        
JS;
 
$this->registerJs($js);

    