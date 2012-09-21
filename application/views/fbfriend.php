<html>
<head>
<title>ReshteyConnect</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.css" type="text/css" media="screen, projection">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="<?php echo base_url();?>js/bootstrap.js"></script>
</head>
<body>
	<div class="navbar navbar-inverse">
    <div class="navbar-inner">
      <div style="width: auto;" class="container">
       <!--
        <a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar">
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
                 <span class="icon-bar"></span>
               </a>
       -->
       
        <a href="#" class="brand">RishteyConnect</a>
        <div class="nav-collapse">
          <ul class="nav">
            <li class="active"><a href="<?php echo base_url();?>">Home</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
              <a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <?php if($user):?>
                <li><a href="<?php echo $logoutUrl;?>">Logout</a></li>
                <?php else:?>
                <li><a href="<?php echo $loginUrl;?>">Login with Facebook</a></li>
                <?php endif;?>
              </ul>
            </li>
          </ul>
          <form action="" class="navbar-search pull-left">
            <input type="text" placeholder="Search" class="search-query span2">
          </form>
          
        </div><!-- /.nav-collapse -->
      </div>
    </div><!-- /navbar-inner -->
  </div>
<div class="container">
		<table class="table table-bordered table-striped">
			<tr>
				<td>Serial No.</td>
				<th>facebook id</th>
				<th>first Name</th>
				<th>Last Name</th>
				<th>Username</th>
				<th>Gender</th>
			</tr>
			<?php $count=1; ?>
			<?php foreach($records as $row): ?>
			<tr>
				<td><?php echo $count++ ?></td>
				<td><?php echo $row['fb_id'] ?></td>
				<td><?php echo $row['fname'] ?></td>
				<td><?php echo $row['lname'] ?></td>
				<td><?php echo $row['username'] ?></td>
				<td><?php echo $row['gender'] ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php echo "records added"; ?>
</div>
</body>
</html>