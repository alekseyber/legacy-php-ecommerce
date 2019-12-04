<?php if ($parentformclass) { ?>
    <div class="<?= $parentformclass ?>">
    <?php } ?>
    <?php if ($header) { ?><h1><?= $header ?></h1><?php } ?>
    <?php if ($message) { ?><p class="message"><?= $message ?></p><?php } ?>
    <?php
    if ($form) {
        $formattr = '';
        if ($formname) {
            $formattr .= ' name="' . $formname . '"';
        }
        if ($action) {
            $formattr .= ' action="' . $action . '"';
        }
        if ($method) {
            $formattr .= ' method="' . $method . '"';
        }
        if ($check) {
            $formattr .= ' onsubmit="return checkForm(this)"';
        }
        if ($enctype) {
            $formattr .= ' enctype="' . $enctype . '"';
        }
        if ($formid) {
            $formattr .= ' id="' . $formid . '"';
        }
        if ($formclass) {
            $formattr .= ' class="' . $formclass . '"';
        }
        if ($adminlte && $validate) {
            $formattr .= ' data-toggle="validator"';
        }
        if ($dataattr) {
            foreach ($dataattr as $k => $v) {
                $formattr .= ' data-' . $k . '="' . $v . '"';
            }
        }
        ?>
        <form<?= $formattr ?>> 
        <?php } else { ?>
            <div class="<?= $formclass ?>">
            <?php } ?>
            <?php foreach ($this->inputs as $input) { ?>
                <?php include "f_" . $input['type'] . ".php"; ?>
            <?php } ?>
            <?php if ($form) { ?>
                <?php if (!isset($submit) && !$img) { ?>
                    <button type="submit" name="submit" class="btn btn-success">Сохранить</button>

                <?php } ?>
        </form>
    <?php } else { ?>
    </div>
<?php } ?>
<?php if ($parentformclass) { ?></div><?php } ?>

<?php if ($validate && $formclass && !$adminlte) { ?>
    <script>
        var forms = document.getElementsByClassName('<?= $formclass ?>');
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
<?php } ?>
