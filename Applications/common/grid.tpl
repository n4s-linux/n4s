<style>
table {
    width: 100%;
    background-color: #343a40;
    color: #fff;
}
table,td, th {
    border: 1px solid #454d55;
}
td, th {
    padding: 10px;
}
th {    /* prevent selection of text in header row */
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

tr:nth-child(even) {
    background-color: rgba(255,255,255,.05);
}

.lock {
    float: right;
    width: 16px;
    height: 18px;
    float: right;
    fill: transparent;
}
.sort-primary .lock { fill: gray; }
.lock-locked { fill: white !important; }
.icon-highlite {
    background-color: white;

}

.sortable {
    position: relative;
    padding-right: 22px;
    cursor: pointer;
}
.sortable:before {
    top: 10px;
    transform: rotate(-135deg);
    -webkit-transform: rotate(-135deg);
}

.sortable:after {
    bottom: 10px;
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
}

.descending:after { border-color: white !important; }
.ascending:before { border-color: white !important; }

.sortable:after, .sortable:before {
    content: "";
        border: solid white;
            border-width: 0 2px 2px 0;
                display: inline-block;
                    padding: 3px;
                        /* margin-left: 10px; */
                            position: absolute;
                                right: 10px;
                                    border-color: gray;
                                    }

*, ::after, ::before {
    box-sizing: border-box;
    }
.download, .download:visited, .download:active {
    display: block; 
    float: right;
    border: 1px solid black;
    padding: 5px;
    background-color: #454d55;
    color: white !important;
    position: relative;
    top: 30px;
}
.download:hover {
    color: #bbb !important;
}
h2 {
    float: left;
}
input {
    color: black;
}
</style>

<h2>Kreditorliste</h2>
<a href="javascript: download();" class=download>Download as CSV</a>
<table class=grid>
    <tr>
        {foreach name=loop loop=$fieldNames}
        <th class="sortable" data-column="{$fieldNames[$loop.index]}">
            {$fieldLabels[$loop.index]}
                <div class="lock"> 
                    <svg class="closed-lock-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 320 320" style="enable-background:new 0 0 320 320;" xml:space="preserve">
                    <g>
                        <path d="M231.731,133.115V64.523C231.731,28.945,204.733,0,171.549,0h-23.098c-33.185,0-60.183,28.945-60.183,64.523v68.591
                            c-20.308,19.738-33.011,47.731-33.011,78.733C55.258,271.483,102.245,320,160,320s104.742-48.517,104.742-108.152
                            C264.742,180.846,252.04,152.853,231.731,133.115z M108.269,117.804v-53.28c0-24.551,18.025-44.523,40.183-44.523h23.098
                            c22.157,0,40.183,19.973,40.183,44.523v53.28c-2.73-1.601-5.528-3.063-8.38-4.402c-0.102-0.048-0.203-0.099-0.305-0.147
                            c-0.399-0.186-0.801-0.363-1.202-0.544c-0.596-0.269-1.195-0.534-1.796-0.792c-0.282-0.12-0.563-0.24-0.846-0.358
                            c-0.891-0.373-1.787-0.734-2.689-1.082c-0.02-0.008-0.04-0.016-0.059-0.023c-9.231-3.55-19.073-5.789-29.314-6.504
                            c-0.237-0.017-0.474-0.032-0.711-0.047c-0.82-0.052-1.643-0.093-2.468-0.125c-0.271-0.011-0.542-0.024-0.814-0.032
                            c-1.046-0.032-2.094-0.053-3.147-0.053s-2.101,0.021-3.147,0.053c-0.272,0.008-0.543,0.021-0.814,0.032
                            c-0.825,0.032-1.648,0.073-2.468,0.125c-0.237,0.015-0.474,0.03-0.711,0.047c-10.241,0.715-20.083,2.954-29.314,6.504
                            c-0.02,0.008-0.04,0.016-0.059,0.023c-0.903,0.348-1.799,0.709-2.689,1.082c-0.283,0.118-0.564,0.237-0.846,0.358
                            c-0.602,0.258-1.2,0.523-1.796,0.792c-0.401,0.18-0.803,0.358-1.202,0.544c-0.102,0.048-0.203,0.099-0.305,0.147
                            C113.797,114.74,110.998,116.203,108.269,117.804z M160,300c-46.727,0-84.742-39.545-84.742-88.152
                            c0-32.953,17.476-61.736,43.29-76.861c0.402-0.234,0.802-0.472,1.207-0.699c0.442-0.249,0.888-0.492,1.335-0.734
                            c0.784-0.422,1.572-0.836,2.368-1.232c0.252-0.126,0.507-0.247,0.761-0.37c8.831-4.279,18.342-6.968,28.119-7.892
                            c0.085-0.008,0.171-0.016,0.257-0.024c1.158-0.106,2.32-0.187,3.484-0.243c0.135-0.006,0.272-0.01,0.407-0.016
                            c1.169-0.051,2.341-0.082,3.515-0.082s2.346,0.031,3.515,0.082c0.136,0.006,0.272,0.01,0.407,0.016
                            c1.165,0.056,2.326,0.137,3.484,0.243c0.086,0.008,0.171,0.016,0.257,0.024c9.777,0.924,19.288,3.613,28.119,7.892
                            c0.254,0.123,0.509,0.244,0.761,0.37c0.796,0.396,1.583,0.81,2.368,1.232c0.447,0.241,0.893,0.484,1.335,0.734
                            c0.405,0.227,0.806,0.465,1.207,0.699c25.814,15.125,43.29,43.908,43.29,76.861C244.742,260.455,206.727,300,160,300z"></path>
                        
                        <path d="M189.504,195.563c0-16.273-13.235-29.513-29.505-29.513c-16.268,0-29.503,13.239-29.503,29.513
                            c0,8.318,3.452,16.06,9.343,21.557l-7.075,31.729c-0.159,0.715-0.239,1.444-0.239,2.177c0,8.667,8.488,15.202,19.744,15.202h15.467
                            c11.254,0,19.74-6.535,19.74-15.202c0-0.732-0.08-1.462-0.24-2.177l-7.076-31.729C186.051,211.622,189.504,203.881,189.504,195.563
                            z M153.84,246.227l6.159-27.622l6.161,27.622H153.84z M164.36,204.014c-1.944,1.01-3.443,2.591-4.361,4.455
                            c-0.918-1.864-2.417-3.445-4.361-4.455c-3.171-1.647-5.142-4.886-5.142-8.451c0-5.245,4.263-9.513,9.503-9.513
                            c5.241,0,9.505,4.268,9.505,9.513C169.504,199.127,167.533,202.365,164.36,204.014z"></path>
                    </g>
                    </svg>                
                </div>
            </div>
        </th>
        {/foreach}
    </tr>
    <tr>
        {foreach name=loop loop=$fieldNames}
        <td>
            <input type="text" data-column="{$fieldNames[$loop.index]}" placeholder="Filter {$fieldNames[$loop.index]}">
        </td>
        {/foreach}
    </tr>
    {foreach name=loop loop=$data}
    <tr class="data-row">
        {foreach name=loop2 loop=$data[$loop.index]}
            <td data-column="{$fieldNames[$loop2.index]}">
                {$data[$loop.index][$loop2.index]}
            </td>
        {/foreach}
    </tr>
    {/foreach}
</table>

<script>
// Sorting

function comparer(index) {
    return function(a, b) {
        if ($(".sort-primary").hasClass("ascending")) pdir = "ascending"; else pdir = "descending";
        primary = $(".sort-primary").data("column");

        if ($(".sort-secondary").hasClass("ascending")) sdir = "ascending"; else sdir = "descending";
        secondary = $(".sort-secondary").data("column");

        pvalA = getCellValue(a, primary);        pvalB = getCellValue(b, primary);        
       
        if (pvalA == pvalB)
        {
            if (typeof secondary === "undefined")
                return true;
            
            svalA = getCellValue(a, secondary);        svalB = getCellValue(b, secondary);        
        
            res = $.isNumeric(svalA) && $.isNumeric(svalB) ? svalA - svalB : svalA.toString().localeCompare(svalB)
         
            if (sdir == "ascending")
                return res;
            else 
                return res * -1;
        }
        else
        {
            res = $.isNumeric(pvalA) && $.isNumeric(pvalB) ? pvalA - pvalB : pvalA.toString().localeCompare(pvalB)
            if (pdir == "ascending")
                return res;
            else
                return res * -1;
        }
    }
}

function getCellValue(row, index)
{
        return $(row).find('td[data-column="'+index+'"').html().trim();

}
function geetCellValue(row, index)
{ 
    return $(row).children('td').eq(index).text();
}


$(document).ready(function(){
        $("input").on("keyup", function() {
                column = $(this).data("column");
                var value = $(this).val().toLowerCase();
                $(".data-row").filter(function() {
                        
                        $(this).toggle( $(this).find('td[data-column="'+column+'"').html().trim().indexOf(value) > -1);
                        //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                        });
                });
        });

$('th').click(function(){

    
    /* Handle selection of primary and secondary columns */
    if (!$(".sort-primary").length) // Ingen primær colonne valgt
    {
        $(this).addClass("sort-primary"); // Marker som valgt for primær
    } else if (!$(this).hasClass("sort-primary"))  {   // Primær colonne allerede valgt
        if ($(".lock-locked").length)   // Vi er ved at valge en sekundaer
        {
            $(".sort-secondary").removeClass("sort-secondary").removeClass("descending").removeClass("ascending");
            $(this).addClass("sort-secondary");
        }
        else                            // Vi er ved at ændre primære
        {
        $(".sort-primary").removeClass("sort-primary").removeClass("descending").removeClass("ascending"); // Af-marker fhv. primær kolonne
        $(this).addClass("sort-primary"); // Marker som valgt for primær
        }
    }


 //   if (typeof this.asc === 'undefined')    // initial order: ascending (will be flipped right away)
//      this.asc = !this.asc;
    
    /* Handle direction of selected colums */
    if (($(this).hasClass("sort-primary")) || ($(this).hasClass("sort-secondary"))) 
    {
        this.asc = !this.asc;
        if (!this.asc)
        {
            $(this).addClass("descending").removeClass("ascending");;
        } else {
            $(this).addClass("ascending").removeClass("descending");
        }
    }

    /* Doing the actual sorting */
    var table = $(this).parents('table').eq(0);
    var rows = table.find('tr:gt(1)').toArray().sort(comparer($(this).index()));
    for (var i = 0; i < rows.length; i++)
    {
        table.append(rows[i]);
    }
});

$('.lock').click(function(event){
    
    if ($(this).parent().hasClass("sort-primary"))
    {
        if ($(".lock").hasClass("lock-locked"))
        {
            $(this).removeClass("lock-locked");
        }
        else
        {
            $(this).addClass("lock-locked");
        }
        event.stopPropagation();
    }
});
// Searching

function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv;charset=UTF-8"});
    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

function download() {
    var csv = [];
    var rows = document.querySelectorAll("table .data-row");
    filename = "table.csv";
   
    var headers = [];
    $(".grid th").each(function(){
        headers.push($(this).data("column"));
    });
    csv.push(headers);

    $(".data-row").filter(':visible').each(function(){
        var row = [];
        $(this).find("td").each(function(){
            row.push($(this).html().trim());
        });  
        csv.push(row);
    });
    downloadCSV(csv.join("\n"), filename);
}


</script>
