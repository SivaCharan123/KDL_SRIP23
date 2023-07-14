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
        buildTableFromQuery(data, "#query_results", 100);
        $('#spinner_query').empty();
    })
}