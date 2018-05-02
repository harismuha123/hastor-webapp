$(document).ready(function(){

    var TodayDate = new Date();
    var m  = TodayDate.getMonth() + 1;
    var y = TodayDate.getFullYear();
    var monthString = "";

    switch (m) {
        case 1:
            monthString = "januar";
            break;
        case 2:
            monthString = "februar";
            break;
        case 3:
            monthString = "mart";
            break;
        case 4:
            monthString = "april";
            break;
        case 5:
            monthString = "maj";
            break;
        case 6:
            monthString = "juni";
            break;
        case 7:
            monthString = "juli";
            break;
        case 8:
            monthString = "august";
            break;
        case 9:
            monthString = "septembar";
            break;
        case 10:
            monthString = "oktobar";
            break;
        case 11:
            monthString = "novembar";
            break;
        case 12:
            monthString = "decembar";
            break;
    }

    $("#task-title").text("Zadaci za mjesec " + monthString + ", " + y + ".");

    demo.initChartist();

    $.notify({
        icon: 'pe-7s-id',
        message: "Dobrodošli u <b>Administracioni Sistem Fondacije Hastor</b>."

    },{
        type: 'info',
        timer: 3000
    });
});

