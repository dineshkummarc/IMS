<?php 
include_once("conn.php");
if($_GET['id']){
?>

						<div class="form-group" >
						<label>Batch</label>
						<div class="form-group">
						<select name="batch" class="form-control" required>
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
						<?php
						$f = mysqli_query($conn,"select * from course where id =".$_GET['id']);
						$fi = mysqli_fetch_array($f);
						?>
						<div class="form-group">
						<label>Course Fee <span class="symbol required"></span></label>
						<div class="form-group">
						<input type="number"  name="course_fee" id="course_fee" class="form-control"  value="<?php echo $fi['fee']; ?>" required>
						</div>
						</div>


<?php } else { ?>

						<div class="form-group" >
						<label>Batch</label>
						<div class="form-group">
						<select name="batch" class="form-control" required>
						<option value="">Select Batch</option>
						</select>
						</div>
						</div>
						
						<div class="form-group">
						<label>Course Fee <span class="symbol required"></span></label>
						<div class="form-group">
						<input type="number"  name="course_fee" id="course_fee" class="form-control"  value="" required>
						</div>
						</div>

<?php } ?>