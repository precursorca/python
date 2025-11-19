<div id="python-tab"></div>
<h2 data-i18n="python.title"></h2>

<table id="python-tab-table"></table>

<script>
$(document).on('appReady', function(){
    $.getJSON(appUrl + '/module/python/get_data/' + serialNumber, function(data){
        var table = $('#python-tab-table');
        $.each(data, function(key,val){
            var th = $('<th>').text(i18n.t('python.column.' + key));
            var td = $('<td>').text(val);
            table.append($('<tr>').append(th, td));
        });
    });
});
</script>
