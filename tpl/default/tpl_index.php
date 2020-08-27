	<div class="container">
		<h1>Vending Machine</h1>
		<div class="d-flex justify-content-between">
			<p>Hi, dear customers</p>
			<a href="service.php" role="button" class="btn btn-info">Service</a>
		</div>
		
		<div class="d-flex justify-content-center">
			<div class="input-group my-3 col-12 col-md-6">
				<select class="custom-select" id="insertCoin">
					<option selected>Choose...</option>
					<option value="005">0,05</option>
					<option value="010">0,10</option>
					<option value="025">0,25</option>
					<option value="100">1,00</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary" type="button">INSERT COIN</button>
				</div>
			</div>	
		</div>
		<div class="my-3 text-center" id="amount">0,00</div>
		
		<div class="row">
			<div class="card col-sm-4">
				<div class="card-body text-center">
					<h5 class="card-title">WATER</h5>
					<p class="card-text">
						<b><?php echo $waterPrice; ?></b> €<br>
						<b><?php echo $waterStock; ?></b> units available
					</p>
					<label>
						<input class="form-check-input" type="radio" name="options" id="option1" autocomplete="off">
						SELECT
					</label>
				</div>
			</div>
			<div class="card col-sm-4">
				<div class="card-body text-center">
					<h5 class="card-title">JUICE</h5>
					<p class="card-text">
						<b><?php echo $juicePrice; ?></b> €<br>
						<b><?php echo $juiceStock; ?></b> units available
					</p>
					<label>
						<input class="form-check-input" type="radio" name="options" id="option2" autocomplete="off">
						SELECT
					</label>
				</div>
			</div>
			<div class="card col-sm-4">
				<div class="card-body text-center">
					<h5 class="card-title">SODA</h5>
					<p class="card-text">
						<b><?php echo $sodaPrice; ?></b> €<br>
						<b><?php echo $sodaStock; ?></b> units available
					</p>
					<label>
						<input class="form-check-input" type="radio" name="options" id="option3" autocomplete="off">
						SELECT
					</label>
				</div>
			</div>
		</div>
	</div>