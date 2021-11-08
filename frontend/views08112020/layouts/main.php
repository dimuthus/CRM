<?php
use yii\helpers\Html;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use yii\helpers\Url;

use frontend\models\Marquee;
Yii::$app->view->registerMetaTag(['http-equiv' => 'refresh', 'content' => Yii::$app->user->authTimeout + 5]);

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" href="<?= Url::base()?>/images/favicon.ico" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">
        .navbar .container {
            padding: 0px !important;
        }
        .navbar-collapse {
            padding: 0px !important;
        }
        .dropdown-menu a:hover{
            background: #bbb !important;
        }
        .navbar {
            border-radius: 0px !important;
        }
        .navbar-custom {
  background-color: #19181733 ;
  border-color: #812B19 ;
  background-image: -webkit-gradient(linear, left 0%, left 100%, from(#ffffff), to(#812B19 ));
  background-image: -webkit-linear-gradient(top, #232323, 0%, #812B19 , 100%);
  background-image: -moz-linear-gradient(top, #232323 0%, #812B19  100%);
    background-image: linear-gradient(to bottom, #232323 0%, #191717 100%);
	background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#232323', endColorstr='#ffd9edf7', GradientType=0);
}
.navbar-custom .navbar-brand {
  color: cyan;
}
.navbar-custom .navbar-brand:hover,
.navbar-custom .navbar-brand:focus {
  color: #0a134f;
  background-color: transparent;
}
.navbar-custom .navbar-text {
  color: cyan;
}
.navbar-custom .navbar-nav > li:last-child > a {
  border-right: 1px solid #bee0f1;
}
.navbar-custom .navbar-nav > li > a {
  color: cyan;
  border-left: 1px solid #bee0f1;
}
.navbar-custom .navbar-nav > li > a:hover,
.navbar-custom .navbar-nav > li > a:focus {
  color: #027499;
  background-color: transparent;
}
.navbar-custom .navbar-nav > .active > a,
.navbar-custom .navbar-nav > .active > a:hover,
.navbar-custom .navbar-nav > .active > a:focus {
  color: #027499;
  background-color: #bee0f1;
  background-image: -webkit-gradient(linear, left 0%, left 100%, from(#bee0f1), to(#e8f4fa));
  background-image: -webkit-linear-gradient(top, #bee0f1, 0%, #e8f4fa, 100%);
  background-image: -moz-linear-gradient(top, #bee0f1 0%, #e8f4fa 100%);
  background-image: linear-gradient(to bottom, #bee0f1 0%, #e8f4fa 100%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffbee0f1', endColorstr='#ffe8f4fa', GradientType=0);
}
.navbar-custom .navbar-nav > .disabled > a,
.navbar-custom .navbar-nav > .disabled > a:hover,
.navbar-custom .navbar-nav > .disabled > a:focus {
  color: #cccccc;
  background-color: transparent;
}
.navbar-custom .navbar-toggle {
  border-color: #dddddd;
}
.navbar-custom .navbar-toggle:hover,
.navbar-custom .navbar-toggle:focus {
  background-color: #dddddd;
}
.navbar-custom .navbar-toggle .icon-bar {
  background-color: #cccccc;
}
.navbar-custom .navbar-collapse,
.navbar-custom .navbar-form {
  border-color: #bcdff1;
}
.navbar-custom .navbar-nav > .dropdown > a:hover .caret,
.navbar-custom .navbar-nav > .dropdown > a:focus .caret {
  border-top-color: #027499;
  border-bottom-color: #027499;
}
.navbar-custom .navbar-nav > .open > a,
.navbar-custom .navbar-nav > .open > a:hover,
.navbar-custom .navbar-nav > .open > a:focus {
  background-color: #bee0f1;
  color: #027499;
}
.navbar-custom .navbar-nav > .open > a .caret,
.navbar-custom .navbar-nav > .open > a:hover .caret,
.navbar-custom .navbar-nav > .open > a:focus .caret {
  border-top-color: #027499;
  border-bottom-color: #027499;
}
.navbar-custom .navbar-nav > .dropdown > a .caret {
  border-top-color: cyan;
  border-bottom-color: cyan;
}
@media (max-width: 767) {
  .navbar-custom .navbar-nav .open .dropdown-menu > li > a {
    color: cyan;
  }
  .navbar-custom .navbar-nav .open .dropdown-menu > li > a:hover,
  .navbar-custom .navbar-nav .open .dropdown-menu > li > a:focus {
    color: #027499;
    background-color: transparent;
  }
  .navbar-custom .navbar-nav .open .dropdown-menu > .active > a,
  .navbar-custom .navbar-nav .open .dropdown-menu > .active > a:hover,
  .navbar-custom .navbar-nav .open .dropdown-menu > .active > a:focus {
    color: #027499;
    background-color: #bee0f1;
  }
  .navbar-custom .navbar-nav .open .dropdown-menu > .disabled > a,
  .navbar-custom .navbar-nav .open .dropdown-menu > .disabled > a:hover,
  .navbar-custom .navbar-nav .open .dropdown-menu > .disabled > a:focus {
    color: #cccccc;
    background-color: transparent;
  }
}
.navbar-custom .navbar-link {
  color: cyan;
}
.navbar-custom .navbar-link:hover {
  color: #027499;
}


.myclass img {
    width:200px;
    height:87px;
    margin-top:-32px;
    margin-left:-50px;
}

.logoS:before {
  color: #bee0f1;
  content: "Welcome, ";
}

.logoS2:before {
  color: red;
  content: "HIGHLY RESTRICTED!  ";
}
    </style>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body data-root="<?= Url::base()?>">
    <?php $this->beginBody() ?>

    <div class="wrap bodyBack">
        <div id="top" style="width:100%; z-index:120">
           

            <?php
               NavBar::begin([
                    'brandLabel' => Html::img('@web/images/logo.png', ['alt'=>'BOC CRM']),
                    'brandOptions' => ['class' => 'myclass'],//options of the brand
                    'options' => [
                        'class' => 'navbar-custom navbar-fixed-top',
                        'activateItems' => 'true',
                        'activateParents' => 'true',
                    ],
                ]);
                $menuItems = [
					[
                        'label' => 'Outbound',
                        'url' => ['/myinbox'],
                        'visible' => Yii::$app->user->can('Dashboard Page'),
                        'active' =>  $this->context->route == 'myinbox/index'
                    ],
                    [
                        'label' => 'Inbound',
                        'url' => ['/search'],
                        'visible' => Yii::$app->user->can('Search Page'),
                        'active' =>  $this->context->route == 'search/index' || (strpos($this->context->route, 'customer/') !== FALSE)
                    ],
                   [
                        'label' => 'Cases',
                        'url' => ['/cases'],
                        'visible' => Yii::$app->user->can('Service Request Page'),
                        'active' => $this->context->route == 'cases/index'
                    ], 
                    [
                        'label' => 'Dashboard',
                        'url' => ['/dashboard'],
                        'visible' => Yii::$app->user->can('Dashboard Page'),
                        'active' => $this->context->route == 'dashboard/index'
                    ],
                   /* [
                        'label' => 'Case Aging',
                        'url' => ['/request/aging'],
                        'visible' => Yii::$app->user->can('SR Aging Page'),
                        'active' => $this->context->route == 'request/aging'
                    ],*/
                    [
                        'label' => 'Reports', 
                        'items' => [    
                            [
                                'label' => 'Inbound Report', 
                                'url' => ['/report/complete'], 
                                'visible' => Yii::$app->user->can('Reporting Page (Inbound)'),
                                'active' => $this->context->route == 'report/complete'
                            ],
                            [
                                'label' => 'Outbound Report', 
                                'url' => ['/report/outbound'], 
                                'visible' => Yii::$app->user->can('Reporting Page (Outbound)'),
                                'active' => $this->context->route == 'report/outbound'
                            ],
							[
                                'label' => 'CSAT Report', 
                                'url' => ['/report/csat'], 
                                'visible' => Yii::$app->user->can('Reporting Page (Csat)'),
                                'active' => $this->context->route == 'report/csat'
                            ],
							
							[
                                'label' => 'Activity Report', 
                                'url' => ['/report/activitylog'], 
                                'visible' => Yii::$app->user->can('Permission Management Module'),
                                'active' => $this->context->route == 'report/activitylog'
                            ],
                        ], 
                        'visible'=> Yii::$app->user->can('Reporting Page (Inbound)') || 
                                    Yii::$app->user->can('Reporting Page (Outbound)') ||  Yii::$app->user->can('Reporting Page (Csat)') ,
                        'active' => (strpos($this->context->route, 'report/') !== FALSE)
                    ],

					[
                        'label' => 'Management',
						 'visible' => Yii::$app->user->can('Distribution Management Module'),

                        'items' => [
                            [
                                'label' => 'Distribute',
                                'url' => ['/distribution/distribute'],
                                'visible' => Yii::$app->user->can('Distribution Management Module'),
                                'active' => $this->context->route == 'distribution/distribute'
                            ],
                            [
                                'label' => 'Redistribute',
                                'url' => ['/redistribution/create'],
                                'visible' => Yii::$app->user->can('Distribution Management Module'),
                                'active' => $this->context->route == 'redistribution/create'
                            ],
							 
                            
                        ],
                    //    'visible'=> Yii::$app->user->can('Reporting Page (Complete)') ||
                    //                Yii::$app->user->can('Reporting Page (Summary)'),
                        'active' => (strpos($this->context->route, 'distribution/') !== FALSE)
                    ],

                    [
                        'label' => 'Tools',
                        'items' => [
                            '<li class="dropdown-header" style="display:'
                            .
                            (
                                Yii::$app->user->can('User Management Module') ||
                                Yii::$app->user->can('Role Management Module') ||
                                Yii::$app->user->can('Permission Management Module')
                                ?'block':'none'
                            )
                            .
                            '">User Administration</li>',
                            [
                                'label' => 'User Management',
                                'url' => ['/tools/user'],
                                'visible' => Yii::$app->user->can('User Management Module'),
                                'active' => $this->context->route == 'tools/user/index'
                            ],
                            [
                                'label' => 'Role Management',
                                'url' => ['/tools/user/role'],
                                'visible' => Yii::$app->user->can('Role Management Module'),
                                'active' => $this->context->route == 'tools/user/role'
                            ],
                            [
                                'label' => 'Permission Management',
                                'url' => ['/tools/permission'],
                                'visible' => Yii::$app->user->can('Permission Management Module'),
                                'active' => $this->context->route == 'tools/user/permission'
                            ],
                            '<li class="divider" style="display:'
                            .
                            (
                                (
                                    (
                                        Yii::$app->user->can('User Management Module') ||
                                        Yii::$app->user->can('Role Management Module') ||
                                        Yii::$app->user->can('Permission Management Module')
                                    ) &&
                                    Yii::$app->user->can('Dropdown Management Module') ||
                                    Yii::$app->user->can('Marquee Management Module')
                                )
                                ?'block':'none'
                            )
                            .
                            '"></li>',
                            '<li class="dropdown-header" style="display:'
                            .
                            (
                                Yii::$app->user->can('Dropdown Management Module')
                                ?'block':'none'
                            )
                            .
                            '">Dropdown Administration</li>',
                            [
                                'label' => 'Case',
                                'items' => [
                                    /*[
                                        'label' => 'Priority',
                                        'url' => ['/tools/dropdown/case-priority'],
                                        'active' => $this->context->route == 'tools/dropdown/case-priority'
                                    ],*/
                                    /*[
                                        'label' => 'Caller Type',
                                        'url' => ['/tools/dropdown/callertype'],
                                        'active' => $this->context->route == 'tools/dropdown/callertype'
                                    ],*/
                                    [
                                        'label' => 'Channel Type',
                                        'url' => ['/tools/dropdown/interaction-channel'],
                                        'active' => $this->context->route == 'tools/dropdown/interaction-channel'
                                    ],
									                  [
                                        'label' => 'Category1',
                                        'url' => ['/tools/dropdown/outcome-code1'],
                                        'active' => $this->context->route == 'tools/dropdown/outcome-code1'
                                    ],
									                  [
                                        'label' => 'Category2',
                                        'url' => ['/tools/dropdown/outcome-code2'],
                                        'active' => $this->context->route == 'tools/dropdown/outcome-code2'
                                    ],
                                    [
                                        'label' => 'Category3',
                                        'url' => ['/tools/dropdown/outcome-code3'],
                                        'active' => $this->context->route == 'tools/dropdown/outcome-code3'
                                    ],
									
                                    [
                                        'label' => 'Case Status',
                                        'url' => ['/tools/dropdown/case-status'],
                                        'active' => $this->context->route == 'tools/dropdown/case-status'
                                    ],
                                    [
                                        'label' => 'severity levels',
                                        'url' => ['/tools/dropdown/severity-level'],
                                        'active' => $this->context->route == 'tools/dropdown/severity-level'
                                    ],
                                  

                                ],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                            ],
                            [
                                'label' => 'Contacts',
                                'items' => [

                                    [
                                        'label' => 'Salutation',
                                        'url' => ['/tools/dropdown/salutation'],
                                        'active' => $this->context->route == 'tools/dropdown/salutation'
                                    ],
                                    [
                                        'label' => 'Preferred Language',

                                        'url' => ['/tools/dropdown/contact-language'],
                                        'active' => $this->context->route == 'tools/dropdown/contact-language'
                                    ],
                                    [
                                'label' => 'Country',
                                'url' => ['/tools/dropdown/country'],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                'active' => $this->context->route == 'tools/dropdown/country'
                            ],
                            [
                                'label' => 'City',
                                'url' => ['/tools/dropdown/city'],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                'active' => $this->context->route == 'tools/dropdown/city'
                            ],
									
                                ],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                            ],

                            [
                                'label' => 'Interaction',
                                'items' => [
                                    [
                                        'label' => 'Status',
                                        'url' => ['/tools/dropdown/case-status'],
                                        'active' => $this->context->route == 'tools/dropdown/case-status'
                                    ],
									
                                ],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                            ],



                            ['label'=>'Campaign',
                              'items'=>[

                                                  [
                                'label' => 'Create Campaign',
                                'url' => ['/campaign/create'],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                'active' => $this->context->route == 'campaign/create'
                            ],
                            [
                                'label' => 'Campaign List',
                                'url' => ['/campaign/index'], //must define Action such as index to be actively selected item.
                                'visible' => Yii::$app->user->can('Distribution Management Module'),
                                'active' => $this->context->route == 'campaign'
                            ],


                              ],       

                                'visible' => Yii::$app->user->can('Dropdown Management Module'),



                            ],
                            ['label'=>'Survey',
                                    'items'=>[
                                           [
                                        'label' => 'Create Survey Questionnaire',
                                        'url' => ['/survey/survey-question/index'],
                                        'active' => $this->context->route == '/survey/survey-question/index'
                                        ],

                                           [
                                        'label' => 'Create Survey Question Options for MCQs',
                                        'url' => ['/survey/survey-response-choice/survey-response-list'],
                                        'active' => $this->context->route == '/survey/survey-response-choice/survey-response-list'
                                        ],
                                        [
                                        'label' => 'Assign Questionnaire to Campaign-survey',
                                        'url' => ['/survey/survey-question-order/index'],
                                        'active' => $this->context->route == '/survey/survey-question-order/index'
                                        ],
                                         ],
                            ],
                            
							[
                                'label' => 'State',
                                'url' => ['/tools/dropdown/state'],
                                'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                'active' => $this->context->route == 'tools/dropdown/state'
                            ],

                            // [
                            //                   'label' => 'Event',
                            //                   'url' => ['/tools/dropdown/events'],
                            //                   'visible' => Yii::$app->user->can('Dropdown Management Module'),
                            //                   'active' => $this->context->route == 'tools/dropdown/events'
                            //               ],
                             
                           
								
								  ['label'=>'Data Upload',
                                    'items'=>[
                                           [
                                        'label' => 'Campaign-Customer Upload',
                                        'url' => ['/customer/campagin-data-upload'],
                                        'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                        'active' => $this->context->route == '/customer/campagin-data-upload'
                                        ],

                                           [
                                     'label' => 'Customer Data Upload(MNL)',
                                        'url' => ['/customer/daily-customer-update'],
                                        'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                        'active' => $this->context->route == '/customer/daily-customer-update'
                                        ],
                                        [
                                       'label' => 'Product Data Upload(MNL)',
                                        'url' => ['/product/daily-product-update'],
                                        'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                        'active' => $this->context->route == '/product/daily-product-update'
                                        ],
                                         ],
                            ],
								
								
								
                                // [
                                                            // 'label' => 'Upload Customers Remote',
                                                            // 'url' => ['/customer/uploadremote'],
                                                            // 'visible' => Yii::$app->user->can('Dropdown Management Module'),
                                                            // 'active' => $this->context->route == '/customer/uploadremote'
                                // ],

                            '<li class="divider" style="display:'
                            .
                            (
                                (
                                    (
                                        Yii::$app->user->can('Dropdown Management Module') ||
                                        Yii::$app->user->can('User Management Module') ||
                                        Yii::$app->user->can('Role Management Module') ||
                                        Yii::$app->user->can('Permission Management Module')
                                    ) &&
                                    Yii::$app->user->can('Marquee Management Module')
                                )
                                ?'block':'none'
                            )
                            .
                            '"></li>',
                            '<li class="dropdown-header" style="display:'
                            .
                            (
                                Yii::$app->user->can('Marquee Management Module')
                                ?'block':'none'
                            )
                            .
                            '">Others</li>',
                            [
                                'label' => 'Marquee Management',
                                'url' => ['/tools/marquee'],
                                'visible' => Yii::$app->user->can('Marquee Management Module'),
                                'active' => $this->context->route == 'tools/dropdown/marquee'
                            ],
                    ],
                    'visible'=> Yii::$app->user->can('User Management Module') ||
                                Yii::$app->user->can('Role Management Module') ||
                                Yii::$app->user->can('Permission Management Module') ||
                                Yii::$app->user->can('Dropdown Management Module'),
                    'active' => (strpos($this->context->route, 'tools/') !== FALSE)],
                ];

                echo NavX::widget([
                    'options' => ['class' => 'navbar-nav navbar-left'],
                    'items' => $menuItems,
                ]); ?>
				  <div class="log-info" style="margin-top:10px;">
                        <div class="row" >
                            <div class="col-sm-8">
                        
                        <div ><?= Html::img('@web/images/highlt_retricted_s.png', ['alt'=>'Highly Restricted!']) ?></div>
                            <div class="logoS">	<?= Html::a(Yii::$app->user->identity->username, ['/user']) ?></div>
                        
                            </div>
                            <div class="col-sm-4" >
                        <?= Html::a('<span class="glyphicon glyphicon-log-out"></span> logout', ['/user/logout'], ['data-method'=>'post',
                                                                'class' => 'btn btn-xs btn-danger console-button']) ?>
                        </div>
                        </div>
<!--                        </div>-->
                </div>
			<?php	
                NavBar::end();
            ?>
        </div>
<div style="margin-top:50px;">
        <?php \yii\widgets\Pjax::begin(['id' => 'marquee','enablePushState'=>FALSE,'timeout' => 10000, 'clientOptions' => ['container' => 'pjax-container']]); ?>

            <?php $marquee = Marquee::findOne(1);
                if($marquee->enabled == 1) { ?>
                    <div id="marquee-box" class="marquee alert-info">
                        <marquee scrollamount="<?= $marquee->speed; ?>"><?= $marquee->message; ?></marquee>
                    </div>
            <?php } ?>

            <?php \yii\widgets\Pjax::end(); ?>
            </div>
        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
		
        <?= Alert::widget() ?>
		<?= $this->render('flashes');?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="text-center"><?= Yii::$app->name ?> &copy; <?=date('Y') ?> by Scicom (MSC) Berhad.All rights reserved.</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

<script>


    $(document).ready(function(){
        //$("#clock1").MyDigitClock();
        $("#top").sticky({ topSpacing: 0 });
    });
</script>
</html>
<?php $this->endPage() ?>
