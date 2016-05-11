$(document).ready(function(){
        
    var numOfFields = $(".descriptionField").size();
    var oldDescVals = [];
    var oldPercentsVals = [];
    
    if(numOfFields > 0){
        var oldDesc = $(".descriptionField");
        var oldPercents = $(".thresholdField");
        
        for(var i = 0; i < oldDesc.size(); i++){
            oldDescVals[i] = oldDesc[i].value;
            oldPercentsVals[i] = oldPercents[i].value;
        }
    }
    
    $("#saveParkingLotsButton").click(function(){
        var newDesc = $(".descriptionField");
        var newPercents = $(".thresholdField");
        var newIDs = $(".parkingSpotID");
        var newDescVals = [], newPercentsVals = [], newIDsVals = [];
        
        for(var i = 0; i < numOfFields; i++){
            if(newDesc[i].value != oldDescVals[i] || newPercents[i].value != oldPercentsVals[i]){
                newDescVals[i] = newDesc[i].value;
                newPercentsVals[i] = newPercents[i].value/100;
                newIDsVals[i] = newIDs[i].value;
            }
        }
        
        if(newDescVals.length > 0){
            saveChanges(newIDsVals, newDescVals, newPercentsVals);
        } 
    });
    
    function saveChanges(ids, descs, percentages) {
        $.post( "spots/saveSpots.php", { parkingLotID: window.location.href.match(/=(.*)/)[1], ids: JSON.stringify(ids), descs: JSON.stringify(descs), percentages: JSON.stringify(percentages)}, function() {
            location.reload();
        });
    }
    
    $(".DeleteLotForm").submit(function () {
        var confirmDelete = confirm("Are you sure you want to delete this parking lot?");
        if(confirmDelete != true){
            event.preventDefault();
        }
    });
    
    if($("#weeklyChart").length > 0){
        setUpCharts();
    }

    function setUpCharts() {
        //Weekly Chart
        
        var ctxWeekly = $("#weeklyChart").get(0).getContext("2d");
        
        var dataWeekly = {
            labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(0,155,255,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(100,100,100,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [$(".weeklyUsageLevel")[0].value, $(".weeklyUsageLevel")[1].value, $(".weeklyUsageLevel")[2].value, $(".weeklyUsageLevel")[3].innerHTML, $(".weeklyUsageLevel")[4].value, $(".weeklyUsageLevel")[5].value, $(".weeklyUsageLevel")[6].value]
                }
            ]
        };
        
        var weeklyChart = new Chart(ctxWeekly).Line(dataWeekly, {
            scaleOverride : true,
            scaleSteps : 20,
            scaleStepWidth : .05,
            scaleStartValue : 0
        });
        
        
        //Hourly Chart
        
        var ctxHourly = $("#hourlyChart").get(0).getContext("2d");
        
        dataSet = []
        for(var i = 0; i < 24; i++) {
            dataSet[i] = $(".hourlyUsageLevel")[i].value;
        }
        
        var dataHourly = {
            labels: ["00h", "01h", "02h", "03h", "04h", "05h", "06h", "07h", "08h", "09h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h", "19h", "20h", "21h", "22h", "23h"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(0,155,255,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(100,100,100,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: dataSet
                }
            ]
        };
        
        var hourlyChart = new Chart(ctxHourly).Line(dataHourly, {
            scaleOverride : true,
            scaleSteps : 20,
            scaleStepWidth : .05,
            scaleStartValue : 0
        });

    }
});