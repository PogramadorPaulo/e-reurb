<!-- Page-header end -->
<div class="pcoded-inner-content" id="content">
	<!-- Main-body start -->
	<div class="main-body">
		<div class="page-wrapper">
			<!-- Page-body start -->
			<div class="page-body">

				<div class="card">
					<div class="card-header">
						<h5 class="card-title">Notificação</h5>
					</div>
					<div class="card-body">
						<div id="resposta"></div>
						<div class="d-flex justify-content-center mb-3">
							<div class="spinner-border" role="status" id="loader">
								<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
								<span class="sr-only">Loading...</span>
							</div>
						</div>
						<?php foreach ($list as $item) : ?>
							<h5><?php echo $item['not_titulo'] ?></h5><br>
							<p><?php echo $item['not_conteudo'] ?></p>
							<?php if ($item['not_link'] !='') {
								echo '<p><a href="' . $item['not_link'] . '" target="_blank">' . $item['not_link'] . '</a></p>';
							} ?>
							<p><?php echo date("d/m/Y H:i:s", strtotime($item['not_date'])) ?></p>

						<?php endforeach; ?>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>