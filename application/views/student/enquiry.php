<?php $this->load->view('admin/partials/header'); ?>
<div class="row">
	<div class="col-md-12">
		<h3 class="text-center">Registration Form</h3>
	</div>
</div>
<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<form action="<?=site_url('student/enquiry_post')?>" method="post">
			<div class="form-group">
				<label>Name:</label>
				<input type="text" name="data[student_name]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Email:</label>
				<input type="email" name="data[email]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Date Of Birth:</label>
				<input type="text" name="data[date_of_birth]" class="form-control _datepicker" />
			</div>
			<div class="form-group">
				<label>Phone:</label>
				<input type="text" name="data[phone]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Fathers Name:</label>
				<input type="text" name="data[fathers_name]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Fathers Contact No:</label>
				<input type="text" name="data[fathers_contact_no]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Address:</label>
				<textarea class="form-control" rows="3"></textarea>
			</div>
			<div class="form-group">
				<label>City:</label>
				<input type="text" name="data[city]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>State:</label>
				<input type="text" name="data[state]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Zip:</label>
				<input type="text" name="data[state]" class="form-control"/>
			</div>
			<div class="form-group">
				<label>Date:</label>
				<input type="text" name="data[registration_date]" class="form-control _datepicker" value="<?=date('Y-m-d')?>" />
			</div>
			<input type="submit" name="submit" value="Submit" class="btn btn-success full-width" />
		</form>
	</div>
	<div class="col-md-4"></div>
</div>
<?php $this->load->view('admin/partials/footer'); ?>