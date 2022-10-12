<?php

namespace webvimark\modules\UserManagement\controllers;

use webvimark\components\AdminDefaultController;
use Yii;
use webvimark\modules\UserManagement\models\User;
use webvimark\modules\UserManagement\models\search\UserSearch;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends AdminDefaultController
{
	/**
	 * @var User
	 */
	public $modelClass = 'webvimark\modules\UserManagement\models\User';

	/**
	 * @var UserSearch
	 */
	public $modelSearchClass = 'webvimark\modules\UserManagement\models\search\UserSearch';

	/**
	 * @return mixed|string|\yii\web\Response
         * 
         * 
	 */
        

	public function actionCreate()
	{
		$model = new User(['scenario'=>'newUser']);
                //$model->date_joined= date('d-M-Y H:m:s');
                $model->scenario='insert';
               // $post=Yii::$app->request->post();
              
                // var_dump($users);   exit;
                $looggedinuser=Yii::$app->getUser()->identity->branch;
               // var_dump($looggedinuser);  exit;
          
		if ($model->load(Yii::$app->request->post()))
		{
                    $post=Yii::$app->request->post();
                    $email=$post['User']['email'];
                    $employeeid=$post['User']['employeeid'];
                    //var_dump($post); exit;
            $SQL="SELECT * FROM clients where code='".$looggedinuser."'";
       
            $db=Yii::$app->db1;  
            $command=$db->createCommand($SQL);
            $clients= $command->queryOne();
               if($clients){
                   $package=$clients['package'];
               }else{
                 $package=Null;  
               }
               $packages= new \frontend\models\Packages();
               $packages=$packages->find()->where(['name'=>$package])->one();
               
               if($packages){
                   $employees=$packages['employees'];
               }else{
                 $employees=Null;  
               }
              // var_dump($employees);  exit;
                   $users= new \frontend\modules\humanresource\models\Users();
                   $users=$users->find()->where(['branch'=>$looggedinuser])->count();
                  // var_dump($users);  exit;
                   if($users > $employees){
                      // echo 3;  exit; 
                    Yii::$app->session->setFlash('error', 'The package you have subscribed has reached maximum user registration');  
                    $this->layout='@frontend/views/layouts/main.php';
                    return $this->redirect(['create']);    
                   }
                  // echo 2;  exit;    
                  // var_dump($users);  exit;
               
                
                   
                    //$employeeid=$post["User"]['employeeid'];
                     //var_dump($employeeid);   exit;
                    $employee=new \frontend\modules\humanresource\models\Employee();
                     $employee= $employee->find()->where(['employee_id'=>$employeeid])->one();
                     //var_dump($employee); exit;
                     if($employee){
                         $fullnames=$employee['name'];
                         $phonenumber=$employee['phone'];
                         $branchlocation=$employee['branch_loc'];
                     }else{
                     $fullnames='undefined'; 
                     $phonenumber='undefined';
                     $branchlocation='undefined';
                     }
                 
                   // var_dump($users);   exit;
                       $model->profilepic = UploadedFile::getInstance($model, 'profilepic');
                       //$generalsettigs= new \frontend\models\Generalsettings();
                       //$setting1= $generalsettigs->find()->where(['desc'=>'company_name'])->one();
                     
                       if($model->profilepic){
                           //check if directory exist
                            $path = 'uploads';
                            if(!file_exists($path)){
                            FileHelper::createDirectory($path, $mode = 0777, $recursive = true);
                            }
                           //upload profile pic
                    $model->profilepic->saveAs('uploads/'. $model->profilepic->baseName.$model->username . '.' . $model->profilepic->extension);
                    $documenturl='uploads/'. $model->profilepic->baseName.$model->username. '.' . $model->profilepic->extension;
                    $model->profilepic=$documenturl;
                      }
                      
                      
                        $password= Yii::$app->security->generateRandomString(8);
                        //send email to user with the password 
                       // $this->sendEmailtouser($model->email,$model->username, $password);
                        $generalsettigs= new \frontend\models\Generalsettings();
                        $setting= $generalsettigs->find()->where(['name'=>'PASS_EXPIRY'])->one();
                        //$model->employee_id=$model->employeeid;
                        //$model->username=$model->email;
                        $model->employee_id=$employeeid;
                        $model->username=$email;
                        $model->fullnames=$fullnames;
                        $model->phone_number=$phonenumber;
                        $model->branch=$looggedinuser;
                        $model->docket=$branchlocation;
                        $model->password_hash= Yii::$app->getSecurity()->generatePasswordHash($password);
                        //update this during passwordchange the same way Y-m-d H:i:s
                        $model->passexpirydate=date('Y-m-d', strtotime("+$setting->value days"));
                       // $model->date_joined= date('d-M-Y H:m:s');
                        //echo $model->passexpirydate;
                        //check if user logged in is superadmin
                       $status=Yii::$app->getUser()->identity->dash_status;
                       $adminstatus=Yii::$app->getUser()->identity->status;
                       //$model->superadmin=$model->superadmin;
                       //var_dump($status);  exit;
                       if($status==1){
                           $userstatus=2;
                       }else{
                        $userstatus=0;   
                       }
                        $model->dash_status=$userstatus;
                       if($status==1){
                       $model->status=1;
                       $model->superadmin=1;
                       }elseif($status==2){
                        $model->status=1;  
                        $model->superadmin=0;
                       }else{
                       $model->status=1; 
                       $model->superadmin=0;
                       
                       }
                         //var_dump($model->auth_key); exit;
                        //insertuser to hrclients users table
                       $superadmin='';
                      $sql ="INSERT  INTO `user`(username,auth_key,password_hash,email,branch,fullnames,phone_number,employeeid,employee_id,dash_status,status) 
                      VALUES('".$email."','".$model->auth_key."','".$model->password_hash."','".$email."','".$looggedinuser."','".$fullnames."','".$phonenumber."','".$employeeid."','".$employeeid."','".$userstatus."','".$superadmin."');"; 
                         //var_dump($sql);  exit;    
                      \Yii::$app->db1->createCommand($sql)->execute();                     
                         if($model->save(false)){
                            
                            //send email
                        
                       //$emailer = new \webvimark\modules\UserManagement\models\forms\ConfirmEmailForm();
                        //$emailer->sendEmail($model->email,$model->username,$password);
                    
                        Yii::$app->session->setFlash('success', 'User Created Successfully.');
			return $this->redirect(['view',	'id' => $model->id]);
                        }
                        else{
                        Yii::$app->session->setFlash('error', 'Failed to create user');
                        $this->layout='@frontend/views/layouts/main.php';
			return $this->redirect(['create']);  
                        }
		}
                       $this->layout='@frontend/views/layouts/main.php';
		        return $this->renderIsAjax('create', compact('model'));
	}
        
        
        public function actionCreate2()
	{
		$model = new User(['scenario'=>'newUser']);
                //$model->date_joined= date('d-M-Y H:m:s');
                $model->scenario='insert';
               // $post=Yii::$app->request->post();
              
                // var_dump($users);   exit;
                $looggedinuser=Yii::$app->getUser()->identity->branch;
               // var_dump($looggedinuser);  exit;
          
		if ($model->load(Yii::$app->request->post()))
		{
                    $post=Yii::$app->request->post();
                
            $SQL="SELECT * FROM clients where code='".$looggedinuser."'";
       
            $db=Yii::$app->db1;  
            $command=$db->createCommand($SQL);
            $clients= $command->queryOne();
               if($clients){
                   $package=$clients['package'];
               }else{
                 $package=Null;  
               }
               $packages= new \frontend\models\Packages();
               $packages=$packages->find()->where(['name'=>$package])->one();
               
               if($packages){
                   $employees=$packages['employees'];
               }else{
                 $employees=Null;  
               }
              // var_dump($employees);  exit;
                   $users= new \frontend\modules\humanresource\models\Users();
                   $users=$users->find()->where(['branch'=>$looggedinuser])->count();
                  // var_dump($users);  exit;
                   if($users > $employees){
                      // echo 3;  exit; 
                    Yii::$app->session->setFlash('error', 'The package you have subscribed has reached maximum user registration');  
                    return $this->redirect(['create2']);    
                   }
                  // echo 2;  exit;    
                  // var_dump($users);  exit;
               
                
                   
                    $fullnames=$post["User"]['fullnames'];
                   
                 
                   // var_dump($users);   exit;
                       $model->profilepic = UploadedFile::getInstance($model, 'profilepic');
                       //$generalsettigs= new \frontend\models\Generalsettings();
                       //$setting1= $generalsettigs->find()->where(['desc'=>'company_name'])->one();
                     
                       if($model->profilepic){
                           //check if directory exist
                            $path = 'uploads';
                            if(!file_exists($path)){
                            FileHelper::createDirectory($path, $mode = 0777, $recursive = true);
                            }
                           //upload profile pic
                    $model->profilepic->saveAs('uploads/'. $model->profilepic->baseName.$model->username . '.' . $model->profilepic->extension);
                    $documenturl='uploads/'. $model->profilepic->baseName.$model->username. '.' . $model->profilepic->extension;
                    $model->profilepic=$documenturl;
                      }
                      
                      
                        $password= Yii::$app->security->generateRandomString(8);
                        //send email to user with the password 
                       // $this->sendEmailtouser($model->email,$model->username, $password);
                        $generalsettigs= new \frontend\models\Generalsettings();
                        $setting= $generalsettigs->find()->where(['name'=>'PASS_EXPIRY'])->one();
                        //$model->employee_id=$model->employeeid;
                        $model->fullnames=$fullnames;
                        $model->username=$model->email;
                        $model->branch=$looggedinuser;
                        $model->password_hash= Yii::$app->getSecurity()->generatePasswordHash($password);
                            $status=Yii::$app->getUser()->identity->dash_status;
                       $adminstatus=Yii::$app->getUser()->identity->status;
                       //var_dump($status);  exit;
                       if($status==1){
                           $userstatus=2;
                       }else{
                        $userstatus=0;   
                       }
                        
                       $model->dash_status=$userstatus;
                       if($status==1){
                       $model->status=1;
                       $model->superadmin=1;
                       }elseif($status==2){
                        $model->status=0;  
                        $model->superadmin=0;
                       }else{
                       $model->status=0; 
                       $model->superadmin=0;
                       }
                       //$superadmin=1;
                        $model->employee_id=90;
                       //$model->superadmin=$superadmin;
                        //update this during passwordchange the same way Y-m-d H:i:s
                        $model->passexpirydate=date('Y-m-d', strtotime("+$setting->value days"));
                        //insert user to Hr clients table
                   $sql ="INSERT  INTO `user`(username,auth_key,password_hash,email,branch,fullnames,phone_number) 
                    VALUES('".$model->email."','".$password."','".$model->password_hash."','".$model->email."','".$looggedinuser."','".$fullnames."','".$model->phone_number."');"; 
                    //  var_dump($sql);  exit;    
                 \Yii::$app->db1->createCommand($sql)->execute(); 
                    if(!$sql){
                    createLog(mysql_error());
                    }    
                        
                        
                        
                       // $model->date_joined= date('d-M-Y H:m:s');
                        //echo $model->passexpirydate;
                        //exit;
                       
                        if($model->save(false)){
                            
                            //send email
                        
                       //$emailer = new \webvimark\modules\UserManagement\models\forms\ConfirmEmailForm();
                        //$emailer->sendEmail($model->email,$model->username,$password);
                    
                        Yii::$app->session->setFlash('success', 'User Created Successfully.');
			return $this->redirect(['view',	'id' => $model->id]);
                        }
                        else{
                        Yii::$app->session->setFlash('error', 'Failed to create user');
			return $this->redirect(['create']);  
                        }
		}
                       
		        return $this->renderIsAjax('create2', compact('model'));
	}
        
        //user self update
        
	public function actionSelfupdate()
	{
		$model = new User();
                $model= $model->findOne(Yii::$app->user->identity->id);
                
                $oldpic=$model->profilepic;
                //$model->date_joined= date('d-M-Y H:m:s');
		if ($model->load(Yii::$app->request->post()))
		{
                    $post=Yii::$app->request->post();
                    $model->profilepic = UploadedFile::getInstance($model, 'profilepic');
                   //  $generalsettigs= new \frontend\modules\meetings\models\Generalsettings();
                   //  $setting1= $generalsettigs->find()->where(['desc'=>'company_name'])->one();
                    if($model->profilepic){
                        
//                        var_dump($model->profilepic);
//                        exit;
                        
                      //check if directory exist
                            $path = 'uploads';
                            if(!file_exists($path)){
                            FileHelper::createDirectory($path, $mode = 0777, $recursive = true);
                            }
                           
                     //remove old profile
                        if(file_exists($oldpic)){
                     unlink($oldpic);  
                        }
                           //upload profile pic
                        //upload profile pic
                    $model->profilepic->saveAs('uploads/'. $model->profilepic->baseName.$model->username . '.' . $model->profilepic->extension);
                    $documenturl='uploads/'. $model->profilepic->baseName.$model->username. '.' . $model->profilepic->extension;
                    $model->profilepic=$documenturl;
                      }
                      else {
                      $model->profilepic=$oldpic;
                      }
                  $sql ="INSERT  INTO `user`(username,auth_key,password_hash,email,branch,fullnames,phone_number) 
                    VALUES('".$model->email."','".$password."','".$model->password_hash."','".$model->email."','".$looggedinuser."','".$fullnames."','".$model->phone_number."');"; 
       //  var_dump($sql);  exit;    
                 \Yii::$app->db1->createCommand($sql)->execute(); 
                    if(!$sql){
                    createLog(mysql_error());
                    }  
                        if($model->save(false)){
                            
                            //send email
                        
                        $emailer = new \webvimark\modules\UserManagement\models\forms\ConfirmEmailForm();
                        $emailer->sendEmail($model->email,$model->username);
                    
                        Yii::$app->session->setFlash('success', 'Details updated successfully');
			return $this->redirect(['viewprofile',	'id' => $model->id]);
                        }
                        else{
                        Yii::$app->session->setFlash('error', 'Failed to update details');
			return $this->redirect(['viewprofile']);  
                        }
		}
                       
		        return $this->renderIsAjax('_form_1', compact('model'));
	}
        
        
        //user self update
        
	public function actionNewupdate()
	{
		$model = new User();
                $model= $model->findOne(Yii::$app->user->identity->id);
                
                $oldpic=$model->profilepic;
                //$model->date_joined= date('d-M-Y H:m:s');
		if ($model->load(Yii::$app->request->post()))
		{
                    $post=Yii::$app->request->post();
                       $model->profilepic = UploadedFile::getInstance($model, 'profilepic');
                     
                    if($model->profilepic){
                           
                     //remove old profile
                     unlink($oldpic);    
                           //upload profile pic
                    $model->profilepic->saveAs('uploads/' . $model->profilepic->baseName.$model->username . '.' . $model->profilepic->extension);
                    $documenturl='uploads/' . $model->profilepic->baseName.$model->username. '.' . $model->profilepic->extension;
                    $model->profilepic=$documenturl;
                      }
                      
                      
                        if($model->save(false)){
                            
                            //send email
                        
                        $emailer = new \webvimark\modules\UserManagement\models\forms\ConfirmEmailForm();
                        $emailer->sendEmail($model->email,$model->username);
                    
                        Yii::$app->session->setFlash('success', 'Details updated successfully');
			return $this->redirect(['viewprofile',	'id' => $model->id]);
                        }
                        else{
                        Yii::$app->session->setFlash('error', 'Failed to update details');
			return $this->redirect(['viewprofile']);  
                        }
		}
                       
		        return $this->renderIsAjax('_form', compact('model'));
	}
        
        
         public function actionActualresetpassword($id){
           
            $model= new \frontend\models\User();
            
            
            $model= $model->find()->where(['id'=>$id])->one();
            $password= Yii::$app->security->generateRandomString(8);
            
           
            $model->password_hash= Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->firstlogin=1;
            
            if($model->save(false)){
                //send mail
                
                $emailer = new \webvimark\modules\UserManagement\models\forms\ConfirmEmailForm();
                $emailer->sendEmail($model->email,$model->username,$password);
                Yii::$app->session->setFlash('success', 'Password reset Successfully.');
                
                 return $this->redirect(['index']);  
            }
            else{
                Yii::$app->session->setFlash('error', 'Password failed to reset.');
                  return $this->redirect(['index']);   
            }
        }
        
        
         public function actionBlockuser($id) {
              $model= new \frontend\models\User();
              $model= $model->find()->where(['id'=>$id])->one();
       
            //$STATUS=$model->APPROVED;
              $model->blocked=1;
            if($model->save(FALSE)){
            
                  Yii::$app->session->setFlash('success', 'User blocked Successfully.');
                  return $this->redirect(['index']);                                      
           
             }
        
            else{
            
                 Yii::$app->session->setFlash('error', 'User blocking failed.Please Try Again');
                  return $this->redirect(['index']); 
            }
            
         }
    
         
           public function actionUnblockuser($id) {
              $model= new \frontend\models\User();
              $model= $model->find()->where(['id'=>$id])->one();
       
            //$STATUS=$model->APPROVED;
              $model->blocked=0;
            if($model->save(FALSE)){
            
                  Yii::$app->session->setFlash('success', 'User unblocked Successfully.');
                  return $this->redirect(['index']);                                      
           
             }
        
            else{
            
                 Yii::$app->session->setFlash('error', 'User unblocking failed.Please Try Again');
                  return $this->redirect(['index']); 
            }
            
         }
         
         
         //approve users
         public function actionApproveusers(){
             
             $model= new \frontend\models\User();
             $model= $model->find()->where(['approved'=>0])->all();
             
             
             return $this->render('index_1',[
                'model'=>$model,
            ]);
         }
        
         
         //actualapproveuser 
         
         public function actionActualapproveuser($id){
             
             $model= new \frontend\models\User();
             $model= $model->find()->where(['id'=>$id])->one();
             
             $model->approved=1;
             
             if($model->save(false)){
                 //send email to user
                  $message='Approval';
                  $password=NULL;
                  $emailer = new \webvimark\modules\UserManagement\models\forms\ConfirmEmailForm();
                  $emailer->sendEmail($model->email,$model->username,$password,$message);
                 Yii::$app->session->setFlash('success', 'User account approved successfully');
                  return $this->redirect(['approveusers']); 
             }
             
             else{
                 
                 Yii::$app->session->setFlash('error', 'User account approval failed.Please try again');
                 return $this->redirect(['approveusers']); 
             }
             
             
         }





         public function actionBoard()
	{
		$model = new User(['scenario'=>'newUser']);

		if ( $model->load(Yii::$app->request->post()) && $model->save() )
		{
			return $this->redirect(['view',	'id' => $model->id]);
		}

		return $this->renderIsAjax('board', compact('model'));
	}
        
        public function actionViewboards(){
            
            $model= new \frontend\models\User();
            $model= $model->find()->all();
            
             return $this->render('allboards',[
                'model'=>$model,
                
            ]);
        }
        
        public function actionViewprofile(){
            
            $model= new \frontend\models\User();
            $model=$model->find()->where(['id'=>Yii::$app->user->identity->id])->one();
            
            return $this->render('myprofile',[
                'model'=>$model,
                
            ]);
            
        }

	/**
	 * @param int $id User ID
	 *
	 * @throws \yii\web\NotFoundHttpException
	 * @return string
	 */
	public function actionChangePassword($id)
	{
		$model = User::findOne($id);

		if ( !$model )
		{
			throw new NotFoundHttpException('User not found');
		}

		$model->scenario = 'changePassword';

		if ( $model->load(Yii::$app->request->post()) && $model->save() )
		{
			return $this->redirect(['view',	'id' => $model->id]);
		}

		return $this->renderIsAjax('changePassword', compact('model'));
	}

}
