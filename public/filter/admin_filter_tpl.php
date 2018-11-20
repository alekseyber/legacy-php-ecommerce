<?php defined('_WORK') or die('Restricted access'); ?>
<?php
	//debug($this->filter);
	//die;
?>
<div class="nav-tabs-custom" id="filter">
    <ul class="nav nav-tabs">
        <?php
			$i = 1;
			foreach ($this->filter as $group_id => $group_item):
		?>
		<li<?php if ($i == 1) echo ' class="active"' ?>><a href="#tab_<?= $group_id ?>" data-toggle="tab" aria-expanded="true"><?= $group_item['title'] ?></a></li>
		<?php
            $i++;
			endforeach;
		?>
        <li class="pull-right">
            <a href="#" id="reset-filter">Сброс</a>
		</li>
	</ul>
	
	
	
	
	<div class="tab-content">
		
		
		<?php
			$i = 1;
			foreach ($this->filter as $group_id => $group_item):
			// title
			$group_item['status'] = $group_item['status'] ? 'Да' : 'Нет';
			$group_item['filter'] = $group_item['filter'] ? 'Да' : 'Нет';
			$group_item['cartproduct'] = $group_item['cartproduct'] ? 'Да' : 'Нет';
		?>
		
		
		<?php if (!empty($group_item['attrs'])): ?>        
		<div class="tab-pane<?php if ($i == 1) echo ' active' ?>" id="tab_<?= $group_id ?>">
			<div class="box">
				<div class="box-header">
					<p>Алиас: <strong><?= $group_item['alias'] ?></strong></p>
					<p>Включена: <strong><?= $group_item['status'] ?></strong></p>
					<p>Фильтр товаров: <strong><?= $group_item['filter'] ?></strong></p>
					<p>Карточка товара: <strong><?= $group_item['cartproduct'] ?></strong></p>			
				</div>
				<div class="box-body">
					<div class="row">
						<?php foreach ($group_item['attrs'] as $attr_id => $value): ?>
						<?php
							$status = $value['status'] ? '' : ' (выключен)';
							$class = $status ? ' class = "bg-red"' : '';
							if (isset($value['on']) && $value['on']) {
								$checked = ' checked';
								} else {
								$checked = null;
							}
						?>
						<div class="col-md-2">    
							<div class="form-group filter-cart-product">
								<label <?= $class ?>>
									<input type="checkbox" name="attrs[<?= $group_id ?>][<?= $attr_id ?>]" value="<?= $value['bitvalue'] ?>"<?= $checked ?>> <?= $value['value'] ?>
								</label>
							</div>
						</div>
						<?php
							$i++;
							endforeach;
						?>
						
					</div><!-- row -->
				</div><!-- box-body -->
			</div><!-- box -->
		</div><!-- tab-pane -->
		<?php endif; ?>
		<?php endforeach; ?>
		
		
		
	</div><!-- tab-content -->
</div><!-- nav-tabs-custom -->
