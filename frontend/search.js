<<<<<<< HEAD
// Global variables
var numCases = 2;
var publishedExam = [];

// For searching through the question bank
function query() {
    document.getElementById("version").innerHTML = "Version 1.0.5";

    var xhttp = new XMLHttpRequest();
    
    // Checks which filters are on
    var request = "query=GetQuestions&question=" + document.getElementById("s").value + "&topic=" + document.getElementById("t").value + "&difficulty=" + document.getElementById("d").value;
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var JSONresults = this.responseText;
            var resultsObj = JSON.parse(JSONresults);
            var searchResults = resultsObj.Questions;
            var queryTable = document.getElementById("qResults");

            // Clears the table to receive the other questions
            if(queryTable.childNodes.length > 1) {
                for(del = 1; del < queryTable.childNodes.length; del++) {
                    queryTable.removeChild(queryTable.childNodes[del]);
                }
            }

            for(q = 0; q < searchResults.length-1; q++) {
                var questionName = searchResults[q].Question;
                var questionTopic = searchResults[q].Topic;
                var questionDifficulty = searchResults[q].Difficulty;
                var questionID = searchResults[q].ID;
                /*var questionTC = searchResults[q].TestCases;
                var end = questionName + questionTopic + questionDifficulty + questionID;
                for(p = 0; p < questionTC.length; p++) {
                    end+=questionTC[p];
                }*/
                //document.getElementById("testWork").innerHTML = end;
                var row = document.createElement("TR");
                row.id = questionID;

                var rName = document.createElement("TH");
                var rNameContent = document.createTextNode(questionName);
                rName.appendChild(rNameContent);
                row.appendChild(rName);

                var rTopic = document.createElement("TH");
                var rTopicContent = document.createTextNode(questionTopic);
                rTopic.appendChild(rTopicContent);
                row.appendChild(rTopic);

                var rDifficulty = document.createElement("TH");
                var rDifficultyContent = document.createTextNode(questionDifficulty);
                rDifficulty.appendChild(rDifficultyContent);
                row.appendChild(rDifficulty);

                var rPoints = document.createElement("TH");
                rPoints.style.display = "none";
                var rPointsBox = document.createElement("INPUT");
                rPointsBox.type = "text";
                rPointsBox.style.visibility = "hidden";
                rPointsBox.style.width = "2rem";

                rPoints.appendChild(rPointsBox);
                row.appendChild(rPoints);
                queryTable.appendChild(row);
            }
        }
        else {
            document.getElementById("testWork").innerHTML = this.readyState + " " + this.status;
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(request);
}

// Adding a question to the question bank
function addQuestion() {
    
    // Resets the form!
    if(document.getElementById("add").innerHTML == "Add Another Question") {
        document.getElementById("add").innerHTML = "Add Question";
        document.getElementById("sysMsg").innerHTML = "";
        document.getElementById("functionName").innerHTML = "";
        document.getElementById("q").value = "";
        document.getElementById("ans1").value = "";
        document.getElementById("ans2").value = "";
        document.getElementById("tc1").value = "";
        document.getElementById("tc2").value = ""; 
        document.getElementById("top").value = "";
        while(numCases > 2) {
            delTC();
        }
    }

    // Sending the question over
    else {
        var question = document.getElementById("q").value;
        var difficulty = document.getElementById("diff").value;
        var topic = document.getElementById("top").value;
        var fName = document.getElementById("functionName").value;
        var testCases = [];
        for(i = 1; i < numCases+1; i++) {
            testCases.push("{input" + i + ":" + document.getElementById("tc" + i).value + "}");
            testCases.push("{output" + i + ":" + document.getElementById("ans" + i).value + "}");    
        }

        // If any fields are empty
        if(question == null || question == "" || difficulty == null || difficulty == "" || topic == null || topic == "" || testCases == null || testCases == [] || fName == null || fName == "") {
            document.getElementById("sysMsg").innerHTML = "All Fields are Required."
        }
        else {
            var xhttp = new XMLHttpRequest();
            var addRequest = "query=InsertQuestion&Question=" + question + "&FunctionName=" + fName + "&Difficulty=" + difficulty + "&Topic=" + topic + "&TestCases=" + JSON.stringify(testCases);
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    var echoed = this.responseText;
                    var response = JSON.parse(echoed);
                    if(response.dbSuccess) {
                        document.getElementById("add").innerHTML = "Add Another Question";
                        document.getElementById("sysMsg").innerHTML = "Question added! Send another?"
                        query();
                    }
                }
                else {
                    document.getElementById("sysMsg").innerHTML = "Error sending message."
                }
            }
            xhttp.open("POST","betaFrontCurl.php", true);
            xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
            xhttp.send(addRequest);
        }
    }
}

// Adding more Test Case Fields
function addTC() {
    if(numCases<6) {
        numCases++;
        var nodeIn = document.createElement("TH");
        var boxIn = document.createElement("INPUT");
        boxIn.type = "text";
        boxIn.name = "test" + numCases;
        boxIn.id = "tc" + numCases;
        nodeIn.appendChild(boxIn);
        nodeIn.id = "in" + numCases;
        var tableIn = document.getElementById("input");
        var addField = document.getElementById("addButton");
        tableIn.insertBefore(nodeIn, addField);

        var nodeOut = document.createElement("TH");
        var boxOut = document.createElement("INPUT");
        boxOut.type = "text";
        boxOut.name = "result" + numCases;
        boxOut.id = "ans" + numCases;
        nodeOut.appendChild(boxOut);
        nodeOut.id = "out" + numCases;
        var tableOut = document.getElementById("output");
        var delField = document.getElementById("delButton");
        tableOut.insertBefore(nodeOut, delField);
    }
}

// Deleting Current Fields
function delTC() {
    if(numCases>2) {
        var tableIn = document.getElementById("input");
        var elementIn = document.getElementById("in" + numCases);
        tableIn.removeChild(elementIn);

        var tableOut = document.getElementById("output");
        var elementOut = document.getElementById("out" + numCases);
        tableOut.removeChild(elementOut);
        numCases--;
    }
}

// Toggle Exam Making Mode
function examOn() {
    var table = document.getElementById("queryResults");
    document.getElementById("examEdit").innerHTML = "Cancel Exam Creating";
    document.getElementById("examEdit").onclick = examOff();
    document.getElementById("makeExam").style.visibility = "visible";
    document.getElementById("testWork").innerHTML = "";
    var queryList = table.getElementsByTagName("tr");
    for(k = 1; k < queryList.length; k++) {
        queryList[k].childNodes[3].style.visibility = "visible";
        queryList[k].addEventListener("click", function(){this.classList.toggle("selected")} );
    }
}
function examOff() {
    var table = document.getElementById("queryResults");
    document.getElementById("examEdit").innerHTML = "Make Exam";
    document.getElementById("examEdit").onclick = examOn();
    document.getElementById("makeExam").style.visibility = "hidden";
    var queryList = table.getElementsByTagName("tr");
    for(m = 1; m < queryList.length; m++) {
        queryList[m].removeEventListener("click", function(){this.classList.toggle("selected")});
        queryList[m].childNodes[3].value = "";
        queryList[m].childNodes[3].style.visibility = "hidden";
    }
    for(p = 0; p < table.getElementByClassName("selected").length; p++) {
        table.getElementByClassName("selected")[p].classList.toggle("selected");
    }
}

// Adding Questions to the Exam and Posting It
function addToExam() {
    var table = document.getElementById("queryResults");
    var examQuestions = table.getElementByClassName("selected");
    for(s = 0; s < examQuestions.length; s++) {
        if(examQuestions[s].childNodes[3].value < 1) {
            document.getElementById("testWork").innerHTML = "Points Must Be Given to Each Selected Question.";
            break;
        }
        var xhttp = new XMLHttpRequest();
        var addRequest = "query=InsertExamQuestion&QuestionID=" + examQuestions[s].id + "&Points=" + examQuestions[s].childNodes[3].value;
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                var echoed = this.responseText;
                var response = JSON.parse(echoed);
                if(response.dbSuccess) {
                    document.getElementById("testWork").innerHTML = "Exam Posted.";
                }
                else {
                    document.getElementById("testWork").innerHTML = "Question Already in Exam.";
                }
            }
            else {
                document.getElementById("testWork").innerHTML = "Error Making Exam.";
            }
        }
        xhttp.open("POST","betaFrontCurl.php", true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(addRequest);
        if(s == examQuestions.length-1) {
            examOff();
        }
    }
}

// Loading the exam for students
function loadExam() {
    var xhttp = new XMLHttpRequest();
    var exam = [];

    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var examJSON = this.responseText;
            var examResults = JSON.parse(examJSON);
            for(h = 0; h < examResults.length; h++) {
                exam.push(examResults[h]);
            }
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp,setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send("query=GetExam");
    if(exam.length == 0) {
        document.getElementById("fullExam").innerHTML = "Exam not posted yet. Try again later."
    }
    else {
        document.getElementById("studentSubmit").style.visibility = "visible";
        document.getElementById("subMsg").style.visibility = "visible";
        var page = document.getElementById("fullExam");
        for(n = 0; n < exam.length; n++) {
            var curNode = document.createElement("P");
            var curText = document.createTextNode(exam[n].Question);
            curNode.appendChild(curText);
            page.appendChild(curNode);

            var ansNode = document.createElement("TEXTAREA");
            ansNode.rows = "20";
            ansNode.cols = "100";
            ansNode.id = exam[n].ID;
            ansNode.onkeydown = "insertTab(this,event)";
            page.appendChild(ansNode);
        }
    }
}

// For submitting the exam
function submitExam() {
    var ansList = document.getElementsByTagName("textarea");
    var studentAnswers = [];
    for(se = 0; se < ansList.length; se++) {
        studentAnswers.push("{qID" + se + ":" + ansList[se].id + "}");
        studentAnswers.push("{answer" + se + ":" + ansList[se].value + "}");
    }
    var xhttp = new XMLHttpRequest();
    var submitRequest = "query=UpdateExamQuestion&answers=" + JSON.stringify(studentAnswers);
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var sEchoJSON = this.responseText;
            var sEcho = JSON.parse(sEchoJSON);
            if(sEcho.dbSuccess) {
                document.getElementById("system").innerHTML = "Answers Submitted! Grade will posted Later.";
            }
            else {
                document.getElementById("system").innerHTML = "dbSuccess is false?"
            }
        }
        else {
            document.getElementById("erroring").innerHTML = this.readyState + " " + this.status;
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(submitRequest);
}
======= // EVERYTHING BELOW IS OLD
// Global variables
var searchKeyword = false;
var searchTopic = false;
var searchDifficulty = false;
var numCases = 2;
var questionBank = [];
var publishedExam = [];
var request;

// For searching through the question bank
function query() {
    var xhttp = new XMLHttpRequest();
    
    // Checks which filters are on
    request = "query=true";
    if(searchKeyword) {
        request = request + "&keyword=" + document.getElementById("s").value;
    }
    if(searchTopic) {
        request = request + "&topic=" + document.getElementById("s").value;
    }
    if(searchDifficulty) {
        request = request + "&difficulty=" + document.getElementById("s").value;
    }

    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var JSONresults = this.responseText;
            var resultsObj = JSON.parse(JSONresults);
            var searchResults = resultsObj.Questions;
            document.getElementById("qResults").innerHTML = "";
            for(q = 0; q < searchResults.length-1; q++) {
                /*if(!questionCache.includes(searchResults[q])) {
                    questionCache.push(searchResults[q]);
                }*/
                var questionName = searchResults[q].Question;
                var questionTopic = searchResults[q].Topic;
                var questionDifficulty = searchResults[q].Difficulty;
                var questionID = searchResults[q].ID;
                var questionTC = searchResults[q].TestCases;
                var end = questionName + questionTopic + questionDifficulty + questionID;
                for(p = 0; p < questionTC.length; p++) {
                    end+=questionTC[p];
                }
                //document.getElementById("testWork").innerHTML = end;
                document.getElementById("qResults").innerHTML += "<tr id=" + questionID + "><th>" + questionName + "</th> <th>" + questionTopic + "</th> <th>" + questionDifficulty + "</th><th><input type='text' style='visibility: hidden; width: 2rem;' id=" + questionID + "></th></tr>";
            }
        }
        else {
            document.getElementById("testPhp").innerHTML = this.readyState + " " +this.status;
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    document.getElementById("testPhp").innerHTML = "Hello";
    document.getElementById("testJs").innerHTML = request;
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
            xhttp.open("POST","betaFrontCurl.php", true);
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
        table.getElementsByTagName("tr")[k].getElementById("points")
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
            var curNode = document.createElement("p");
        }
    }
}
>>>>>>> 7291f96817ca6486586f7bccea9b73322e2f8041
