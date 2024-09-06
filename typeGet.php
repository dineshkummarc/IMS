<?php
$id = $_GET['id'];

if($id == 1){
?>

<input type="text" class="form-control" placeholder="User Name" name="user_name" id="uname" required>

<?php } else { ?>

<input type="text" class="form-control" placeholder="Institute Name" name="center_name" id="cname" required>

<input type="text" class="form-control" placeholder="Person Name" name="person_name" id="pname" required >

<input type="number" class="form-control" placeholder="Mobile Number" name="mobile" id="mobile" required >               
				  
<input type="email" class="form-control" placeholder="Email  (You will get login detail on this email)" name="email" id="email" required >

<select name="ref" class="form-control" required>
<option value="">How You Reach Here</option>
<option value="From Google">From Google</option>
<option value="From Facebook">From Facebook</option>
<option value="By Friend">By Friend Refrence</option>
<option value="By Website Ad">See Our Ad On Any Website</option>
<option value="Other">Other</option>
</select>

<textarea class="form-control" placeholder="Full Address" name="address" id="message" required></textarea>

<?php } ?>