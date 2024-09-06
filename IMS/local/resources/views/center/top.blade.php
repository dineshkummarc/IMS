<div class="navbar-collapse collapse">
						<ul class="nav navbar-left hidden-sm hidden-xs">
							<li class="sidebar-toggler-wrapper">
								<div>
									<button href="javascript:void(0)" class="btn sidebar-toggler visible-md visible-lg">
										<i class="fa fa-bars"></i>
									</button>
								</div>
							</li>
							<li>
								<a href="#" class="toggle-fullscreen"> <i class="fa fa-expand expand-off"></i><i class="fa fa-compress expand-on"></i></a>
							</li>
							<li>
								<form role="search" class="navbar-form main-search" action="{{Asset('center/student/search')}}">
									<div class="form-group">
										<input type="text" placeholder="Search Student With Name, Mobile, City" class="form-control" style="width:370px;" name="query" required>
										<button class="btn search-button" type="submit">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</form>
							</li>
							
						</ul>
													
							<?php
							$centerName = DB::table("users")->where("id",env("admin_id"))->first();
							?>
							<br><h2 class="mainTitle no-margin"  style="color:white;float:right;margin-right:10px">{{$centerName->center_name}}</h2>
						
						
					</div>
					