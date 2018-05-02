$(document).ready(function () {
    $.get("rest/front_page", function (data) {
        var html = "";
        for(var i = 0; i < data.length; i++) {
            html += "<div class=\"col-md-4\">\n" +
                "        <div class=\"card card-price\">\n" +
                "            <div class=\"image\">\n" +
                "                <img src='"+ data[i].image + "' alt=\"...\"/>\n" +
                "            </div>\n" +
                "            <div class=\"content\">\n" +
                "                 <h5 class=\"title\"><a href='"+ data[i].about +"' target='_blank'>" + data[i].title +"</h5>\n" +
                "            </div>\n" +
                "        </div>\n" +
                "    </div>"
        }
        $(".homepage").html(html);
    })
});