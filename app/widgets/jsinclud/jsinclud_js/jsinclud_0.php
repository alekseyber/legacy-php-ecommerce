var cityselect = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/delivery/typeahead?query=%QUERY'
    }
});
cityselect.initialize();
$("#typeahead-city").typeahead({
// hint: false,
    minLength: 3,
    highlight: true
}, {
    name: 'cityselect',
    display: 'CityName',
    limit: 20,
    source: cityselect,
    templates: {
        empty: [
            '<div class="empty-message">',
            'Сожалеем, ни чего не найдено',
            '</div>'
        ].join('\n'),
        suggestion: function (s) {
        //console.log('sssss',s);
            return '<div>' + s.CityName + ' - ' + s.OblName + '</div>';
        }

    }

});
$('#typeahead-city').bind('typeahead:select', function (ev, suggestion) {
//console.log(suggestion); 
$('input[name="receiverCityId"]').val(suggestion.ID);
var link = path + '/delivery?receiverCityId=' + encodeURIComponent(suggestion.ID);
    contrUpdate(false, link);
   // window.location = path + '/delivery?receiverCityId=' + encodeURIComponent(suggestion.ID);
});

 $(function () {
    $('[data-toggle="tooltip"]').tooltip();
  });