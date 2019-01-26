<script>

function redirect_personal() {
	window.location = '<?= $this->VARS['link_personal'] ?>';
}

function redirect_enterprise() {
	window.location = '<?= $this->VARS['link_enterprise'] ?>';
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
						<div class="ad-left">
							<img src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/7.jpg" alt="">
						</div>
						<div class="ad-right">
							<h4><?= $this->VARS['lang_subtitle1'] ?></h4>
							<p><?= $this->VARS['lang_subtext1'] ?></p>
							<div style="margin-top: 15px;"><input type="button" class="button" style="float: right; width: 100px;" value="<?= $this->VARS['lang_register'] ?>" onClick="redirect_personal();" /></div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="col-md-6 w3ls-left wow fadeInRight animated" data-wow-delay=".5s">
						<div class="ad-left">
							<img src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/8.jpg" alt="">
						</div>
						<div class="ad-right">
							<h4><?= $this->VARS['lang_subtitle2'] ?></h4>
							<p><?= $this->VARS['lang_subtext2'] ?></p>
							<div style="margin-top: 15px;"><input type="button" class="button" style="float: right; width: 100px;" value="<?= $this->VARS['lang_register'] ?>"onClick="redirect_enterprise();" /></div>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- w3ls -->
