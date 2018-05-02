$(document).ready(function () {
    $.get("rest/front_page", function (data) {
        var html = "";
        for(var i = 0; i < data.length; i++) {
            if(i !== 0 && i % 3 === 0) {
                html += "</div>\n" +
                    "    <div class=\"clearfix visible-md-block\"></div>" +
                    "    <div class='row'></div>";
            }
            html += "<div class=\"col-md-4\">\n" +
                "        <div class=\"card card-price\">\n" +
                "            <div class=\"image\">\n" +
                "                <img src='"+ data[i].image + "' alt=\"...\"/>\n" +
                "            </div>\n" +
                "            <div class=\"content\">\n" +
                "                 <h5 class=\"title\"><a href='"+ data[i].about +"' target='_blank'>" + data[i].title +"</h5>\n" +
                "            </div>\n" +
                "        </div>\n" +
                "    </div>";
        }
        $(".homepage").html(html);
    })
});