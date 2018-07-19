
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/mui/css/mui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/mui/css/mui.min.css">

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Episode Management
        <small>Add / Edit Episode</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Episode Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addEpisode" action="<?php echo base_url() ?>episode/submit" method="post" role="form">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="anime">Anime</label>
                                        <div class="mui-select">
                                            <select id="anime" name="anime">
                                                <option value="" disabled selected>Select Anime</option>
                                                <?php
                                                if(!empty($animeRecords))
                                                {
                                                    foreach($animeRecords as $record)
                                                    {
                                                ?>
                                                        <option value="<?php echo $record->animeid ?>" ><?php echo $record->title ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="episodenumber">Episode Number</label>
                                        <input type="text" class="form-control required" onkeypress="return isNumber(event)" value="<?php echo set_value('episodenumber'); ?>" id="episodenumber" name="episodenumber" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Episode Title</label>
                                        <input type="text" class="form-control required" id="title" value="<?php echo set_value('title'); ?>" name="title" maxlength="128">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="thumbnail">Thumbnail</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('thumbnail'); ?>" id="thumbnail" name="thumbnail" maxlength="128">
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
<script type="text/javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>