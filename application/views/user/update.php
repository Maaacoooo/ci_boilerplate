
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$title?> &middot; <?=$site_title?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php $this->load->view('inc/css')?>
   
</head>
<body class="hold-transition skin-black sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <?php $this->load->view('inc/header')?>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <?php $this->load->view('inc/left_nav')?>    
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?=$title?>        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url()?>">Dashboard</a></li>
        <li><a href="<?=base_url('users')?>">Users</a></li>
        <li class="active"><?=$title?></li>
      </ol>
    </section>

   <section class="content">

      <div class="row">
        <div class="col-xs-12">
          <?php
            //ALERT / NOTIFICATION
            //ERROR ACTION        
            $flash_error = $this->session->flashdata('error');
            $flash_success = $this->session->flashdata('success');
            $flash_valid =  validation_errors();                 
        ?>
         <?=$this->sessnotif->showNotif()?>

        </div><!-- /.col-xs-12 -->
      </div><!-- /.row -->

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">             
              <?php if (filexist($info['img']) && $info['img']): ?>
                <img class="profile-user-img img-responsive img-circle" src="<?=base_url($info['img'])?>" alt="User profile picture">
              <?php else: ?>
                <img class="profile-user-img img-responsive img-circle" src="<?=base_url('assets/img/no_image.gif')?>" alt="User profile picture">                
              <?php endif ?>

              <h3 class="profile-username text-center"><?=$info['name']?></h3>
              <p class="text-muted text-center"><?=$info['usertype']?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Username</b> <a class="pull-right"><?=$info['username']?></a>
                </li>
                <li class="list-group-item">
                  <b>Email</b> <a href="mailto:<?=$info['email']?>" class="pull-right"><?=$info['email']?></a>
                </li>
                <li class="list-group-item">
                  <b>Contact</b> <a class="pull-right"><?=$info['contact']?></a>
                </li>                
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li <?php if(!($flash_error || $flash_success || $flash_valid))echo'class="active"'?>><a href="#activity" data-toggle="tab">Activity Logs</a></li>
              <li <?php if($flash_error || $flash_success || $flash_valid)echo'class="active"'?>><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane <?php if(!($flash_error || $flash_success || $flash_valid))echo'active'?>" id="activity">
                <h4 class="title">Last 50 Activity <small><a href="<?=current_url().'/download_logs'?>" class="pull-right"><i class="fa fa-download"></i> Download Logs</a></small></h4>
                <?php if ($logs): ?>
                <table class="table table-condensed">
                  <thead>
                    <tr>
                      <th>Tag</th>
                      <th>Tag ID</th>
                      <th>Action</th>
                      <th>Date Time</th>
                      <th>IP Address</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($logs as $lg): ?>
                    <tr>
                      <td><?=$lg['tag']?></td>
                      <td><?=$lg['tag_id']?></td>
                      <td><?=$lg['action']?>
                        <?php if ($lg['tag'] == 'user'): ?>
                          <a href="<?=base_url('users/update/'.$lg['tag_id'])?>" title="Check out..."><i class="fa fa-external-link"></i></a>
                        <?php endif ?>
                      </td>
                      <td><?=$lg['date_time']?></td>
                      <td><?=$lg['ip_address']?></td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table><!-- /.table table-condensed -->
                <?php else: ?>
                  <div class="alert alert-warning">               
                    <h4><i class="icon fa fa-warning"></i> No records found!</h4>         
                    No Activity Logs record found in the system
                  </div>
                <?php endif ?>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane <?php if($flash_error || $flash_success || $flash_valid)echo'active'?>" id="settings">
              <?=form_open_multipart('users/update/'.$info['username'], array('class' => 'form-horizontal'))?>                
                  <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Full name</label>
                    <div class="col-sm-10">
                      <input type="text" name="name" class="form-control" id="name" placeholder="Full name..." value="<?=$info['name']?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-2 col-md-2 control-label">Email Address</label>
                    <div class="col-sm-10 col-md-4">
                      <input type="email" name="email" class="form-control" id="email" placeholder="Email Address..." value="<?=$info['email']?>" required>
                    </div>

                    <label for="contact" class="col-sm-2 col-md-2 control-label">Contact Number</label>
                    <div class="col-sm-10 col-md-4">
                      <input type="text" name="contact" class="form-control" id="contact" placeholder="Contact Number..." value="<?=$info['contact']?>">
                    </div>                    
                  </div>
                  <div class="form-group">
                    <label for="name" class="col-sm-2 col-md-2 control-label">Usertype</label>
                    <div class="col-sm-10 col-md-4">        
                        <select name="usertype" class="form-control" required="">
                          <option disabled="">Select Usertype...</option>
                           <?php 
                              if($usertypes):
                              foreach($usertypes as $usr):
                            ?>
                            <option value="<?=$usr['title']?>" <?php if($info['usertype']==$usr['title'])echo'selected';?>><?=$usr['title']?></option>
                            <?php
                              endforeach;
                              endif;
                            ?>
                        </select>       
                     </div>
                  </div>
                  <div class="form-group">    
                    <label for="img" class="col-sm-2 control-label">Profile Image</label>    
                    <div class="col-sm-3">
                      <input type="file" name="img" id="img">   
                    </div>
 
                    <div class="col-sm-5">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="remove_img"> Remove Image
                        </label>
                      </div>
                    </div>    
                  </div> 
                  <input type="hidden" name="id" value="<?=$this->encryption->encrypt($info['username'])?>" />                  
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-warning pull-right">Update</button>

                      <?php if (!($user['username'] == $info['username'])): ?>
                      <div class="btn-group">                        
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalReset">Reset Password</button>                        
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalDelete">Delete</button> 
                      </div>    
                      <?php endif ?>                                          
                    </div>
                  </div>
              <?=form_close()?>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
  </div>
  <!-- /.content-wrapper -->

  <!-- Modals -->
      <?php if(!($user['username'] == $info['username'])): ?>      
        <div class="modal modal-danger fade" id="modalDelete" style="display: none;">
          <div class="modal-dialog">
          <?=form_open('users/delete')?>
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Delete <?=$info['name']?></h4>
              </div>
              <div class="modal-body">
                <p>Are you sure to delete the record of <strong ><?=$info['name']?></strong>?</p>
                <p>You <strong>CANNOT UNDO</strong> this action.</p>
                <input type="hidden" name="id" value="<?=$this->encryption->encrypt($info['username'])?>" />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline">Delete User</button>
              </div>
            </div>
            <!-- /.modal-content -->
          <?=form_close()?>
          </div>
          <!-- /.modal-dialog -->
        </div>


        <div class="modal modal-info fade" id="modalReset" style="display: none;">
          <div class="modal-dialog">
          <?=form_open('users/resetpassword')?>
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Reset Password: <?=$info['name']?></h4>
              </div>
              <div class="modal-body">
                <p>Are you sure to reset to DEFAULT PASSWORD?</p>
                <p>You <strong>CANNOT UNDO</strong> this action.</p>
                <p>The Default Password is <strong class="text-success"><?=DEFAULT_PASS?></strong></p>
                <input type="hidden" name="id" value="<?=$this->encryption->encrypt($info['username'])?>" />
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-outline">Reset</button>
              </div>
            </div>
            <!-- /.modal-content -->
          <?=form_close()?>
          </div>
          <!-- /.modal-dialog -->
        </div>

      <?php endif; ?>

  <footer class="main-footer">    
    <?php $this->load->view('inc/footer')?>    
  </footer>

</div>
<!-- ./wrapper -->

    <?php $this->load->view('inc/js')?>    
  
</body>
</html>
