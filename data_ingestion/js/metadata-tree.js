var content = "";
var pixelsPerHierarchy = 30;

function expandTogglePlus(e) {
    var element = $(e).children('.collapse_toggle').eq(0);
    if(element.hasClass('fa-plus')){
        element.removeClass('fa-plus');
        element.addClass('fa-minus');
    }
    else
    {
        element.removeClass('fa-minus');
        element.addClass('fa-plus');
    }
}
function createJSONTree(JSONData, selector) {
    console.log(JSONData)
    content = "<ul>";
    iterateOverKey(JSONData, 0, selector);
    content += "</ul>";
    $(selector).append(content);
}

function sanitizeKey(key) {
    return key.replace(' ', '').replace(/[^\w\s]/gi, '').replace(/ /g, '');
}

function prettyKeyValuePair(key, value) {
    var result = '<li class="json_oth">'
    result += '<span class="json_key text-success fw-bold">' + key + '</span>';
    result += '<span class="text-danger fw-bold">&nbsp;(' + typeof(value) +  ')&nbsp;</span>'
    result += '<span class="json_value fw-bold text-muted">"' + value + '"</span></li>';
    return result;
}

function generateCollapse(key, hierarchy) {
    var result = "";
    result += '<li><button class="json_object" data-bs-toggle="collapse" data-bs-target="#section_' + sanitizeKey(key) + '" aria-expanded="false" aria-controls="section_' + sanitizeKey(key) + '" onclick="expandTogglePlus(this)"><span class="collapse_toggle fas fa-plus"></span>&nbsp;' + key + '</button></li>'
    result += '<ul>'
    result += '<div class="json_tree collapse" id="section_' + sanitizeKey(key) + '" style="margin-left:' + hierarchy * pixelsPerHierarchy + 'px;">';
    return result;
}

function closeDiv() {
    content += '</ul>';
    content += '</div>'
}

function iterateOverKey(JSONData, hierarchy_level) {
    var i = 0;
    for (key in JSONData) {
        if (JSONData[key].constructor === Object) {
            content += generateCollapse(key, hierarchy_level + 1);
            iterateOverKey(JSONData[key], hierarchy_level + 1);
            closeDiv();
        }
        else {
            content += prettyKeyValuePair(key, JSONData[key]);
        }
        i += 1;
    }
}