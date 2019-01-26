<script>
function start_trial() {
	window.location = '<?= $this->VARS['link_trial'] ?>';
}

</script>
	<!-- w3ls -->
	<div class="w3ls">
		<!---728x90-->
		<div class="container">
			<div class="w3ls-top heading">
				<h3><?= $this->VARS['lang_title'] ?></h3>
				<p class="wt"><?= $this->VARS['lang_subtitle'] ?></p>
			</div>
			<div class="w3ls-bottom">
				<div class="w3ls-one">
					<div class="col-md-6 w3ls-left wow fadeInLeft animated" data-wow-delay=".5s">
						<h4><a href="<?= $this->VARS['link_user'] ?>"><span class="fa fa-user"></span> <?= $this->VARS['lang_subtitle1'] ?></a></h4>
						<p class="wt"><?= $this->VARS['lang_subtext1'] ?></p>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-6 w3ls-left wow fadeInRight animated" data-wow-delay=".5s">
						<h4><a href="<?= $this->VARS['link_admin'] ?>"><span class="fa fa-wrench"></span> <?= $this->VARS['lang_subtitle2'] ?></a></h4>
						<p class="wt"><?= $this->VARS['lang_subtext2'] ?></p>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- w3ls -->
