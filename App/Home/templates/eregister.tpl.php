<script>
    function submit_eregister_form()
    {
        jQuery.ajax({
            url: 'https://www.3d-products.com/adapter.php',
            // url: 'http://localhost/litocrm-web/adapter.php',
            type: 'POST',
            data: $('form#register').serialize(),
            beforeSend: function() {
                OpenRefreshBlock();
            },
            success: function(feedback) {
                feedback = ConvToObject(feedback);
                CloseBlock();
                if (IsValidFeedback(feedback)) {
                    if (feedback.success) {
                        if ('message' in feedback.callback && feedback.callback.message.toString().length) {
                            alert(feedback.callback.message);
                        }

                        return;
                    } else if (feedback.failure) {
                        if ('fields' in feedback.callback && feedback.callback.fields.toString().length) {
                            if ($('#'+ feedback.callback.fields).is(':text')) {
                                $('#'+ feedback.callback.fields).focus()
                            }
                        }

                        if ('message' in feedback.callback && feedback.callback.message.toString().length) {
                            alert(feedback.callback.message);
                        }

                        return;
                    }
                }

                alert('發生預期以外的錯誤，請聯絡軟體供應商');
            },
            error: function() {
                alert('伺服器發生的錯誤，請聯絡軟體供應商');
                CloseBlock();
            }
        });
    }
</script>
<!-- w3ls -->
<div class="w3ls">
    <!---728x90-->
    <div class="container">
        <div class="w3ls-top heading">
            <h3>車両査定</h3>
            <div class="mail-grids wthree-22">
              <div class="row">
                <div class="col-md-5 mail-grid-right">
                    <p class="wt" style="text-align: left;">下記フォームより、必要記入項目を入力してください。ご記入いただいた情報に基づき、当社からご連絡いたします｡※営業日時の関係で、ご連絡まで日数を要する場合がございます。ご了承下さい。</p>
                </div>
                <div class="col-md-7 mail-grid-left">
                    <div class="mail-grid-left1">
                        <form action="" method="post" id="register">
                            <input type="hidden" name="method" value="create_org">
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_name" style="padding-top: 0.5em;">お名前(漢字)</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_name" name="org_name" placeholder="田中" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="user_name" style="padding-top: 0.5em;">ふりがな</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="user_name" name="user_name" placeholder="タナカ" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_email" style="padding-top: 0.5em;">E-mail</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_email" name="org_email" placeholder="<?= $this->VARS['hint_email'] ?>" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_phone" style="padding-top: 0.5em;">電話番号</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_phone" name="org_phone" placeholder="<?= $this->VARS['hint_phone'] ?>" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_phone" style="padding-top: 0.5em;">郵便番号</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_phone" name="org_phone" placeholder="" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_phone" style="padding-top: 0.5em;">ご住所</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_phone" name="org_phone" placeholder="" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_industry" style="padding-top: 0.5em;">メーカー</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <select class="form-control" id="org_industry" name="org_industry">
                                    <?= $this->VARS['val_makers'] ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_industry" style="padding-top: 0.5em;">車種</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <select class="form-control" id="org_industry" name="org_industry">
                                    <?= $this->VARS['val_makers_types'] ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_industry" style="padding-top: 0.5em;">年式</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <select class="form-control" id="org_industry" name="org_industry">
                                    <?= $this->VARS['val_makers_types_year'] ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_phone" style="padding-top: 0.5em;">形式</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_phone" name="org_phone" placeholder="CBA-MAX74G" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                  <label class="control-label" for="org_refer" style="padding-top: 0.5em;">走行距離</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                  <select class="form-control" id="org_refer" name="org_refer">
                                    <?= $this->VARS['val_dists'] ?>
                                  </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                  <label class="control-label" for="org_headcount" style="padding-top: 0.5em;">基本色</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                  <select class="form-control" id="org_headcount" name="org_headcount">
                                    <?= $this->VARS['val_colors'] ?>
                                  </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                  <label class="control-label" for="org_year" style="padding-top: 0.5em;">シフト</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                  <select class="form-control" id="org_year" name="org_year">
                                    <?= $this->VARS['val_shifts'] ?>
                                  </select>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                    <label class="control-label" for="org_phone" style="padding-top: 0.5em;">原動機の型式</label>
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="text" id="org_phone" name="org_phone" placeholder="4A" required="">
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                  <label class="control-label" for="captch" style="padding-top: 0.5em;"><?= $this->VARS['lang_captch'] ?></label>
                                </div>
                                <div class="col-md-3 mail-grid-left">
                                    <input type="text" id="captch" name="captcha_input" required="" />
                                    <input type="hidden" name="captcha_validation" value="<?= $this->VARS['VAL_CAPTCHA_VALIDATION'] ?>" />
                                </div>
                                <div class="col-md-4 mail-grid-left">
                                  <div style="display: block; background-color: white; width: 160px;">
                                    <?= $this->VARS['VAL_CAPTCHA_PICTURE'] ?>
                                  </div>
                                </div>
                            </div>
                            <div class="row" style="margin-bottom: .5em;">
                                <div class="col-md-5 mail-grid-left" style="text-align: left;">
                                </div>
                                <div class="col-md-7 mail-grid-left">
                                    <input type="button" class="button" name="submit" value="查定スタート" onClick="submit_eregister_form();" style="width: 100%;" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
              </div>
                <div class="clearfix"> </div>
            </div>
        </div>
    </div>
</div>
<!-- w3ls -->
