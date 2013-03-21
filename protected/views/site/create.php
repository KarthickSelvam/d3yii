<?php
/* @var $this SiteController */
/* @var $model MyModel */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>
<script> 
function getfile(fileinfo){
    
}
</script>
<h1>Create Chart</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
</p>

<div class="form">
<?php
$this->widget('ext.coco.CocoWidget'
        ,array(
            'id'=>'cocowidget1',
            'onCompleted'=>'function(id,filename,jsoninfo){ getfile(jsoninfo); }',
            'onCancelled'=>'function(id,filename){ alert("cancelled"); }',
            'onMessage'=>'function(m){ alert(m); }',
            'allowedExtensions'=>array('jpeg','jpg','gif','png'), // server-side mime-type validated
            'sizeLimit'=>2000000, // limit in server-side and in client-side
            'uploadDir' => 'assets/files', // coco will @mkdir it
            // this arguments are used to send a notification
            // on a specific class when a new file is uploaded,
            'receptorClassName'=>'application.models.MyModel',
            //'receptorClassName'=>'application.controllers.SiteController',
            'methodName'=>'myFileReceptor',
            'userdata'=>$model,
            // controls how many files must be uploaded
            'maxUploads'=>1, // defaults to -1 (unlimited)
            'maxUploadsReachMessage'=>'No more files allowed', // if empty, no message is shown
            // controls how many files the can select (not upload, for uploads see also: maxUploads)
            'multipleFileSelection'=>false, // true or false, defaults: true
            'buttonText'=>'Find & Upload',
            'dropFilesText'=>'Drop Files Here !',
            'htmlOptions'=>array('style'=>'width: 300px;'),
            //'defaultControllerName'=>'site',
            //'defaultActionName'=>'Coco',
        ));
    
?>
</div><!-- form -->

<?php endif; ?>