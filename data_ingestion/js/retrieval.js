// The metadata.json fetched is loaded here on document.ready
var dataCatalogMeta = null;

// The CSV data fetched is loaded into this variable for downloading
var currentCSVdata = "";
var currentQuerydata = null;

var exampleQuery = `-- Select Stunting, 6 Year Schooling, Safe Water, Sanitation, and MPI status from the SDG1 taluka table and group by district
SELECT "District", 
ROUND(AVG("DEPRIVATION_HOUSEHOLD_BMI_BELOW_ADULT_OR_STUNTING_CHILD"),2) AS "% Stunting", 
ROUND(AVG("DERPIVATION_HOUSEHOLD_NO_MEMBER_SIX_YR_SCHOOL"),2) AS "% Without 6 Year Schooling",
ROUND(AVG("DEPRIVATION_HOUSEHOLD_NO_SAFE_WATER"),2) AS "% Without Safe Water",
ROUND(AVG("DERPIVATION_HOUSEHOLD_NO_SANITATION"),2) AS "% Without Sanitation",
ROUND(AVG("MPI"),3) AS "Multidimensional Poverty Index" 
FROM "1689254129_SDG_1_Taluka_Data" GROUP BY "District"`

// Create SQL Editor
var SQL_Editor = ace.edit("custom_query_input");
SQL_Editor.session.setMode("ace/mode/sql");

function loadExampleQuery()
{
    SQL_Editor.setValue(exampleQuery, 1);
}

function generateSDGFlag() {
    var inputs = document.querySelectorAll("#sdg_query_form input[name='sdg_query[]']");
    var sdg_flag = 0;
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].checked == true) {
            sdg_flag = sdg_flag | (1 << (i + 1));
        }
    }
    return sdg_flag;
}

function parseSDGFlags(flag)
{
    var sdgs = []
    for(var i = 1; i < 18; i++)
    {
        if(flag & (1 << i))
        {
            sdgs.push(i.toString());
        }
    }
    return sdgs;
}

// Download CSV to client's file given a druid table
function downloadCSV(druid_table) {
    // Send query to csv-from-druid.php
    jsonToSend = { "query": "SELECT * from \"" + druid_table + "\"" };
    console.log(jsonToSend)
    $.ajax({
        type: 'POST',
        url: 'csv-from-druid.php',
        data: jsonToSend
    }).done((data) => {
        data = JSON.parse(data);
        var filename = druid_table + ".csv"
        var filecontents = data["CSV"]
        var blob = new Blob([filecontents], { type: "text/plain;charset=utf-8" });
        saveAs(blob, filename);
    });
}

function buildRow(table_name, name, sdg_flags)
{
    var row = ""
    row += '<tr>'
    row += '<td>' + table_name + '</td>'
    row += '<td>' + name + '</td>'
    row += '<td>' + parseSDGFlags(sdg_flags).join(",") + '</td>'
    row += '<td><button class="btn btn-sm btn-secondary" onClick="downloadCSV(\'' + table_name + '\')"><span class="fas fa-download"></span></button></td>'
    row += '</tr>'
    return row
}

function buildTableFromMetaData(metadata, sdg) {
    var content = ""
    content += '<table class="table">'
    content += '<tr>'
    content += '<th>Table Name (On Druid)</th>'
    content += '<th>Name</th>'
    content += '<th>Relevant SDGs</th>'
    content += '<th>Download CSV</th>'
    content += '</tr>'

    for (table in metadata) {
        if (sdg == 0) {
            content += buildRow(table, metadata[table]["name"], metadata[table]["sdg_flags"]);
        }
        else
        {
            if(metadata[table]["sdg_flags"] & sdg)
            {
                content += buildRow(table, metadata[table]["name"], metadata[table]["sdg_flags"]);
            }
        }
    }

    content += '</table>'
    return content;
}

function disableExecuteButton()
{
    $("#custom_sql_query_button").prop('disabled', true);
    $("#custom_sql_query_button").html('<div class="spinner-border" role="status"><span class="sr-only"></span></div>')
}

function enableExecuteButton()
{
    $("#custom_sql_query_button").prop('disabled', false);
    $("#custom_sql_query_button").html('<span class="fa fa-play"></span> Execute SQL')
}

function executeSQLQueryFromEditor()
{
    var query = SQL_Editor.getValue();
    queryJSON = { "query": query };
    // Null existing CSV data
    currentCSVdata = "";
    currentQuerydata = null;
    // Disable the button
    disableExecuteButton();
    $.ajax({
        type: 'POST',
        url: 'csv-from-druid.php',
        data: queryJSON
    }).done((data) => {
        data = JSON.parse(data);
        if(data["Error"] == "")
        {
            // Load it into the variable
            currentCSVdata = data["CSV"];
            $.ajax({
                type: 'POST',
                url: 'druid-query.php',
                data: queryJSON
            }).done((data) => {
                data = JSON.parse(data);
                currentQuerydata = data;
                buildTableFromQuery(currentQuerydata, "#custom_query_results_table", $("#show_entry_limit").val());
                enableExecuteButton();
            });
        }
        else
        {
            console.log(data);
            buildTableFromQuery(data, "#custom_query_results_table", 0);
            enableExecuteButton();
        }
    });
}

// Called when user changes the number of entires to be shown
function changeNumberOfEntries()
{
    if(currentQuerydata != null)
    {
        buildTableFromQuery(currentQuerydata, "#custom_query_results_table", $("#show_entry_limit").val());
    }
}

// Download CSV
function downloadCustomQueryCSV()
{
    if(currentCSVdata == "")
    {
        // No data
        // Inform user
        $("#nocsv-alert").fadeTo(2000, 500).slideUp(500, function() {
            $("#nocsv-alert").slideUp(500);
        });
    }
    else
    {
        // Download
        var blob = new Blob([currentCSVdata], { type: "text/plain;charset=utf-8" });
        saveAs(blob, "query.csv");
    }
}
// Grab metadata
$(document).ready(() => {
    generate_checkboxes("sdg_checkboxes", "sdg_query");
    $("#nocsv-alert").hide();
    $.getJSON("fetch-metadata.php", (JSON) => {
        dataCatalogMeta = JSON;
        $("#sdg_query_results").html(buildTableFromMetaData(dataCatalogMeta, 0))
    });
});

$("#sdg_query_form").submit((e) => {
    e.preventDefault();
    flag = generateSDGFlag();
    $("#sdg_query_results").html(buildTableFromMetaData(dataCatalogMeta, flag))
});

