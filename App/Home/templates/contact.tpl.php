<style>
.call-me, .mail-me {
  cursor: pointer;
}
.call-me {
  display: inline !important;
}
</style>
<script>
  jQuery(document).ready(function () {
    jQuery('.call-me').click(function() {
      window.location.href = 'tel:' + jQuery(this).html();
      gtag('event', 'submit', {
        'event_category': 'inquire',
        'event_label': 'call'
      });
    });
    jQuery('.mail-me').click(function() {
      window.location.href = 'mailto:<?= $this->VARS['info_email'] ?>';
      gtag('event', 'submit', {
        'event_category': 'inquire',
        'event_label': 'mail'
      });
    });
  });
  function trigger_form() {
    gtag('event', 'submit', {
      'event_category': 'inquire',
      'event_label': 'form'
    });
  }
</script>
<!-- w3ls -->
<div class="w3ls">
<!---728x90-->
  <div class="container">
    <div class="w3ls-top heading">
      <h3>お問合せ</h3>
      <div class="mail-grids wthree-22">
        <div class="row">
				<div class="col-md-6 mail-grid-left">
					<div class="mail-grid-left1">
            <?= $this->VARS['msg']; ?>
						<form action="" method="post" style="text-align: left;">
              <input type="hidden" name="captcha_validation" value="<?= $this->VARS['VAL_CAPTCHA_VALIDATION'] ?>" />
							<input type="text" name="name" placeholder="タナカ" required="" />
							<input type="email" name="email" placeholder="info@unix-e.co.jp" required="" />
							<input type="text" name="subject" placeholder="件名" required="" />
							<textarea type="text" name="message" placeholder="内容" required=""></textarea>
              <div class="row">
                <div class="col-xs-6" style="padding-left: 0px;">
                  <input type="text" id="captch" name="captcha_input" style="margin: 0 0 1em 0; width: 100%;" required="" />
                </div>
                <div class="col-xs-6" style="padding-right: 0px;">
                  <span style="display: block; background-color: white; width: 160px; float: right; margin: 0 0 1em 0;">
                    <?= $this->VARS['VAL_CAPTCHA_PICTURE'] ?>
                  </span>
                </div>
              </div>
							<input type="submit" name="submit" value="送る" onclick="trigger_form();" />
						</form>
					</div>
				</div>
				<div class="col-md-6 mail-grid-right">
					<div class="mail-grid-right1 agile-22" style="text-align: left;">
						<ul>
							<li><i class="fa fa-map-marker contact_icon" aria-hidden="true"></i></li>
							<li>〒112-0002 東京都文京区小石川</li>
						</ul>
						<ul>
							<li><i class="fa fa-send contact_icon" aria-hidden="true"></i></li>
							<li><span class="mail-me">info@unix-e.co.jp</span></li>
						</ul>
						<ul>
							<li><i class="fa fa-phone contact_icon" aria-hidden="true"></i></li>
							<li>
                <span><span class="call-me">03-5840-6200</span></span>
                <span><span class="call-me">03-5840-6205</span></span>
              </li>
						</ul>
					</div>
				</div>
        </div>
				<div class="clearfix"> </div>
			</div>
    </div>
  </div>
</div>
<!-- w3ls -->
