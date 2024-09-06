<?php 
include_once("conn.php");
if($_GET['id']){
?>

						<div class="form-group" >
						<label>Batch</label>
						<div class="form-group">
						<select name="batch" class="form-control"  required>
						<option value="0">Select Batch</option>
						<?php
						$b = mysqli_query($conn,"select * from course_batch where course_id =".$_GET['id']);
						while($row = mysqli_fetch_array($b))
						{
						?>
						<option value="<?php echo $row['batch_name']; ?>"><?php echo $row['batch_name']; ?></option>
						<?php } ?>
						</select>
						</div>
						</div>
						<Br>
						<div class="form-group">						
						<div class="form-group">
						<button type="submit" class="btn btn-wide btn-success">Get Student</button>
						</div>
						</div>

<?php } ?>