<?php $this->load->view('partials/header'); ?>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<h4 class='text-center'>
				Feedback Form
				<br>
			</h4>
			<form method="post" action="<?=site_url('feedback/form_post')?>">
				<input type="hidden" name="student_id" value="<?=$student_id?>"/>
				<input type="hidden" name="added_by" value="<?=$added_by?>"/>
				<!-- Speaking -->
				<div class="row">
					<div class="col-md-3">
						<h5><span class="badge badge-pill badge-dark">Speaking</span></h5>
				  </div>
					<div class="col-md-9">
						<input type="radio" name="speaking" value='PATHETIC' />
						<img width="11%" src="<?=base_url('assets/images/pathatic.png')?>" />
						&nbsp;&nbsp;
						<input type="radio" name="speaking" value='SAD' />
						<img width="11%" src="<?=base_url('assets/images/sad.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="speaking" value='NORMAL' />
						<img width="11%" src="<?=base_url('assets/images/ok.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="speaking" value='HAPPY' checked="true" />
						<img width="11%" src="<?=base_url('assets/images/happy.png')?>" />

					</div>	
				</div>
				<hr>

	<!-- Listening -->
				<div class="row">
					<div class="col-md-3">
						<h5><span class="badge badge-pill badge-dark">Listening</span></h5>
				  </div>
					<div class="col-md-9">
						<input type="radio" name="listening" value='PATHETIC' />
						<img width="11%" src="<?=base_url('assets/images/pathatic.png')?>" />
						&nbsp;&nbsp;
						<input type="radio" name="listening" value='SAD' />
						<img width="11%" src="<?=base_url('assets/images/sad.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="listening" value='NORMAL' />
						<img width="11%" src="<?=base_url('assets/images/ok.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="listening" value='HAPPY' checked="true"/>
						<img width="11%" src="<?=base_url('assets/images/happy.png')?>" />

					</div>	
				</div>
				<hr>

				<!-- reading -->
				<div class="row">
					<div class="col-md-3">
						<h5><span class="badge badge-pill badge-dark">Reading</span></h5>
				  </div>	
					<div class="col-md-9">
						<input type="radio" name="reading" value='PATHETIC' />
						<img width="11%" src="<?=base_url('assets/images/pathatic.png')?>" />
						&nbsp;&nbsp;
						<input type="radio" name="reading" value='SAD' />
						<img width="11%" src="<?=base_url('assets/images/sad.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="reading" value='NORMAL' />
						<img width="11%" src="<?=base_url('assets/images/ok.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="reading" value='HAPPY' checked="true" />
						<img width="11%" src="<?=base_url('assets/images/happy.png')?>" />

					</div>	
				</div>
				<hr>
				
				<!-- writing -->
				<div class="row">
					<div class="col-md-3">
						<h5><span class="badge badge-pill badge-dark">Writing</span></h5>
				  </div>	
					<div class="col-md-9">
						<input type="radio" name="writing" value='PATHETIC' />
						<img width="11%" src="<?=base_url('assets/images/pathatic.png')?>" />
						&nbsp;&nbsp;
						<input type="radio" name="writing" value='SAD' />
						<img width="11%" src="<?=base_url('assets/images/sad.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="writing" value='NORMAL' />
						<img width="11%" src="<?=base_url('assets/images/ok.png')?>" />

						&nbsp;&nbsp;
						<input type="radio" name="writing" value='HAPPY' checked="true" />
						<img width="11%" src="<?=base_url('assets/images/happy.png')?>" />

					</div>	
				</div>
				<hr>
				<br>
				<div class="text-center">
						<input type="submit" name="submit" value="Submit" class="btn btn-dark text-center" />
				</div>
			</form>
			
				
		</div>
		<div class="col-md-3"></div>
	</div>
<?php $this->load->view('partials/footer'); ?>