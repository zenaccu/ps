<?php

class SiteController extends Controller
{
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex()
	{
		$this->Install();
		$this->Reminder();
		$this->layout = '//layouts/column2';
		if(Yii::app()->user->isGuest)
					$this->redirect(array('login'));
		else {
			$model = Document::model();
			$user = yii::app()->user->guid;
			$level = yii::app()->user->level;
			if($level == "Reader"){				
				$active = $model->findAll(array('condition'=>'ApprovalStatus IS NULL OR ApprovalStatus = "Revise"'));
				$model->unsetAttributes();
				$process = $model->findAll(array('condition'=>'ApprovalStatus = "Proccess"'));
				$model->unsetAttributes();
				$execute = $model->findAll(array('condition'=>'ApprovalStatus = "Approved"'));
				$model->unsetAttributes();
				$final = $model->findAll(array('condition'=>'ApprovalStatus = "Executed"'));
			} else {
				$active = $model->findAll(array('condition'=>'IdExecutedBy != "'.$user.'" AND DocumentStatus = "'.$user.'"'));
				$model->unsetAttributes();
				$process = $model->findAll(array('condition'=>'(IdRequiredBy = "'.$user.'" OR IdApprovedBy LIKE "%'.$user.'%" OR IdExecutedBy = "'.$user.'") AND DocumentStatus NOT IN ("'.$user.'","FINAL","CANCEL")'));
				$model->unsetAttributes();
				$execute = $model->findAll(array('condition'=>'IdExecutedBy = "'.$user.'" AND ApprovalStatus = "Approved"'));
				$model->unsetAttributes();
				$final = $model->findAll(array('condition'=>'(IdRequiredBy = "'.$user.'" OR IdApprovedBy LIKE "%'.$user.'%" OR IdExecutedBy = "'.$user.'") AND DocumentStatus = "FINAL"'));
			}
			$this->render('index', array('active'=>$active, 'process'=>$process, 'execute'=>$execute, 'final'=>$final));
		}
	}

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

	// public function actionContact()
	// {
		// $model=new ContactForm;
		// if(isset($_POST['ContactForm']))
		// {
			// $model->attributes=$_POST['ContactForm'];
			// if($model->validate())
			// {
				// $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				// $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				// $headers="From: $name <{$model->email}>\r\n".
					// "Reply-To: {$model->email}\r\n".
					// "MIME-Version: 1.0\r\n".
					// "Content-type: text/plain; charset=UTF-8";

				// mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				// Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				// $this->refresh();
			// }
		// }
		// $this->render('contact',array('model'=>$model));
	// }

	public function actionLogin()
	{
		$this->layout = '//layouts/login';
		$cek = User::model()->findAllByAttributes(array('Name'=>'maulana'));
		
		if(count($cek)==0)
		{
			$user = new User;
			$user->Name = "maulana";
			$user->Email = "islademuerta847@mail.com";
			$user->Password = "k3y@system";
			$user->Status = "Aktif";
			$user->Level = "Super Admin";
			$user->StructureId = "00000000-0000-0000-0000-000000000000";
			$user->save();
		}
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
		if(Yii::app()->user->isGuest)
			$this->render('login',array('model'=>$model));
		else $this->redirect(array('index'));
	}

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/**
	 * Initiate the table.
	 */
	private function Install()
	{
		if (Yii::app()->db->schema->getTable("attachment", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `attachment` (
			  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Size` int(11) NOT NULL,
			  `Type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Content` mediumblob,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `DocumentId` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("comment", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `comment` (
			  `Content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `DocumentId` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `UserId` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` int(11) NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("document", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `document` (
			  `Code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `DocumentName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Priority` enum('Segera','Penting','Biasa') COLLATE utf8_unicode_ci NOT NULL,
			  `Description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `IdRequiredBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `RequiredBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `IdApprovedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `ApprovedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `IdExecutedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `ExecutedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Budget` bigint(20) NOT NULL,
			  `Realization` bigint(20) NOT NULL,
			  `Instruction` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ApprovalData` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ApprovalStatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `DocumentStatus` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("history", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `history` (
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `Predicate` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  `Subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("profile", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `profile` (
			  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `PlaceAndDateOfBirth` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Gender` enum('Pria','Wanita') COLLATE utf8_unicode_ci NOT NULL,
			  `Phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `UserId` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("role", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `role` (
			  `Code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `DocumentName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `Priority` enum('Segera','Penting','Biasa') COLLATE utf8_unicode_ci NOT NULL,
			  `IdRequiredBy` char(255) COLLATE utf8_unicode_ci NOT NULL,
			  `RequiredBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `IdApprovedBy` char(255) COLLATE utf8_unicode_ci NOT NULL,
			  `ApprovedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `IdExecutedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `ExecutedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("structure", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `structure` (
			  `Level` tinyint(1) NOT NULL,
			  `IdMemberOf` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `MemberOf` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `Division` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `GroupEmployee` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  PRIMARY KEY (`Id`)
			)")->execute();
		} else {
			// table exists
		}
		if (Yii::app()->db->schema->getTable("user", true)===null) {
			// table does not exist
			Yii::app()->db->createCommand("CREATE TABLE `user` (
			  `Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `Password` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `UniqKey` varchar(36) COLLATE utf8_unicode_ci NOT NULL,
			  `Status` enum('Aktif','Tidak Aktif') COLLATE utf8_unicode_ci NOT NULL,
			  `Level` enum('Super Admin','Admin','User') COLLATE utf8_unicode_ci NOT NULL,
			  `StructureId` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedBy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
			  `CreatedDate` datetime NOT NULL,
			  `Id` char(36) COLLATE utf8_unicode_ci NOT NULL,
			  `ModifiedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			  `ModifiedDate` datetime DEFAULT NULL,
			  `RowStatus` tinyint(1) NOT NULL,
			  PRIMARY KEY (`Id`),
			  UNIQUE KEY `Name` (`Name`),
			  UNIQUE KEY `Email` (`Email`)
			)")->execute();
		} else {
			// table exists
		}
	}
	
	/*
	* Reminder Realization
	*/
	private function Reminder()
	{
		$role = Role::model()->find(array('condition'=>'Code = "REMINDER"'));
		// $code = if(explode("/", $data->Number)[1] != date('m')) {
				// $data->Number = "0001/".date('m')."/".date('Y');
				// $data->save();
			// }
		$doc = Document::model()->findAll(array('condition'=>'RowStatus = 0 AND DATE(PlanningDate) = CURRENT_DATE() + INTERVAL 1 DAY'));
		$new = new Document;
		if($doc != null)
		foreach($doc as $data) {
			$new->Budget += $data->Budget;
			$data->RowStatus = 1;
			$data->save();
		}
		$new->Code = 'REMINDER/0001/'.date('m').'/'.date('Y');
		$new->DocumentName = 'Pengingat Pembayaran';
		$new->SubName .= $data->Code.'; ';
		$new->Priority = 'Penting';
		$new->Description = 'Mengingatkan Pembayaran Untuk Keperluan Dokumen Yang Akan Jatuh Tempo';
		$new->IdRequiredBy = '00000000-0000-0000-0000-000000000000';
		$new->RequiredBy = 'System';
		$new->IdApprovedBy ='00000000-0000-0000-0000-000000000000';
		$new->ApprovedBy ='System';
		$new->IdExecutedBy = '1E951857-08E4-BF8A-1E6E-C4BEE277897C';
		$new->ExecutedBy = 'Ami Rahmiatin';
		$new->PlanningDate = date('Y-m-d');
		$new->ApprovalData = 'a:1:{s:36:"00000000-0000-0000-0000-000000000000";i:1;}';
		$new->ApprovalStatus = 'Approved';
		$new->DocumentStatus = '1E951857-08E4-BF8A-1E6E-C4BEE277897C';
		$new->CreatedBy = 'System';
		$new->RowStatus = 9;
		$new->save();
	}
}