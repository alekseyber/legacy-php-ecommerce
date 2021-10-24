<?php defined('_WORK') or die('Restricted access'); ?>
<div class="filter-menu sec-l-menu">
    <?php if ((ltrim($this->clearLink, '/') != (ltrim($this->uri, '/'))) && ($this->clearLink != '')) : ?>
        <div class="list-group-item m-t-menu">
            <a class="btn btn-menu-nav btn-drk js-link-true2 drawer-cl" href="<?= $this->clearLink; ?>">
                <span>Очистить фильтр</span>
            </a>
        </div>
    <?php endif; ?>
    <?php if (($this->gruppColorLink)) : ?>
        <div class="list-group-item m-t-menu">
            <a class="btn btn-menu-nav btn-drk js-link-true2 drawer-cl" href="<?= $this->gruppColorLink; ?>">
                <span>Разбить цвета</span>
            </a>
        </div>
    <?php endif; ?>
    <?php if (($this->noGruppColorLink)) : ?>
        <div class="list-group-item m-t-menu">
            <a class="btn btn-menu-nav js-link-true2 drawer-cl" href="<?= $this->noGruppColorLink; ?>">
                <span>Сгруппировать по моделям</span>
            </a>
        </div>
    <?php endif; ?>
    <?php if ($this->js) : ?>
        <div class="list-group-item m-t-menu filter-pr">
            <div class="filter-pr-title">Фильтр по цене</div>
            <input type="hidden" id="pricefilterslider" name="pricefilterslider" data-uri="<?= $uri ?>" data-pricefr="<?= $pricefr ?>" data-priceto="<?= $priceto ?>" data-block="<?= $block ?>" data-pricefrlimit="<?= $pricefrLimit ?>" data-pricetolimit="<?= $pricetoLimit ?>" data-prefix="<?= $priceprefix ?>" data-postfix="<?= $pricepostfix ?>" data-step="<?= $pricestep ?>" value="">
        </div>
    <?php endif; ?>
    <div class="list-group-item m-t-menu">
        <div class="tab-content">
            <div class="tab-pane active" id="v-tab-Filterhome">
                <div class="nav nav-tabs">
                    <?php foreach ($filter as $itemgr) : ?>
                        <button type="button" class="btn btn-menu-nav js-l-return-tab" data-target="v-tab-Filter<?= $itemgr['alias']; ?>">
                            <span><?= $itemgr['title']; ?></span>
                            <span class="material-icons right">arrow_forward</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($filter as $group_id => $group_item) : ?>
                <div class="tab-pane" id="v-tab-Filter<?= $group_item['alias']; ?>">
                    <button type="button" class="btn btn-menu-nav js-l-return-tab" data-target="v-tab-Filterhome">
                        <span class="material-icons left">arrow_back</span>
                        <span><?= $group_item['title']; ?></span>
                    </button>
                    <div class="m-t-con list-group">
                        <?php if ($group_item['color']) : ?>
                            <div class="checkbox-wr list-group-item">
                                <?php
                                foreach ($group_item['attrs'] as $attr_id => $value) :
                                    $checked = $value['active'] ? ' checked' : '';
                                    $disabled = $value['block'] ? ' disabled' : '';
                                ?>
                                    <div class="checkbox color">
                                        <label>
                                            <input type="checkbox" class="js-checkbox-true" data-color="<?= $value['colorkey']; ?>" data-href="<?= $value['link']; ?>" <?= $checked; ?><?= $disabled; ?>><span class="clr-title"><?= $value['title']; ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($group_item['sizes']) : ?>
                            <div class="btn-wr list-group-item">
                                <?php
                                foreach ($group_item['attrs'] as $attr_id => $value) :
                                    $active = $value['active'] ? ' active' : '';
                                    $disabled = $value['block'] ? ' disabled' : '';
                                ?>
                                    <button type="button" class="btn btn-outline btn-size js-button-true<?= $active; ?>" data-href="<?= $value['link']; ?>" <?= $disabled; ?>><?= $value['title']; ?></button>
                                <?php endforeach; ?>

                            </div>
                        <?php else : ?>
                            <div class="checkbox-wr list-group-item">
                                <?php
                                foreach ($group_item['attrs'] as $attr_id => $value) :
                                    $checked = $value['active'] ? ' checked' : '';
                                    $disabled = $value['block'] ? ' disabled' : '';
                                ?>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="js-checkbox-true" data-href="<?= $value['link']; ?>" <?= $checked; ?><?= $disabled; ?>> <?= $value['title']; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div> <!-- tab-pane -->
            <?php endforeach; ?>
        </div>
    </div>
</div><!-- filter-menu -->