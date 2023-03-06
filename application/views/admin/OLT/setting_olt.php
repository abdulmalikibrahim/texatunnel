<!-- Begin Page Content -->
<div class="container-fluid">
    	<!-- Page Heading -->
    	<h1 class="h3 mb-4 text-gray-800"><?= $nzm ?></h1>
    	<div class="row">
		<div class="col-12">
			<?php
			print_r($test);
			?>
			<div class="card">
				<div class="card-body">
					<table class="table table-bordered table-hover" id="datatable">
						<thead class="thead-light">
							<tr>
								<th class="text-center">No</th>
								<th class="text-center">Name</th>
								<th class="text-center">OLT Hardware Version</th>
								<th class="text-center">IP Number</th>
								<th class="text-center">Telnet Port</th>
								<th class="text-center">SNMP Port</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody id="tbody">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
