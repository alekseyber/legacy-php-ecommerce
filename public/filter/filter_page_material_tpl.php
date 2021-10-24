<?php defined('_WORK') or die('Restricted access'); ?>
<div class="filter-menu sec-l-menu" data-selector="app-in-load-filter">
    <?php if ((ltrim($this->clearLink, '/') != (ltrim($this->uri, '/'))) && ($this->clearLink != '')) : ?>
        <div class="list-group-item">
            <a class="btn btn-menu-nav btn-drk js-link-true2 drawer-cl" href="<?= $this->clearLink; ?>">
                <span>Очистить фильтр</span>
            </a>
        </div>
    <?php endif; ?>
    <?php if (($this->gruppColorLink)) : ?>
        <div class="list-group-item">
            <a class="btn btn-menu-nav btn-drk js-link-true2 drawer-cl" href="<?= $this->gruppColorLink; ?>">
                <span>Разбить цвета</span>
            </a>
        </div>
    <?php endif; ?>
    <?php if (($this->noGruppColorLink)) : ?>
        <div class="list-group-item">
            <a class="btn btn-menu-nav js-link-true2 drawer-cl" href="<?= $this->noGruppColorLink; ?>">
                <span>Сгруппировать по моделям</span>
            </a>
        </div>
    <?php endif; ?>    
    <div class="list-group-item m-t-menu filter-pr">
        <div class="filter-pr-title">Фильтр по цене</div>
        <input 
            type="hidden" 
            id="pricefilterslider" 
            name="pricefilterslider" 
            data-uri="<?= $uri ?>" 
            data-pricefr="<?= $pricefr ?>" 
            data-priceto="<?= $priceto ?>" 
            data-block="<?= $block ?>" 
            data-pricefrlimit="<?= $pricefrLimit ?>" 
            data-pricetolimit="<?= $pricetoLimit ?>" 
            data-prefix="<?= $priceprefix ?>" 
            data-postfix="<?= $pricepostfix ?>" 
            data-step="<?= $pricestep ?>" 
            value=""
            >
    </div>   
    <div class="list-group-item">
        <div 
            class="tab-content w-100"
            data-selector="tableftmenugrupp"
            data-bs-def-target="#v-tab-filterhome"
            data-bs-leftfilter="true"
            >
            <div class="tab-pane active" id="v-tab-filterhome">
                <div class="nav nav-tabs">
                    <?php foreach ($filter as $itemgr) : ?>
                        <button type="button" class="btn btn-menu-nav" data-bs-target="#v-tab-filter<?= $itemgr['alias']; ?>" data-bs-toggle="tableftmenu">
                            <span><?= $itemgr['title']; ?></span>
                            <span class="material-icons right">arrow_forward</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($filter as $group_id => $group_item) : ?>
                <div class="tab-pane" id="v-tab-filter<?= $group_item['alias']; ?>">
                    <button type="button" class="btn btn-menu-nav" data-bs-target="#v-tab-filterhome" data-bs-toggle="tableftmenu">
                        <span class="material-icons left">arrow_back</span>
                        <span><?= $group_item['title']; ?></span>
                    </button>
                    <?php if ($group_item['color']) : ?>
                        <div class="f-color-gr">
                            <?php
                            foreach ($group_item['attrs'] as $attr_id => $value) :
                                $checked = $value['active'] ? ' checked' : '';
                                $disabled = $value['block'] ? ' disabled' : '';
                                $id = 'fcheckboxcolor' . $value['alias'] . $attr_id;
                                ?>
                                <div class="form-color-check">
                                    <input class="form-color-input" 
                                           data-selector="js-link-btn" 
                                           type="checkbox" 
                                           id="<?= $id; ?>" 
                                           data-href="<?= $value['link']; ?>"
                                           style="background-color: <?= $value['colorkey']; ?>"                                              
                                           <?= $checked; ?>
                                           <?= $disabled; ?>
                                           >
                                    <label class="form-check-label" for="<?= $id; ?>"><?= $value['title']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($group_item['sizes']) : ?>
                        <div class="p-2">
                            <?php
                            foreach ($group_item['attrs'] as $attr_id => $value) :
                                $active = $value['active'] ? ' active' : '';
                                $disabled = $value['block'] ? ' disabled' : '';
                                ?>
                                <button type="button" data-selector="js-link-btn" class="btn btn-outline btn-size<?= $active; ?>" data-href="<?= $value['link']; ?>" <?= $disabled; ?>><?= $value['title']; ?></button>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="p-2">
                            <?php
                            foreach ($group_item['attrs'] as $attr_id => $value) :
                                $checked = $value['active'] ? ' checked' : '';
                                $disabled = $value['block'] ? ' disabled' : '';
                                $id = 'fcheckbox' . $value['attr_group_id'] . $attr_id;
                                ?>                                   
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           data-selector="js-link-btn" 
                                           type="checkbox" 
                                           id="<?= $id; ?>" 
                                           data-href="<?= $value['link']; ?>" 
                                           <?= $checked; ?>
                                           <?= $disabled; ?>
                                           >
                                    <label class="form-check-label" for="<?= $id; ?>"><?= $value['title']; ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>