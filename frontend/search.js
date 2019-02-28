// Global variables
var searchKeyword = true;
var searchTopic = false;
var searchDifficulty = false;
var numCases = 2;
var draftExam = [];
var publishedExam = [];

// For searching through the question bank
function query() {
    var xhttp = new XMLHttpRequest();
    
    // Checks which filters are on
    var request = "query=true";
    if(searchKeyword) {
        request = "&keyword=" + document.getElementById("s").value;
    }
    if(searchTopic) {
        request = "&topic=" + document.getElementById("s").value;
    }
    if(searchDifficulty) {
        request = "&difficulty=" + document.getElementById("s").value;
    }

    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var JSONresults = this.responseText;
            var searchResults = JSON.parse(JSONresults);
            document.getElementById("qResults").innerHTML = "";
            for(q = 0; q < searchResults.length; q++) {
                var questionName = searchResults[q].Question;
                var questionTopic = searchResults[q].Topic;
                var questionDifficulty = searchResults[q].Difficulty;
                var questionID = searchResults[q].ID;
                document.getElementById("qResults").innerHTML += "<tr id=" + questionID + "><th>" + questionName + "</th> <th>" + questionTopic + "</th> <th>" + questionDifficulty + "</th><th><input type='text' style='visibility: hidden; width: 2rem;' id=" + questionID + "></th></tr>";
            }
        }
    }
    xhttp.open("POST","frontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(request);
}

// Functions for adjusting search filters
function setKeyword() {
    searchKeyword = !searchKeyword;
}
function setTopic() {
    searchTopic = !searchTopic;
}
function setDifficulty() {
    searchDifficulty = !searchDifficulty;
}

// Adding a question to the question bank
function addQuestion() {
    if(document.getElementById("add").innerHTML == "Add Another Question") {
        document.getElementById("add").innerHTML = "Add Question";
        document.getElementById("q").value = "";
        document.getElementById("ans1").value = "";
        document.getElementById("ans2").value = "";
        document.getElementById("tc1").value = "";
        document.getElementById("tc2").value = "";
        document.getElementById("top").value = "";
    }
    else {
        var question = document.getElementById("q").value;
        var difficulty = document.getElementById("diff").value;
        var topic = document.getElementById("top").value;
        var testCases = [];
        for(i = 1; i < numCases+1; i++) {
            testCases.push("{" + document.getElementById("tc" + i).value + ":" + document.getElementById("ans" + i).value + "}");    
        }

        // If any fields are empty
        if(question == null || question == "" || difficulty == null || difficulty == "" || topic == null || topic == "" || testCases == null || testCases == []) {
            document.getElementById("sysMsg").innerHTML = "All Fields are Required."
        }
        else {
            var xhttp = new XMLHttpRequest();
            var addRequest = "question=" + question + "&difficulty=" + difficulty + "&topic=" + topic + "&testCases=" + JSON.stringify(testCases);
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    var response = this.responseText;
                    if(response) {
                        document.getElementById("add").innerHTML = "Add Another Question";
                        document.getElementById("sysMsg").innerHTML = "Question added! Send another?"
                    }
                    else {
                        document.getElementById("sysMsg").innerHTML = "Error sending message."
                    }
                }
            }
            xhttp.open("POST","frontCurl.php", true);
            xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            xhttp.send(addRequest);
        }
    }
}

// Adding more Test Cases
function addTC() {
    if(numCases<6) {
        var nextNum = numCases + 1;
        var nodeIn = document.createElement("th");
        var textIn = document.createTextNode("<input type='text' name='test" + nextNum + "' id='tc" + nextNum + "'>");
        nodeIn.appendChild(textIn);
        nodeIn.id = "in" + nextNum;
        var tableIn = document.getElementById("input");
        var addBut = document.getElementById("addButton");
        tableIn.insertBefore(nodeIn, addBut);

        var nodeOut = document.createElement("th");
        var textOut = document.createTextNode("<input type='text' name='result" + nextNum + "' id='ans" + nextNum + "'>");
        nodeOut.appendChild(textOut);
        nodeOut.id = "out" + nextNum;
        var tableOut = document.getElementById("output");
        var delBut = document.getElementById("delButton");
        tableOut.insertBefore(nodeOut, delBut);
        numCases = nextNum;
    }
}

// Deleting Current Fields
function delTC() {
    if(numCases>2) {
        var nextNum = numCases - 1;
        var tableIn = document.getElementById("input");
        var elementIn = document.getElementById("in" + numCases);
        tableIn.removeChild(elementIn);

        var tableOut = document.getElementById("output");
        var elementOut = document.getElementById("out" + numCases);
        tableOut.removeChild(elementOut);
        numCases = nextNum;
    }
}

// Toggle Exam Making Mode
function examOn() {
    var table = document.getElementById("queryResults");
    document.getElementById("examEdit").innerHTML = "Cancel Exam Creating";
    document.getElementById("examEdit").onclick = examOff();
    document.getElementById("makeExam").style.visibility = visible;
    for(k = 1; k < table.getElementsByTagName("tr").length; k++) {
        table.getElementsByTagName("tr")[k].addEventListener("click", addToExam);
    }
}
function examOff() {
    var table = document.getElementById("queryResults");
    document.getElementById("examEdit").innerHTML = "Make Exam";
    document.getElementById("examEdit").onclick = examOn();
    for(m = 1; m < table.getElementsByTagName("tr").length; m++) {
        table.getElementsByTagName("tr")[m].removeEventListener("click", addToExam);
    }
}

function addToExam(element) {
    
}

// Loading the exam for students
function loadExam() {
    if(exam.length == 0) {
        document.getElementById("fullExam").innerHTML = "Exam not posted yet. Try again later."
    }
    else {
        for(n = 0; n < publishedExam.length; n++) {

        }
    }
}