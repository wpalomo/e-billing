<?php

class UsuariosController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','assign','admin','delete','index','view'),
				'expression'=>'Yii::app()->authmanager->checkAccess("Administrador",Yii::app()->user->id) '
			),
		
                    array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('reset'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
        
        
         public function actionReset()
        {
            $error="";
            if( isset($_POST["password0"])&& isset($_POST["password1"] ) && isset($_POST["password2"] )){
                $model=  Usuarios::model()->findByPk(Yii::app()->user->id);
                if ($model->usr_password==md5($_POST["password0"])){
                    $model->usr_password=  md5($_POST["password1"]);
                    if($model->save())$error="Actualizado correctamente"; ;
                }
                else $error="La clave actual es incorrecta";
            }
           $this->render('reset',array('error'=>$error));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Usuarios;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Usuarios']))
		{
			      $model->attributes=$_POST['Usuarios'];
                       if (!empty($model->usr_password))
                        $model->usr_password=md5($model->usr_password);
			if($model->save())
				$this->redirect(array('admin','id'=>$model->usr_codigo));
                        
		}

		$this->render('create',array(
			'model'=>$model,
		));
   
	}
        
      public function actionAssign($id)
        {
            if (Yii::app()->authmanager->checkAccess($_GET["item"],$id ))
              Yii::app()->authmanager->revoke($_GET["item"],$id );
            else
              Yii::app()->authmanager->assign($_GET["item"],$id );
            
            $this->render('view',array(
			'model'=>$this->loadModel($id),
		));
            
          //  $this->redirect(array("update","id"=>$id));
	}
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Usuarios']))
		{
                        if (!empty($_POST['Usuarios']['usr_password']) && $_POST['Usuarios']['usr_password']!= $model->usr_password)
                          $_POST['Usuarios']['usr_password']=  md5($_POST['Usuarios']['usr_password']);
                        
                        $model->attributes=$_POST['Usuarios'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->usr_codigo));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
              $documentos=  Documentos::model()->find(array(
                                'condition' => 'usr_codigo=:usr_codigo  ',
                                'params' => array(':usr_codigo'=>$id) 
                            ));
              
              if($documentos==null)
		$this->loadModel($id)->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Usuarios');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Usuarios('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Usuarios']))
			$model->attributes=$_GET['Usuarios'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
        

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Usuarios the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Usuarios::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Usuarios $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='usuarios-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
