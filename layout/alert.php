<?php if (count($message) > 0) : ?>
					<!-- <div class="row"> -->
						<div class="alert-div" style="display: flex;justify-content:center;">
						<?php foreach ($message as $error) : ?>
							<div class="alert alert-primary alert-dismissible show col-lg-10 col-12">
								<div class="alert-body">
									<button class="close close-alert" data-dismiss="alert"><span>×</span></button>
									<?php echo $error ?>
								</div>
							</div>
						<?php endforeach ?>
						</div>
					<!-- </div> -->
				<?php endif ?>