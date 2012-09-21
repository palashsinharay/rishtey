<html>
<head>
<title>World</title>
<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.css" type="text/css" media="screen, projection">
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="<?php echo base_url();?>js/bootstrap.js"></script>
</head>
<body>
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
				<td><?php echo $row['id'] ?></td>
				<td><?php echo $row['first_name'] ?></td>
				<td><?php echo $row['last_name'] ?></td>
				<td><?php echo $row['username'] ?></td>
				<td><?php echo $row['gender'] ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php echo "records added"; ?>
</div>
</body>
</html>