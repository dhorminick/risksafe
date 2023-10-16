<?php

include_once("../controller/auth.php");
include_once("../config.php");
include_once('../model/readnotification.php');


require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

$as = new readnotification();



?>

<!DOCTYPE html>
<html lang="en">

<head>
	<?php include_once("header.php"); ?>
</head>
<style>
	.background {
		height: auto;
		background: rgb(0, 46, 76);
		background: linear-gradient(90deg, rgba(0, 46, 76, 1) 0%, rgba(0, 153, 255, 1) 35%, rgba(0, 107, 179, 1) 100%);
		display: flex;
		margin-bottom: 1rem;
		justify-content: flex-end;
		align-items: center;
		padding: 2rem 2rem;
		border-bottom-left-radius: 2rem;
		border-top-right-radius: 2rem;

	}

	.heading {
		display: flex;
		align-items: center;
	}
</style>

<body>
	<!-- header -->
	<div id="top-nav" class="navbar navbar-inverse navbar-static-top" style="background-color:#09F;border:0;">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
				<a class="navbar-brand" href="main.php" style="font-weight:900;color:#fff;">
					<?php echo APP_TITLE; ?>
				</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown"> <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#" style="font-weight:900;color:#fff;"><i class="glyphicon glyphicon-user"></i> Account <span class="caret"></span></a>
						<ul id="g-account-menu" class="dropdown-menu" role="menu">
							<?php include_once("menu_top.php"); ?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<!-- /container -->
	</div>
	<!-- /Header -->

	<!-- Main -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-3 col-sm-12">
				<!-- Left column -->
				<?php include_once("menu.php"); ?>
				<!-- /col-3 -->
			</div>
			<div class="col-lg-9 col-md-12">
				<h1 class="page-header">Notifications</h1>

				<div class="">
					<div class="panel panel-default">
						<div class="panel-body">
							<div class="clearfix mb20 background">
								<div class="heading">
									<button type="button" style="border:none; outline:none;" class="btn btn-md btn-danger btn-info pull-right" id="btn_add">Clear All</button>
								</div>


							</div>
							<!-- <div class="clearfix mb20 background">
								<div class="heading">
								<button type="button" style="border:none; outline:none;" class="btn btn-md btn-info pull-right" id="btn_add">+ New Risk
									Assessment</button>

								</div>
								
							</div> -->

							<table class="table table-striped table-bordered table-hover highlight" id="table">
								<thead>
									<tr>
										<th>#</th>
										<!-- <th>Username</th> -->
										<th>Message</th>

										<th>Date</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td colspan="4">Loading...</td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!--/col-span-9-->
	</div>
	</div>

	<!-- /Main -->

	<?php include_once("footer.php"); ?>
	<script>
		$(document).ready(function() {
			var table = $('#table').dataTable({
				"processing": true,
				"serverSide": true,
				"stateSave": true,
				"bFilter": false,
				"ordering": false,
				"columns": [{
						
						"render": function(data, type, row, meta) {
							return meta.row + 1; // Display consistent serial numbers
						},
						"width": "20"
					},
					{},
					{
						"width": "25"
					},
					{
						"width": "55"
					}
				],
				"ajax": "../controller/readnotification.php?action=readnotification",
				"drawCallback": function() {
					// Refresh the serial numbers when the table is redrawn
					var api = this.api();
					api.column(0, {
						page: 'current'
					}).nodes().each(function(cell, i) {
						var index = api.page.info().start + i + 1;
						cell.innerHTML = index;
					});
				}
			
			

			});
			
			// Initialize DataTables with state loading configuration



			// update status 
			function updateReadStatus(notificationId, messageCell) {
				messageCell.addClass('highlight'); // Temporarily add the 'highlight' class to the message cell
				// console.log(notificationId)
				$.ajax({
					type: 'POST',
					url: '../controller/readnotification.php?action=statusupdate',
					data: {
						id: notificationId
					},
					success: function() {
						// console.log('Notification status updated successfully.');
						messageCell.removeClass('highlight'); // Remove the 'highlight' class after updating status

						var table = $('#table').DataTable();
						var currentPage = table.page();

						// Update the row numbers to maintain continuity
						table.rows({
							page: 'current'
						}).every(function(rowIdx) {
							var updatedIdx = rowIdx + currentPage * table.page.len() + 1;
							this.cell(rowIdx, 0).data(updatedIdx);
							
						});

						// Redraw the table to reflect the updated row numbers
						table.draw('page');
					},
					error: function() {
						// console.error('Failed to mark notification as read.');
						// messageCell.removeClass('highlight'); // Remove the 'highlight' class on error as well
					}
				});
				// 5000 milliseconds = 5 seconds
			}

			// Handle click event on table rows
			$('#table tbody').on('click', 'td:nth-child(2)', function() {
				var row = $(this).closest('tr');
				var dataArray = table.DataTable().row(row).data();
				var notificationId = dataArray[0];

				var messageCell = row.find('td:nth-child(2)'); // Assuming message cell is the 2nd column, change it if needed

				// Call the function to update read_status after a delay
				updateReadStatus(notificationId, messageCell);

				// Update the UI to show the notification as read immediately (without waiting for 5 seconds)
				messageCell.css('font-weight', 'normal');
			});

		});
	</script>
	<script>
		function del(id) {
			BootstrapDialog.show({
				title: "<i class='glyphicon glyphicon-trash'></i> Warning",
				type: BootstrapDialog.TYPE_DANGER,
				message: 'Are you sure you want to delete this notification?',
				buttons: [{
					label: 'Cancel',
					action: function(dialogItself) {
						dialogItself.close();
					},
				}, {
					label: 'Delete',
					cssClass: 'btn-danger',
					action: function(dialogItself) {
						res = $.ajax({
							type: "GET",
							url: "../controller/readnotification.php?action=deletenotification&id=" + id,
							async: false,
							success: function() {
								// Reload the DataTable after deleting the notification
								$('#table').DataTable().ajax.reload();
							},
							error: function() {
								console.error('Failed to delete notification.');
							}
						});
						dialogItself.close();
					}
				}]
			}); //end dialog
		}
	</script>
	<script>
		$("#btn_add").click(function() {
			delAll();
		});

		function delAll() {
			BootstrapDialog.show({
				title: "<i class='glyphicon glyphicon-trash'></i> Warning",
				type: BootstrapDialog.TYPE_DANGER,
				message: 'Are you sure you want to delete All these notification?',
				buttons: [{
					label: 'Cancel',
					action: function(dialogItself) {
						dialogItself.close();
					},
				}, {
					label: 'Delete',
					cssClass: 'btn-danger',
					action: function(dialogItself) {
						res = $.ajax({
							type: "GET",
							url: "../controller/readnotification.php?action=deleteallnotification",
							async: false,
							success: function() {
								// Reload the DataTable after deleting the notification
								$('#table').DataTable().ajax.reload();
							},
							error: function() {
								console.error('Failed to delete notification.');
							}
						});
						dialogItself.close();
					}
				}]
			}); //end dialog
		}
	</script>
</body>

</html>