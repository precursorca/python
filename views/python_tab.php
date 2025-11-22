<div id="python-tab"></div>
<h2 data-i18n="python.title"></h2>

<table id="python-tab-table"
       class="table table-responsive table-striped table-condensed"
       style="max-width: 800px;">
    <tbody></tbody>
</table>

<script>
$(document).on('appReady', function(){

    $.getJSON(appUrl + '/module/python/get_data/' + serialNumber, function(data){

        $('#python-cnt').text(data.length);
        
        var tbody = $('#python-tab-table tbody');
        tbody.empty();

        $.each(data, function(index, record){

            $.each(record, function(key, val){

                tbody.append(
                    $('<tr>').append(
                        $('<th>').text(i18n.t('python.column.' + key)),
                        $('<td>').text(val)
                    )
                );

            });


        });
    });
});
</script>
