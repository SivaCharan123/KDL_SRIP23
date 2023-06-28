/*
    Generates 17 SDG checkboxes to make it easier and quicker!
*/
function generate_checkboxes(checkbox_element, checkbox_array)
{
    var i = 0;
    var items = [];

    SDG_STRINGS = ['No Poverty', 'Zero Hunger', 'Good Health & Well Being', 'Quality Education', 'Gender Equality', 'Clean Water & Sanitation', 'Affordable Energy', 'Economic Growth & Decent Work', 'Industry and Infrastructure', 'Inequality Reduction', 'Sustainable Cities', 'Responsible Consumption', 'Climate Action', 'Life Below Water', 'Life on Land', 'Strong Institutions & Justice', 'Partnerships']
    for(i = 1; i < 18; i++)
    {
        var sdg_html = `<div class=\"form-check\">\n<input class=\"form-check-input\" type=\"checkbox\" value=\"` + i.toString() + `\" name=\"` + checkbox_array + `[]\" id="` + i.toString() + `">
        <label class="form-check-label" for="` + checkbox_array + `[]">` +
            `SDG ` + i.toString() + ": " + SDG_STRINGS[i-1] + 
        `</label>
        </div>`;
        items.push(sdg_html);
    }

    $('.' + checkbox_element).append(items.join('\n'));
}
