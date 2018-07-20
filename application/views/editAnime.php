<?php

$animeid = '';
$title = '';
$sinopsis = '';
$status = 0;
$statuscheck = '';
$image = '';
$imgbackground = '';
$view = '';


if(!empty($animeInfo))
{
    foreach ($animeInfo as $af)
    {
        $animeid = $af->animeid;
        $title = $af->title;
        $sinopsis = $af->sinopsis;
        $status = $af->status;
        if($status == 1)
        {
            $statuscheck = 'checked';
        }
        else
        {
            $statuscheck = '';
        }
        $image = $af->image;
        $imgbackground = $af->imgbackground;
        $view = $af->view;
    }
}


?>

<style>
    .material-switch > input[type="checkbox"] {
    display: none;   
    }

    .material-switch > label {
        cursor: pointer;
        height: 0px;
        position: relative; 
        width: 40px;  
    }

    .material-switch > label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: -8px;
        position:absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
    }
    .material-switch > label::after {
        background: rgb(255, 255, 255);
        border-radius: 16px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        content: '';
        height: 24px;
        left: -4px;
        margin-top: -8px;
        position: absolute;
        top: -4px;
        transition: all 0.3s ease-in-out;
        width: 24px;
    }
    .material-switch > input[type="checkbox"]:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > input[type="checkbox"]:checked + label::after {
        background: inherit;
        left: 20px;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Anime Management
        <small>Add / Edit Anime</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Anime Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>editAnim" method="post" id="editAnim" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="title">Anime Title</label>
                                        <input type="text" class="form-control" id="title" placeholder="Anime Title" name="title" value="<?php echo $title; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $animeid; ?>" name="animeid" id="animeid" />    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="view">Views</label>
                                        <input type="view" class="form-control" id="view" placeholder="Enter View Count" name="view" value="<?php echo $view; ?>" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="sinopsis">Sinopsis</label>
                                        <input type="text" class="form-control" id="sinopsis" placeholder="Sinopsis" name="sinopsis" value="<?php echo $sinopsis; ?>" maxlength="500">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Image Link</label>
                                        <input type="text" class="form-control" id="image" placeholder="Enter Image Link" name="image" value="<?php echo $image; ?>" maxlength="200">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="imgbackground">Image Background</label>
                                        <input type="text" class="form-control" id="imgbackground" placeholder="Enter Image Background Link" name="imgbackground" value="<?php echo $imgbackground; ?>" maxlength="200">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="ongoing" style="width:70%;
                                                                    position:absolute;">Ongoing</label>
                                        <div class="material-switch pull-right">
                                            <input id="status" name="status" type="checkbox" <?php echo $statuscheck; ?>/>
                                            <label for="status" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Save" />
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

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>