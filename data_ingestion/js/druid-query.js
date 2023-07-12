// Add an ENTER event to submit the query automatically
$(document).ready(() => {
    document.getElementById("druid_query").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            document.getElementById("monitor_button").click();
        }
    })
});

function sendQueryToPHP() {
    jsonToSend = { "query": $("#druid_query").val() };
    $("#spinner_query").html('<div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div>');
    $.ajax({
        type: 'POST',
        url: 'druid-query.php',
        data: jsonToSend
    }).done((data) => {
        console.log(data);
        data = JSON.parse(data);
        buildTable(data, "#query_results");
        $('#spinner_query').empty();
    })
}

function buildTable(data, selector) {
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
    content += "</tr>"
    for (object in data) {
        content += "<tr>";
        for (let header of tableHeaders) {
            content += "<td>" + data[object][header] + "</td>";
        }
        content += "</tr>";
    }
    content += "</table>"
    content += "</div>"
    $(selector).html(content);
}