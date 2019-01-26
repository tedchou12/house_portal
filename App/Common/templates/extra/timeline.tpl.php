
<? if (empty($this->VARS['ROW_TIMELINE'])) : ?>
<? else : ?>
    <link rel="stylesheet" href="<?= $this->VARS['URL_ROOT_DIR'] ?>Resources/css/v-timeline.css">

    <section id="cd-timeline" class="cd-container">
        <? foreach ($this->VARS['ROW_TIMELINE'] as $ITEM) : ?>
            <div class="cd-timeline-block">
                <div class="cd-timeline-img cd-picture">
                    <img src="<?= GetUserFaceSrc($ITEM['report_creator']) ?>">
                </div>

                <div class="cd-timeline-content">
                    <span class="cd-date"><?= $ITEM['report_created'] ?> <?= IdToUserName($ITEM['report_creator']) ?></span>
      
                    <div class="container">
                        <? if ($ITEM['report_type'] == REPORT_TYPE_MILESTONE) : ?>
                            <?= Lang($ITEM['report_description']) ?>
                        <? else : ?>
                            <?= $ITEM['report_description'] ?>
                        <? endif; ?>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </section>
<? endif; ?>
