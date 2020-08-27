	<div class="container">
		<h1>Vending Machine Service!</h1>	
		<div class="d-flex justify-content-between">
			<p>Hi, dear being who manages the vending</p>
			<a href="." role="button" class="btn btn-warning">Back</a>
		</div>
		
		<div id="alert-box"></div>
		
		<form action="ajax.service.php" enctype="multipart/form-data" method="post">
			<fieldset>
				<legend>Items</legend>
				<div class="form-row">
					<div class="form-group col-sm-4">
						<label for="water">Water</label>
						<input type="number" class="form-control" id="water" name="water" min="0" step="1" placeholder="0" value="<?php echo $water; ?>">
					</div>
					
					<div class="form-group col-sm-4">
						<label for="juice">Juice</label>
						<input type="number" class="form-control" id="juice" name="juice" min="0" step="1" placeholder="0" value="<?php echo $juice; ?>">
					</div>
					
					<div class="form-group col-sm-4">
						<label for="soda">Soda</label>
						<input type="number" class="form-control" id="soda" name="soda" min="0" step="1" placeholder="0" value="<?php echo $soda; ?>">
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>Change</legend>
				<div class="form-row">
					<div class="form-group col-sm-4">
						<label for="coin005">0.05</label>
						<input type="number" class="form-control" id="coin005" name="coin005" min="0" step="1" placeholder="0">
					</div>
					
					<div class="form-group col-sm-4">
						<label for="coin010">0.10</label>
						<input type="number" class="form-control" id="coin010" name="coin010" min="0" step="1" placeholder="0">
					</div>
					
					<div class="form-group col-sm-4">
						<label for="coin025">0.25</label>
						<input type="number" class="form-control" id="coin025" name="coin025" min="0" step="1" placeholder="0">
					</div>
				</div>
			</fieldset>
			<button class="btn btn-primary">Set!</button>
		</form>
	</div>