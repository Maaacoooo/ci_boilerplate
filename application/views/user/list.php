
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
        <li class="active"><?=$title?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
      <div class="row">
        <div class="col-xs-12">
          <?=$this->sessnotif->showNotif()?>
        </div><!-- /.col-xs-12 -->
      </div><!-- /.row -->

      <!-- Default box -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">User List  <span class="badge"><?=$total_result?></span></h3>
          <div class="box-tools pull-right">            
            <?=form_open('users', array('method' => 'get', 'class' => 'input-group input-group-sm', 'style' => 'width: 150px;'))?>
              <input type="text" name="search" class="form-control pull-right" placeholder="Search...">
              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                <button type="button" class="btn btn-default btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i>
                </button>  
              </div> 
            <?=form_close()?>            
          </div>
        </div>
        <div class="box-body table-responsive no-padding">
          <table class="table table-bordered">            
            <?php if ($results): ?>
            <thead>
              <tr>
                <th width="8%"></th>
                <th>Name</th>
                <th>Username</th>
                <th>Usertype</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($results as $res): ?>
                <tr>
                  <td>
                    <a href="<?=base_url('users/update/'.$res['username'])?>">
                      <img class="img-responsive img-circle" src="<?=checkImg($res['img'])?>" alt="User profile picture">                
                    </a>
                  </td>
                  <td><a href="<?=base_url('users/update/'.$res['username'])?>"><?=$res['name']?></a></td>
                  <td><a href="<?=base_url('users/update/'.$res['username'])?>"><?=$res['username']?></a></td>
                  <td><a href="<?=base_url('users/update/'.$res['username'])?>"><?=$res['usertype']?></a></td>
                </tr>
              <?php endforeach; ?>
            </tbody>              
            <?php endif; ?>              
          </table><!-- /.table table-bordered -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <div class="pull-right">
            <?php foreach ($links as $link) { echo $link; } ?>
          </div><!-- /.pull-right -->
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->


      <div class="box <?php if(!validation_errors())echo "collapsed-box"; else echo "box-danger";?>">
        <div class="box-header with-border">
          <h3 class="box-title">Register User</h3>
          <div class="box-tools pull-right">
              <button type="button" class="btn btn-default btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-plus"></i></button>            
          </div><!-- /.box-tools pull-right -->
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <?=form_open_multipart('users', array('class' => 'form-horizontal'))?>
          <div class="box-body">
              <div class="callout callout-info">
                <h4><i class="fa fa-info-circle"></i> Information</h4>
                <p>The default password of every new user is <strong class="strong text-success"><?=DEFAULT_PASS?></strong>(case-sensitive).</p>
                <p>Please advise your New User to change his password after logging in.</p>
              </div>

              <div class="form-group">
              <label for="username" class="col-sm-2 control-label">Username</label>

              <div class="col-sm-10">
                <input type="text" name="username" class="form-control" id="username" placeholder="Username..." value="<?=set_value('username')?>" >
              </div>
            </div>
            <div class="form-group">
              <label for="name" class="col-sm-2 control-label">Full name</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" id="name" placeholder="Full name..." value="<?=set_value('name')?>">
              </div>
            </div>
            <div class="form-group">
              <label for="email" class="col-sm-2 col-md-2 control-label">Email Address</label>
              <div class="col-sm-10 col-md-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="Email Address..." value="<?=set_value('email')?>" required>
              </div>

              <label for="contact" class="col-sm-2 col-md-2 control-label">Contact Number</label>
              <div class="col-sm-10 col-md-2">
                <input type="text" name="contact" class="form-control" id="contact" placeholder="Contact Number..." value="<?=set_value('contact')?>">
              </div>

              <label for="img" class="col-sm-2 col-md-1 control-label">Profile Image</label>
              <div class="col-sm-10 col-md-2">        
                  <input type="file" name="img" id="img">       
              </div>

            </div>
            <div class="form-group">            
              <label for="name" class="col-sm-2 col-md-2 control-label">Usertype</label>
              <div class="col-sm-10 col-md-2">        
                  <select name="usertype" class="form-control" required="">
                    <option selected="">Select Usertype...</option>
                     <?php 
                        if($usertypes):
                        foreach($usertypes as $usr):
                      ?>
                      <option value="<?=$usr['title']?>"><?=$usr['title']?></option>
                      <?php
                        endforeach;
                        endif;
                      ?>
                  </select>       
               </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Register New User</button>
          </div>
          <!-- /.box-footer -->
        <?=form_close()?>
      </div>



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">    
    <?php $this->load->view('inc/footer')?>    
  </footer>

</div>
<!-- ./wrapper -->

    <?php $this->load->view('inc/js')?>    
  
</body>
</html>
