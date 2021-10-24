<section class="filter-nav">    
    <?php if ((ltrim($this->clearLink, '/') != (ltrim($this->uri, '/'))) && ($this->clearLink != '')): ?>
        <div class="clear-all-refinements">
            <a href="<?= $this->clearLink; ?>" class="btn btn-transparent js-link-true2">Очистить фильтр</a>
        </div>   
    <?php endif; ?>
    <?php if (($this->gruppColorLink)): ?>
        <div class="clear-all-refinements">
            <a href="<?= $this->gruppColorLink; ?>" class="btn btn-black js-link-true2">Показать все цвета</a>
        </div>   
    <?php endif; ?>
    <?php if (($this->noGruppColorLink)): ?>
        <div class="clear-all-refinements">
            <a href="<?= $this->noGruppColorLink; ?>" class="btn btn-transparent js-link-true2">Сгруппировать по моделям</a>
        </div>   
    <?php endif; ?>
    <?php if ($this->js): ?>    
        <div class="refinement">
            <h3 class="panel-title">Цена</h3>
            <div class="filter-price">
                <input  type = "hidden" id = "pricefilterslider" name = "pricefilterslider" data-uri="<?= $uri ?>" data-pricefr="<?= $pricefr ?>" data-priceto="<?= $priceto ?>" data-block="<?= $block ?>" data-pricefrLimit="<?= $pricefrLimit ?>" data-pricetoLimit="<?= $pricetoLimit ?>" value = "">
            </div>
        </div> 
    <?php endif; ?>
    <?php foreach ($filter as $group_id => $group_item): ?>
        <div class="refinement">
            <h3 class="panel-title">
                <a data-toggle="collapse" href="#collapseFilter<?= $group_item['alias']; ?>" aria-expanded="true" aria-controls="collapseFilter<?= $group_item['alias']; ?>">
                    <?= $group_item['title']; ?>
                </a>
            </h3>
            <div id="collapseFilter<?= $group_item['alias']; ?>" class="panel-collapse collapse in show">

                <?php
                if (!$group_item['color'] && !$group_item['sizes']) {
                    $class = 'noсolour';
                } else {
                    $class = $group_item['color'] ? 'сolour' : 'size';
                }
                ?> 
                <ul class="nav-left nav-pills  <?= $class; ?> nav-stacked refinements-item-<?= $group_item['alias']; ?>">
                    <?php
                    foreach ($group_item['attrs'] as $attr_id => $value):
                        $classActive = $value['active'] ? ' active' : '';
                        $titlelink = $value['active'] ? 'Снять выбор:' : 'Выбрать:';
                        $classBlock = $value['block'] ? ' blocklink' : '';
                        $attrBlock = $value['block'] ? 'true' : '';
                        ?>
                        <?php if ($class == 'noсolour'): ?>
                            <li class="nav-item">
                                <a class="nav-link js-link-true<?= $classActive; ?><?= $classBlock; ?>" data-block="<?= $attrBlock; ?>" href="<?= $value['link']; ?>" title="<?= $titlelink; ?> <?= $value['title']; ?>"><?= $value['title']; ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if ($class == 'сolour'): ?>   
                            <li class="nav-item item-color dropdown-item-<?= $value['alias']; ?>">
                                <a class="nav-link js-link-true<?= $classActive; ?><?= $classBlock; ?>" data-block="<?= $attrBlock; ?>" href="<?= $value['link']; ?>" title="<?= $titlelink; ?> <?= $value['title']; ?>">        
                                    <span><?= $value['title']; ?></span>
                                </a> 
                            </li>
                        <?php endif; ?>
                        <?php if ($class == 'size'): ?>   
                            <li class="nav-item">
                                <a class="nav-link js-link-true<?= $classActive; ?><?= $classBlock; ?>" data-block="<?= $attrBlock; ?>" href="<?= $value['link']; ?>" title="<?= $titlelink; ?> <?= $value['title']; ?>"><span><?= $value['title']; ?></span></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div> <!-- refinement -->
    <?php endforeach; ?>
</section><!-- filter-nav -->