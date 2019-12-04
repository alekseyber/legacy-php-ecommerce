//<script>
    /* typeahead-main */
    var <?= $idinput ?> = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
<?php if ($select): ?>
        local: <?= $select ?>,
                // identify: function(obj) { return obj.id; },        
<?php endif; ?>
<?php if ($remote): ?>
        remote: {
        wildcard: '%QUERY',
                url: adminpath + '/ajax/typeahead?query=%QUERY&data=<?= $remote ?>'
        }
<?php endif; ?>
    });
<?= $idinput ?>.initialize();
    $("#<?= $idinput ?>").typeahead({
// hint: false,
    highlight: true
    }, {
    name: '<?= $idinput ?>',
    display: '<?= $title ?>',
    limit: 10,
    source: <?= $idinput ?>,
    templates: {
        empty: [
            '<div class="empty-message">',
            'Не найдено',
            '</div>'
        ].join('\n')},
    });
    $('#<?= $idinput ?>').bind('typeahead:select', function (ev, suggestion) {
    //console.log(suggestion.id);
<?php if ($changid): ?>
        $('#<?= $idinput ?>').typeahead('val', suggestion.id);
<?php endif; ?>
    //  console.log($(this).val);
    //console.log(ev);
    // window.location = adminpath + '/order?acquirers_id=' + encodeURIComponent(suggestion.id);
    });
//===================
//</script>