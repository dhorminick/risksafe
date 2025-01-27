<div class="alert">
<?php if (count($message) > 0) : ?>
						<div class="bg-[--primary] text-white p-[15px] rounded-md mb-[10px] !w-full">
						<?php foreach ($message as $error) : ?>
							<div class="w-full">
								<div class="flex items-center justify-between w-full">
									<?php echo $error ?>
									<button class="close-alert py-[5px] px-[15px]" data-dismiss="alert"><span>×</span></button>
								</div>
							</div>
						<?php endforeach ?>
						</div>

				<?php endif ?>
				<script
                  src="https://code.jquery.com/jquery-3.7.1.js"
                  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
                  crossorigin="anonymous"
                ></script>
				<script>
				   
                  $(".close-alert").click(function (e) {
                    $(".alert").html('');
                  });
                  
				</script>
</div>