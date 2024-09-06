<?php 
include_once("conn.php");
if($_GET['course']){
?>

						<div class="form-group" >
						<label>Select Student <span class="symbol required"></span></label>
						<div class="form-group">
						<select name="student_id" class="form-control">
						<option value="">All Student</option>
						<?php
						$batch = $_GET['batch'];
						
						$b = mysqli_query($conn,"select * from student_course where course_id =".$_GET['course']." and batch = '$batch' and status = 0");
						while($row = mysqli_fetch_array($b))
						{
							$stu = mysqli_query($conn,"select * from student where id =".$row['student_id']);
							$stud = mysqli_fetch_array($stu);
						?>
						<option value="<?php echo $stud['id']; ?>"><?php echo $stud['first_name']; ?> <?php echo $stud['last_name']; ?></option>
						<?php } ?>
						</select>
						</div>
						</div>
						
						<?php } ?>							
	
