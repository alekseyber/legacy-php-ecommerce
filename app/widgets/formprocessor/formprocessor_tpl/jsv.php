<?php

$jsv = '';
if (isset($input['disabled'])) {
    $jsv .= ' disabled';
}
if (!empty($input['data'])) {
    foreach ($input['data'] as $k => $v) {
        if ($k == 'oldvalue') {
            $jsv .= ' data-oldvalue = "' . $input['value'] . '"';
        } else {
            $jsv .= ' data-' . $k . ' = "' . $v . '"';
        }
    }
}
if (!empty($input['rules']) && $this->form['validate']) {
    foreach ($input['rules'] as $k => $v) {
        if ($v) {
            $jsv .= ' ' . $k . ' = "' . $v . '"';
        } else {
            $jsv .= ' ' . $k;
        }
    }
}
?>
<?= $jsv ?>