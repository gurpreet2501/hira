<?php $this->load->view('admin/partials/header'); ?>
<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<h3 class="text-center">Select Batch</h3>
			<form action="<?=site_url('student/filter_students_attendence')?>" method="post">
				<div class="form-group">
					<label>Select Batch</label>
					<select class="form-control" name="course_id">
						<?php foreach ($courses as $key => $course):?>
								<option value="<?=$course->id?>"><?=$course->course_name?></option>
						<?php endforeach ?>
					</select>
				</div>
				<input type="submit" name="submit" class="btn btn-primary" value="Show Attendence" />
			</form>
	  </div>
  </div>
<?php $this->load->view('admin/partials/footer'); ?>