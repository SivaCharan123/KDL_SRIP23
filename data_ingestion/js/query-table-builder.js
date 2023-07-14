function buildTableFromQuery(data, selector, maxLimit) {
    var tableHeaders = [];
    if (data.length == 0) {
        return;
    }
    for (key in data[0]) {
        tableHeaders.push(key);
    }

    content = '<div class="table-responsive">'
    content += '<table class="table">';
    content += "<tr>";
    for (var i = 0; i < tableHeaders.length; i++) {
        content += "<th>" + tableHeaders[i] + "</th>";
    }
    content += "</tr>";
    var q = 0;
    for (object in data) {
        q += 1;
        content += "<tr>";
        for (let header of tableHeaders) {
            content += "<td>" + data[object][header] + "</td>";
        }
        content += "</tr>";
        if(maxLimit != 0)
        {
            if(q > maxLimit)
            {
                break;
            }
        }
    }
    content += "</table>"
    content += "</div>"
    $(selector).html(content);
}