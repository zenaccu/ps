<?php
/* @var $this DocumentController */
/* @var $model Document */

$this->breadcrumbs=array(
	'Documents'=>array('/site/index'),
	$model->Code,
);

$this->menu=array(
	array('label'=>'List Document', 'url'=>array('index')),
	array('label'=>'Create Document', 'url'=>array('create')),
	array('label'=>'Update Document', 'url'=>array('update', 'id'=>$model->Id)),
	array('label'=>'Delete Document', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->Id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Document', 'url'=>array('admin')),
);
$authorize = $model->ApprovalStatus == "Revise" and $model->DocumentStatus == yii::app()->user->guid ? true : false;
?>

<div class="box box-solid">
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        <h3 class="box-title">
							<?php echo "View Document $model->DocumentName"; ?>
						</h3>
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
<?php $form=$this->beginWidget('CActiveForm',
	array('enableAjaxValidation'=>false, 
		'htmlOptions'=>array('class'=>'form-horizontal', 'enctype' => 'multipart/form-data'),
		'id'=>'document-form')); ?>
			<div class="form-group">
				<?php echo $form->labelEx($model,'Code',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'Code', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'DocumentName',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'DocumentName', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'SubName',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'SubName', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'Priority',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'Priority', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'Description',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textArea($model,'Description', array('class' => 'form-control', 'placeholder'=>'Type Here', 'rows'=>6, 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'RequiredBy',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->hiddenField($model,'IdRequiredBy'); ?>
					<?php echo $form->textField($model,'RequiredBy', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'ApprovedBy',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->hiddenField($model,'IdApprovedBy'); ?>
					<?php echo $form->textField($model,'ApprovedBy', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'ExecutedBy',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->hiddenField($model,'IdExecutedBy'); ?>
					<?php echo $form->textField($model,'ExecutedBy', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'Budget',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'Budget', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'PlanningDate',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'PlanningDate', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'Realization',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'Realization', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'RealizationDate',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textField($model,'RealizationDate', array('class' => 'form-control', 'placeholder'=>'Auto Field', 'readonly'=>true)); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model,'Instruction',array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-5">
					<?php echo $form->textArea($model,'Instruction', array('class' => 'form-control', 'placeholder'=>'Type Here', 'rows'=>6, 'readonly'=>true)); ?>
				</div>
			</div>
	<?php $this->endWidget(); ?>
		<div class="box-footer">
			<button id="close" type="button" class="btn btn-warning">Back</button>
			<?php if($authorize) : echo'
			<form action="'.yii::app()->baseurl.'/document/update/'.$model->Id.'" method="post">
			<button name="update" type="submit" class="btn btn-primary pull-right">Update</button>';
			endif; ?>
		</div>
                    </div>
                  </div>
                </div>
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        Attachment
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="box-body">
			<div class="form-group">
				<div class="col-sm-12">
					<?php
						$att = Attachment::model()->findAllByAttributes(array('DocumentId'=>$model->Id));
						if($att != null)
							foreach($att as $img) {
								if(preg_match('/image/',$img->Type)) {
									$url = Yii::app()->request->baseUrl.'/protected/views/images/'.$img->Name;
									echo "<a href='$url' target='_blank'>".CHtml::image($url, "image",array("width"=>200))."</a>";
									if(strtolower($img->CreatedBy) == yii::app()->user->name){
										echo("<a href='/site/deletefile/$img->Id'><button class='btn btn-danger'>Delete</button><a>");
									}
								} else {
									$url = Yii::app()->request->baseUrl.'/protected/views/images/accepted_document.png';
									$file = Yii::app()->request->baseUrl.'/protected/views/images/'.$img->Name;
									echo "<a href='$file' target='_blank'>".CHtml::image($url, "image",array("width"=>200))."</a>";
									if(strtolower($img->CreatedBy) == yii::app()->user->name){
										echo("<a href='/site/deletefile/$img->Id'><button class='btn btn-danger'>Delete</button><a>");
									}
								}
								echo " ";
							}
					?>
				</div>
			</div>
			<?php if($model->DocumentStatus != "FINAL") : ?>
			<div class="panel box box-footer">
              <form action="<?=Yii::app()->request->baseUrl?>/Document/add" enctype="multipart/form-data" method="post">
				<label class='col-sm-3 control-label' for='up'>Add File</label>
				<div class="col-sm-5">
                  <input name="DocumentId" type="hidden" value="<?=$model->Id?>">
					<input class='form-control' id='up' multiple='multiple' name='Attachment[UploadedFile][]' type='file' />
				</div>
				<div class="col-sm-3">
					<button class="btn btn-primary pull-right" name='submit' type='submit'>Upload</button>
				</div>
              </form>
			</div>
			<?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        Comment
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
                    <div class="box-body">
            <!-- /.box-body -->
            <div class="box-footer box-comments">
			<?php foreach($comment as $value) : ?>
              <div class="box-comment">
                <!-- User image -->
                <img class="img-circle img-sm" src="<?=Yii::app()->request->baseUrl?>/themes/alte/dist/img/user.png" alt="User Image">

                <div class="comment-text">
                      <span class="username"><?=$value->CreatedBy?>
						<span class="text-muted pull-right"><?=$value->CreatedDate?></span>
                      </span><!-- /.username -->
					  <?=$value->Content?>
                </div>
                <!-- /.comment-text -->
              </div>
              <!-- /.box-comment -->
			<?php endforeach; ?>
            </div>
            <!-- /.box-footer -->
            <div class="box-footer">
              <form action="<?=Yii::app()->request->baseUrl?>/comment/create" method="post">
                <img class="img-responsive img-circle img-sm" src="<?=Yii::app()->request->baseUrl?>/themes/alte/dist/img/user.png" alt="Alt Text">
                <!-- .img-push is used to add margin to elements next to floating images -->
                <div class="img-push">
                  <input name="Comment[Content]" type="text" class="form-control input-sm" placeholder="Press enter to post comment">
                  <input name="Comment[DocumentId]" type="hidden" value="<?=$model->Id?>">
                  <input name="Comment[UserId]" type="hidden" value="<?=yii::app()->user->guid?>">
                </div>
              </form>
            </div>
            <!-- /.box-footer -->
			</div>
                  </div>
                </div>
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Activity
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFour" class="panel-collapse collapse">
                    <div class="box-body">
						<table class="table table-bordered table-striped" width="100%">
							<thead>
								<tr>
									<th>Date and Time</th>
									<th>Activity Name</th>
									<th>User</th>
								</tr>
							</thead>
							<tbody>
								<?php if($history != null) foreach($history as $row) { echo ("<tr>
									<td>$row->CreatedDate</td>
									<td>$row->Predicate</td>
									<td>$row->CreatedBy</td></tr>"); } ?>
							</tbody>
						</table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
</div>

	<?php echo $form->errorSummary($model); ?>

<script>
	$('#close').on('click',function() {
		history.back();
	} );
</script>
