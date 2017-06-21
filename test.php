<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<ul>
	<?php $data = [1,2,3,4,5,6,7,8,9]; ?>
		<script type="text/javascript">
			var data = [1,2,3,4,5,6,7,8,9];
			var x = 0; 
			<# for(x = 0; x < data.length; x++ ){ #>
				<li>ID: <# data[x]; #></li>
			<# } #>
		</script>
	</ul>
</body>
</html>