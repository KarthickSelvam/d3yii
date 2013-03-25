<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
                    'coco'=>array(
                'class'=>'CocoAction',
            ),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
        public function actionCsv()
	{
          

            //if(isset($_POST['ajax']))
		//{
                  //echo '<pre>';
           // print_r($_REQUEST);
            $file=fopen($_REQUEST['fullpath'], 'rb');
            while(!feof($file)) {
            $csv[]=fgetcsv($file);
            }
            //$csv=fgetcsv($file,1000);
         //   print_r($csv);
            //exit;
            foreach ($csv as $id=>$val){
                  foreach($val as $key2=>$val2){
                          $result[$id][$csv[0][$key2]]=$val2;
                  }
            }
            
            // Remove title row
            $title_row=0;
            unset($result[$title_row]);
            
            $nodes = array();
            $tree = array();
            foreach ($result as &$node) {
  //$node["children"] = array();
  $id = $node["Id"];
  $parent_id = $node["Report to"];
  $nodes[$id] =& $node;
  if (array_key_exists($parent_id, $nodes)) {
    $nodes[$parent_id]["children"][] =& $node;
  } else {
    $tree[] =& $node;
  }
}
print_r(json_encode(array("Name"=>"Top","children"=>$tree)));
			// print_r(json_encode($_REQUEST));
			Yii::app()->end();
		//}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
        
     /*   public function actionCoco() {
        // userdata is the same passed via widget config.
      //  echo"<pre>";
       // print_r($_REQUEST);
        //print_r($_FILES);
        //exit;
    }*/
        public function actionCreate()
	{
           if(Yii::app()->request->isAjaxRequest){
               echo '<pre>';
               print_r($_SERVER);
           }else{
            $model=new MyModel;
		$this->render('create',array('model'=>$model));
	}
        }
          public function actionWorldmap()
	{
           // $model=new MyModel;
		$this->render('worldmap');
        }
        
        public function actionForce()
	{
            $model=new MyModel;
		$this->render('force',array('model'=>$model));
        }
        
        public function actionStep()
	{
            $model=new MyModel;
		$this->render('step',array('model'=>$model));
        }
}