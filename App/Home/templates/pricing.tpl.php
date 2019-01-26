<style>
.pricing_icon {
	width: 35px;
}
</style>
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
				<div class="responsive_width">
					<div class="pull-right"><button class="btn btn-primary" onclick="window.location='<?= $this->VARS['link_trial']; ?>'"><?= $this->VARS['lang_trial']; ?></button></div>
					<table class="table table-striped">
				    <thead>
				      <tr>
				        <th><?= $this->VARS['lang_price']; ?></th>
								<th><?= $this->VARS['lang_plan_zero']; ?><span class="label label-danger" style="margin-left: 10px;"><?= $this->VARS['lang_early_bird']; ?></span></th>
				        <th><s><?= $this->VARS['lang_plan_one']; ?></s><span class="label label-success" style="margin-left: 10px;"><?= $this->VARS['lang_recommended']; ?></span></th>
				        <th><?= $this->VARS['lang_plan_two']; ?></th>
								<th><?= $this->VARS['lang_plan_three']; ?></th>
				      </tr>
				    </thead>
				    <tbody>
				      <tr>
				        <td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image1.png" /><?= $this->VARS['lang_storage']; ?></td>
								<td><?= $this->VARS['lang_storage_one']; ?></td>
				        <td><s><?= $this->VARS['lang_storage_one']; ?></s></td>
				        <td><?= $this->VARS['lang_storage_two']; ?></td>
								<td><?= $this->VARS['lang_storage_three']; ?></td>
				      </tr>
							<tr>
				        <td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image2.png" /><?= $this->VARS['lang_add_storage']; ?></td>
								<td><?= $this->VARS['lang_add_storage_one']; ?></td>
				        <td><s><?= $this->VARS['lang_add_storage_one']; ?></s></td>
				        <td><?= $this->VARS['lang_add_storage_two']; ?></td>
								<td><?= $this->VARS['lang_add_storage_three']; ?></td>
				      </tr>
							<tr>
				        <td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image3.png" /><?= $this->VARS['lang_file_size']; ?></td>
								<td><?= $this->VARS['lang_file_size_one']; ?></td>
				        <td><s><?= $this->VARS['lang_file_size_one']; ?></s></td>
				        <td><?= $this->VARS['lang_file_size_two']; ?></td>
								<td><?= $this->VARS['lang_file_size_three']; ?></td>
				      </tr>
							<tr>
				        <td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image4.png" /><?= $this->VARS['lang_access_control']; ?></td>
								<td><?= $this->VARS['lang_access_control_one']; ?></td>
				        <td><s><?= $this->VARS['lang_access_control_one']; ?></s></td>
				        <td><?= $this->VARS['lang_access_control_two']; ?></td>
								<td><?= $this->VARS['lang_access_control_three']; ?></td>
				      </tr>
							<tr>
				        <td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image5.png" /><?= $this->VARS['lang_unlimited_users']; ?></td>
								<td><?= $this->VARS['lang_unlimited_users_one']; ?></td>
				        <td><s><?= $this->VARS['lang_unlimited_users_one']; ?></s></td>
				        <td><?= $this->VARS['lang_unlimited_users_two']; ?></td>
								<td><?= $this->VARS['lang_unlimited_users_three']; ?></td>
				      </tr>
							<tr>
				        <td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image6.png" /><?= $this->VARS['lang_platforms']; ?></td>
								<td><?= $this->VARS['lang_platforms_one']; ?></td>
				        <td><s><?= $this->VARS['lang_platforms_one']; ?></s></td>
				        <td><?= $this->VARS['lang_platforms_two']; ?></td>
								<td><?= $this->VARS['lang_platforms_three']; ?></td>
				      </tr>
							<tr>
								<td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image7.png" /><?= $this->VARS['lang_permissions']; ?></td>
								<td><?= $this->VARS['lang_permissions_one']; ?></td>
								<td><s><?= $this->VARS['lang_permissions_one']; ?></s></td>
								<td><?= $this->VARS['lang_permissions_two']; ?></td>
								<td><?= $this->VARS['lang_permissions_three']; ?></td>
							</tr>
							<tr>
								<td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image8.png" /><?= $this->VARS['lang_resumable']; ?></td>
								<td><?= $this->VARS['lang_resumable_one']; ?></td>
								<td><s><?= $this->VARS['lang_resumable_one']; ?></s></td>
								<td><?= $this->VARS['lang_resumable_two']; ?></td>
								<td><?= $this->VARS['lang_resumable_three']; ?></td>
							</tr>
							<tr>
								<td><img class="pricing_icon" src="<?= $this->VARS['URL_RESOURCE_DIR'] ?>/images/pricing/image9.png" /><?= $this->VARS['lang_log']; ?></td>
								<td><?= $this->VARS['lang_log_one']; ?></td>
								<td><s><?= $this->VARS['lang_log_one']; ?></s></td>
								<td><?= $this->VARS['lang_log_two']; ?></td>
								<td><?= $this->VARS['lang_log_three']; ?></td>
							</tr>
				    </tbody>
				  </table>
					<div class="pull-right"><button class="btn btn-primary" onclick="window.location='<?= $this->VARS['link_trial']; ?>'"><?= $this->VARS['lang_trial']; ?></button></div>
				</div>
			</div>
		</div>
	</div>
	<!-- w3ls -->
