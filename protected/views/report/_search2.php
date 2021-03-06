<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'htmlOptions'=>array('class'=>'form-horizontal')
)); ?>
<!-- /.box-header -->
<div class="box box-info">
	<div class="box-body">
		<div class="form-group">
			<?php echo $form->labelEx($model,'Since',array('class'=>'col-sm-1 control-label')); ?>
			<div class="col-sm-3">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'attribute'=>'Since',
					'model'=>$model,
					'options'=>array(
						'changeMonth'=>true,
						'changeYear'=>true,
						'dateFormat'=>'yy-mm-dd',
					),
					'htmlOptions'=>array(
						'class'=>'form-control',
						'placeholder'=>'Search Field'
					),
				)); ?>
			</div>
			<?php echo $form->labelEx($model,'Until',array('class'=>'col-sm-1 control-label')); ?>
			<div class="col-sm-3">
				<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'attribute'=>'Until',
					'model'=>$model,
					'options'=>array(
						'changeMonth'=>true,
						'changeYear'=>true,
						'dateFormat'=>'yy-mm-dd',
					),
					'htmlOptions'=>array(
						'class'=>'form-control',
						'placeholder'=>'Search Field'
					),
				)); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model,'Code',array('class'=>'col-sm-1 control-label')); ?>
			<div class="col-sm-3">
				<?php echo $form->textField($model,'Code', array('class' => 'form-control', 'placeholder'=>'Search Field')); ?>
			</div>
			<?php echo $form->labelEx($model,'SubName',array('class'=>'col-sm-1 control-label')); ?>
			<div class="col-sm-3">
				<?php echo $form->textField($model,'SubName', array('class' => 'form-control', 'placeholder'=>'Search Field')); ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-1 control-label required" for="Document_Code">Branch </label>
			<div class="col-sm-3">
				<?php echo $form->dropDownList($model,'Code', CHtml::listData(Document::model()->findAll(array('condition'=>'RowStatus != 2', 'order'=>'Code')),'lookup','lookup'), array('class' => 'form-control', 'placeholder'=>'Search Field')); ?>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<button id="submit" type="submit" class="btn btn-primary pull-right">Search</button>
	</div>
</div>
<?php $this->endWidget(); ?>
<?php echo $form->errorSummary($model); ?>