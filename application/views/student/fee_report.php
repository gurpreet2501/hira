<?php $this->load->view('admin/partials/header'); ?>
<div class="row">
	<div class="col-md-12">
		  <div class='filters'>
		    <form class="form-inline" method="post" action="<?=site_url('student/post_fee_report')?>">
		        <div class="form-group">
		        	<label>Start Date</label>
		          <input type="text" name="start_date" class="form-control _datepicker" />
		        </div>
		        <div class="form-group">
		        	<label>End Date</label>
		          <input type="text" name="end_date" class="form-control _datepicker" />
		        </div>
		        
		        <input type="submit" name="filter" class="btn btn-success" value="Generate Report">

		      </form>
		  </div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		
	</div>
</div>
<?php $this->load->view('admin/partials/footer'); ?>