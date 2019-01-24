<div class="spacer-100"></div>
</div> <!-- container ends -->
<?php $cacheVer = rand(1,10000); ?>     

 <script src="<?=base_url('env.js?v='.$cacheVer);?>"></script> 
 <script src="<?=base_url('assets/js/env-support.js?v='.$cacheVer);?>"></script> 

 <script src="<?=base_url('assets/js/jquery.js?v='.$cacheVer);?>"></script>
 
 <script src="<?=base_url('assets/js/mousetrap.min.js?v='.$cacheVer);?>"></script>
 <script src="<?=base_url('assets/js/ctrl-q-click.js?v='.$cacheVer);?>"></script>

 <script src="<?=base_url('assets/js/bootstrap.min.js?v='.$cacheVer);?>"></script>
 <script type="text/javascript" src="<?=base_url('assets/js/chosen.jquery.js?v='.$cacheVer)?>"></script>
 <script type="text/javascript" src="<?=base_url('assets/js/select2.js?v='.$cacheVer)?>"></script>

 <script type="text/javascript" src="<?=base_url('assets/js/jquery-validate.js?v='.$cacheVer)?>"></script>

 <script type="text/javascript" src="<?=base_url('assets/js/vue.min.js?v='.$cacheVer)?>"></script>
 <script type="text/javascript" src="<?=base_url('assets/js/vue-select.js?v='.$cacheVer)?>"></script>
 <script type="text/javascript" src="<?=base_url('assets/js/app.js?v='.$cacheVer)?>"></script>
 <script type="text/javascript" src="<?=base_url('assets/js/vex.combined.min.js?v='.$cacheVer)?>"></script>
 <script type="text/javascript">
  function getBaseUrl(){
    return <?=json_encode(site_url())?>;
  }
 </script>
 <?php if($this->config->item('auto_logout')): ?>
   <script type="text/javascript" src="<?=base_url('assets/js/auto-logout.js?v='.$cacheVer)?>"></script>
<?php endif; ?>

 <?php if(isset($js_files)): ?>
  <?php  foreach($js_files as $js_file): 
      if(strstr($js_file,'jquery-1.11.1.min.js')){
        continue;
      }
  ?>
    <script type="text/javascript" src="<?=at($js_file).'?v='.$cacheVer?>"></script>
  <?php endforeach; ?>
<?php endif;?>
 <script type="text/javascript" src="<?=base_url('assets/js/party-name-pop-up.js?v='.$cacheVer)?>"></script>

<script type="text/javascript">
jQuery(function(){
  
  $('form.validate').each(function(){
    $(this).validate();
  })
  
  // $('.account_selector').select2('open');

  $(".chosen-select").chosen();

  $("._datepicker" ).datepicker({ 
      dateFormat: 'yy-mm-dd' 
   });
})
</script>
	<!-- <script type="text/javascript" src="<?=base_url()?>/js/jquery.validate.min.js""></script> -->
	

</body>
</html>
				
