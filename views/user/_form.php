<?php

use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


   
   
/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $model
 * @var yii\bootstrap\ActiveForm $form
 */

?>

<div class="user-form">

	<?php $form = ActiveForm::begin([
		'id'=>'form-profile',
		'layout'=>'horizontal',
		'validateOnBlur' => false,
                'options'=>['enctype'=> 'multipart/form-data'],
	]) ?>
    
    <div class="row">
            <div class="col-lg-4">
                
            </div>
        
        <div class="col-lg-8">
            <?php
             $employees= new \frontend\modules\humanresource\models\Employee();
                $allemp=$employees->find()->all();
                $employeesnames=ArrayHelper::map($allemp,'name','name');
                $employees=ArrayHelper::map($allemp,'employee_id','name');
            ?>
                <?= $form->field($model->loadDefaultValues(), 'status')
		->dropDownList(User::getStatusList()) ?>
            
             
              <?= $form->field($model, 'employeeid')->widget(Select2::classname(), [
                'data' => $employees,
                'options' => ['placeholder' => 'Select  ...', 'multiple' => false],
                'pluginOptions' => [
                    'tags' => true,
                    'tokenSeparators' => [',', ' '],
                    'maximumInputLength' => 10
                ],
            ]) ?>
			
		
			
                
	        <?= $form->field($model, 'username')->textInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>
                
                <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
            
                <?php  
//                $branch=['001'=>'Nairobi','002'=>'Kisumu','003'=>'Mombasa'];
//                $dockets=[''=>'None','P'=>'P','B'=>'B','S'=>'S'];
                ?>
            
                <?php //$form->field($model, 'branch')->dropdownlist($branch) ?>
            
                
	        
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php if ( $model->isNewRecord ): ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-plus-sign"></span> ' . UserManagementModule::t('back', 'Create'),
					['class' => 'btn btn-outline-primary btn-rounded waves-effect width-md waves-light']
				) ?>
			<?php else: ?>
				<?= Html::submitButton(
					'<span class="glyphicon glyphicon-ok"></span> ' . UserManagementModule::t('back', 'Save'),
					['class' => 'btn btn-outline-info btn-rounded waves-effect width-md waves-light']
				) ?>
			<?php endif; ?>
		</div>
	</div>
            
        </div>
        </div>

	
    




	<?php ActiveForm::end(); ?>

</div>

<?php BootstrapSwitch::widget() ?>

 
 


<?php
$js = <<<JS
        
        $(document).ready(function(){
       function readUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        
        });
JS;
 
$this->registerJs($js);
?>