<?php
use yii\helpers\Html;

?>
        <div class="row">
            <?php foreach ($model as $member) { ?>
            <div class="col-lg-4">
                <div class="contact-box">
                    <a href="profile.html">
                    <div class="col-sm-4">
                        <div class="text-center">
                                              <?php
                if (!Yii::$app->user->isGuest) {
                echo Html::img(Yii::$app->getUrlManager()->getBaseUrl().'/'.$member['profilepic'], ['alt'=>'some', 'class'=>'img-circle m-t-xs img-responsive']);
   }
            ?>
                            <div class="m-t-xs font-bold"><?=$member['boardposition'];?></div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <h3><strong><?=$member['fullnames'];?></strong></h3>
                        <p><i class="fa fa-map-marker"></i> Board Member</p>
                        <address>
                            
                            <abbr title="Phone">Phone:</abbr> <?=$member['phone'];?> <br>
                            <abbr title="Phone">Email:</abbr> <?=$member['email'];?> <br>
                            
                            <p> <?=$member['boarddesc'];?> </p>
                        </address>
                    </div>
                    <div class="clearfix"></div>
                        </a>
                </div>
            </div>
            <?php } ?>
        </div>
      