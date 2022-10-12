<?php
/**
 * @var $this yii\web\View
 * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
 */

use webvimark\modules\UserManagement\components\GhostHtml;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TAGCODE');</script>
    <!-- End Google Tag Manager -->
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="<?= Yii::$app->getUrlManager()->getBaseUrl()."/images/crewlogo.png"?>" type="image/x-icon">


</head>
<style>
.field-icon {
  float: right;
  margin-left: -25px;
  margin-top: -60px;
  position: relative;
  z-index: 2;
  margin-right:20px;
}

/*.container{
  padding-top:50px;
  margin: auto;
}*/
    
</style>
<body id="top">
<div class="page_loader"></div>

<!-- Login 5 start -->
<div class="login-5">
    <div class="container">
        <div class="row login-box">
            <div class="col-lg-6 align-self-center pad-0">
                <div class="form-section align-self-center">
                    <h3><img src="<?= Yii::$app->getUrlManager()->getBaseUrl()."/images/crewlogo.png"?>" alt="logo"></h3>
                  
                    <div class="btn-section clearfix">
                        <a href="https://hrms.crew.africa/user-management/auth/login" class="link-btn active btn-1 active-bg">Login</a>
                        <a href="https://crew.africa/" class="link-btn btn-2 default-bg">Visit Home</a>
                         
                    </div>
                    <?php if(Yii::$app->session->get('companystatus')){
                          echo '<h4 style="color:red;">'.UserManagementModule::t('front', 'Wrong User Credentials.Contact Admin').'</h4>';
                         }?>
                    <div class="clearfix"></div>
                   <?php $form = ActiveForm::begin([
						'id'      => 'login-form',
						'options'=>['autocomplete'=>'off'],
						'validateOnBlur'=>false,
						'fieldConfig' => [
							'template'=>"{input}\n{error}",
						],
					]) ?>
                        <div class="form-group form-box">
<!--                            <input type="email" name="email" class="input-text" placeholder="Email Address">-->
                            <?= $form->field($model, 'username')
						->textInput(['placeholder'=>$model->getAttributeLabel('username'), 'autocomplete'=>'off','class'=>'input-text']) ?>
                        </div>
                        <div class="form-group form-box clearfix">
                            <?= $form->field($model, 'password')
						->passwordInput(['placeholder'=>$model->getAttributeLabel('password'), 'autocomplete'=>'off','class'=>'input-text']) ?>
                                           
                        </div>
                        <div class="form-group clearfix mb-0">
                           
                            <?= (isset(Yii::$app->user->enableAutoLogin) && Yii::$app->user->enableAutoLogin) ? $form->field($model, 'rememberMe')->checkbox(['value'=>true]) : '' ?>

					<?= Html::submitButton(
						UserManagementModule::t('front', 'Login'),
						['class' => 'btn-md btn-theme float-left','style'=>['background-color'=>'#1EB9BE']]
					) ?>
                             <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password" onclick="myFunction()">
<!--                                  <input type="checkbox" id="checkbox1" onclick="myFunction()">-->
<!--                            <a href="forgot-password-5.html" class="forgot-password">Forgot Password</a>-->
                        </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
            <div class="col-lg-6 bg-color-15 align-self-center pad-0 none-992 bg-img">
<!--                <div class="info clearfix">
                    <div class="logo-2">
                        <a href="https://hrms.crew.africa/user-management/auth/login">
                            <img src="../../images/crewlogo.png" alt="logo">
                        </a>
                    </div>
                    <h3>Welcome to Crew</h3>
                    <div class="social-list">
                        <a href="#" class="facebook-bg">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="twitter-bg">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" class="google-bg">
                            <i class="fa fa-google"></i>
                        </a>
                        <a href="#" class="linkedin-bg">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>
<!-- Login 5 end -->

</html>

<script>
     function myFunction() {
            var x = document.getElementById("loginform-password");
            if (x.type === "password") {
              x.type = "text";
             
            } else {
              x.type = "password";
            }
          
          }

  </script>




























<style>
  
    .btn-primary {
    color: #fff;
    background-color: #77267C !important;
    border-color: #77267C !important;
}
    
</style>



















