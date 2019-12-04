<span class="btn btn-success fileinput-button">
    <i class="glyphicon glyphicon-plus"></i>
    <span>Добавить файлы...</span>                                                                
    <input class="fileupload-input" type="file" name="files[]" multiple>                                                             
</span>
<?php if ($formid) { ?>
    <input type="hidden" name="id" value="<?= $formid; ?>">
<?php } ?>