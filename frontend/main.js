// Global variables (starting positions)
var examEditingOn = false;
var numCases = 2;

// For searching through the question bank
function query() {
    //document.getElementById("version").innerHTML = "testing for cache";

    var xhttp = new XMLHttpRequest();
    var qRequest = "query=GetQuestions";
    var kw = document.getElementById("s").value;
    var tpc = document.getElementById("t").value;
    var dif = document.getElementById("d").value;

    if(kw !== "") {
        qRequest += "&Question=" + kw;
    }
    if(tpc !== "") {
        qRequest += "&Topic=" + tpc;
    }
    if(dif !== "") {
        qRequest += "&Difficulty=" + dif;
    }
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var JSONresults = this.responseText;
            var resultsObj = JSON.parse(JSONresults);
            var searchResults = resultsObj.Questions;
            var queryTable = document.getElementById("qResults");
            document.getElementById("testWork").innerHTML = "";

            // Clears the table to receive the other questions
            while(queryTable.children.length > 0) {
                queryTable.removeChild(queryTable.firstChild);
            }

            for(q = 0; q < searchResults.length-1; q++) {
                
                // All Info
                var questionName = searchResults[q].Question;
                var questionTopic = searchResults[q].Topic;
                var questionDifficulty = searchResults[q].Difficulty;
                var questionID = searchResults[q].ID;
                var fName = searchResults[q].FunctionName;
                var questionTC = searchResults[q].TestCases;
                var questionNoNo = searchResults[q].Constraints;
                console.log(questionTC);
                console.log(questionNoNo);

                // Row
                var row = document.createElement("TR");
                row.id = questionID;

                // ID Column
                var rID = document.createElement("TD");
                var rIDContent = document.createTextNode(questionID);
                rID.appendChild(rIDContent);
                row.appendChild(rID);

                // Question Column
                var rName = document.createElement("TD");
                var rNameCollapse = document.createElement("BUTTON");
                rNameCollapse.className = "collapse";
                var rNameLabel = document.createTextNode(fName);
                rNameCollapse.appendChild(rNameLabel);
                rName.appendChild(rNameCollapse);

                // Full Question Info in Question Column
                var rNameContainer = document.createElement("DIV");
                rNameContainer.className = "content";
                var textSection = document.createElement("P");
                var rNameContent = document.createTextNode(questionName);
                textSection.appendChild(rNameContent);
                rNameContainer.appendChild(textSection);  

                var inSection = document.createElement("DIV");
                var inString = "Input: " + questionTC.Input;
                var inText = document.createTextNode(inString);
                inSection.appendChild(inText);
                rNameContainer.appendChild(inSection);

                var outSection = document.createElement("DIV");
                var outString = "Output: " + questionTC.Output;
                var outText = document.createTextNode(outString);
                outSection.appendChild(outText);
                rNameContainer.appendChild(outSection);

                var constraintSection = document.createElement("DIV");
                var constraintString = "";
                if(questionNoNo.forLoop) {
                    constraintString += "For Loop";
                }
                if(questionNoNo.print) {
                    if(constraintString.length > 0) {
                        constraintString += ", ";
                    }
                    constraintString += "Print Statement";
                }
                if(questionNoNo.while) {
                    if(constraintString.length > 0) {
                        constraintString += ", ";
                    }
                    constraintString += "While Loop";
                }  
                var constraintText = document.createTextNode("Must Use: " + constraintString);
                constraintSection.appendChild(constraintText);
                rNameContainer.appendChild(constraintSection);

                rName.appendChild(rNameContainer);
                row.appendChild(rName);

                // Topic Column
                var rTopic = document.createElement("TD");
                var rTopicContent = document.createTextNode(questionTopic);
                rTopic.appendChild(rTopicContent);
                row.appendChild(rTopic);

                // Difficulty Column
                var rDifficulty = document.createElement("TD");
                var rDifficultyContent = document.createTextNode(questionDifficulty);
                rDifficulty.appendChild(rDifficultyContent);
                row.appendChild(rDifficulty);

                // Points Column
                var rPoints = document.createElement("TD");
                var rPointsBox = document.createElement("INPUT");
                rPointsBox.type = "text";
                rPointsBox.style.width = "1rem";
                rPoints.appendChild(rPointsBox);
                rPoints.style.display = "none";
                row.appendChild(rPoints);

                // Delete Button
                var delQ = document.createElement("TD");
                var delQBut = document.createElement("BUTTON");
                var delQSymbol = document.createTextNode("X");
                delQBut.appendChild(delQSymbol);
                delQBut.className = "rm";
                delQBut.setAttribute("onClick", "delQuestion()");
                delQ.appendChild(delQBut);
                row.appendChild(delQ); 

                queryTable.appendChild(row);
            }
            var coll = document.getElementsByClassName("collapse");
            for(let bar of coll) {
                bar.addEventListener("click", function() {
                    this.classList.toggle("open");
                    var content = this.nextElementSibling;
                    if(content.style.maxHeight) {
                        content.style.maxHeight = null;
                    } else {
                        content.style.maxHeight = content.scrollHeight + "px";
                    }
                });
            }
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(qRequest);
}

function teachExamView(mode) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        console.log(this.readyState + " " + this.status);
        if(this.readyState == 4 && this.status == 200) {
            var strResponse = this.responseText;
            var examJS = JSON.parse(strResponse);
            var exArray = examJS.Exam;
            var questArray = examJS.Questions;
            if(mode == "editing") {
                var editTable = document.getElementById("examQuestions");
                while(editTable.children.length > 0) {
                    editTable.removeChild(editTable.firstChild);
                }
                for(qNum = 0; qNum < exArray.length-1; qNum++) {
                    var curRow = document.createElement("TR");
                    curRow.id = exArray[qNum].QuestionID;
                    
                    var curID = document.createElement("TD");
                    var IDText = document.createTextNode(exArray[qNum].QuestionID);
                    curID.appendChild(IDText);
                    curRow.appendChild(curID);

                    var curQuest = document.createElement("TD");
                    var curQuestBut = document.createElement("BUTTON");
                    curQuestBut.className = "collapse";
                    curQuestBut.addEventListener("click",function() {
                        this.classList.toggle("open");
                        var content = this.nextElementSibling;
                        if(content.style.maxHeight) {
                            content.style.maxHeight = null;
                        } else {
                            content.style.maxHeight = content.scrollHeight + "px";
                        }
                    });
                    var questLabel = document.createTextNode(questArray[qNum].FunctionName);
                    curQuestBut.appendChild(questLabel);
                    curQuest.appendChild(curQuestBut);
                    var questContainer = document.createElement("DIV");
                    questContainer.className = "content";
                    var questText = document.createTextNode(questArray[qNum].Question);
                    questContainer.appendChild(questText);
                    curQuest.appendChild(questContainer);
                    curRow.appendChild(curQuest);

                    var curPoints = document.createElement("TD");
                    var pointsText = document.createElement("INPUT");
                    pointsText.type = "text";
                    pointsText.value = exArray[qNum].Points;
                    curPoints.appendChild(pointsText);
                    curRow.appendChild(curPoints);

                    var delEQ = document.createElement("TD");
                    var delEQButton = document.createElement("BUTTON");
                    var delEQSymbol = document.createTextNode("X")
                    delEQButton.appendChild(delEQSymbol);
                    delEQButton.className = "rm";
                    delEQButton.setAttribute("onClick","rmFromExam(" + exArray[qNum].QuestionID + ")");
                    delEQ.appendChild(delEQButton);
                    curRow.appendChild(delEQ);

                    editTable.appendChild(curRow);
                }
            }
            else if(mode == "grading") {
                if(exArray[0].AutoComments !== "") {
                    var examView = document.getElementById("examStatus");
                    examView.innerHTML = "";
                    while(examView.children.length > 0) {
                        examView.removeChild(examView.firstChild);
                    }

                    for(eqNum = 0; eqNum < exArray.length-1; eqNum++) {
                        var curQ = document.createElement("P");
                        var curQText = document.createTextNode("Question " + (eqNum + 1));s
                        curQ.appendChild(curQText);
                        examView.appendChild(curQ);

                        var curAns = document.createElement("P");
                        curAns.style.border = "0.1rem solid black";
                        var curAnsText;
                        if(exArray[eqNum].Answer) {
                            curAnsText = document.createTextNode(exArray[eqNum].Answer);
                        }
                        else {
                            curAnsText = document.createTextNode("No answer given.");
                        }
                        curAns.appendChild(curAnsText);
                        examView.appendChild(curAns);

                        var curPts = document.createElement("INPUT");
                        curPts.type = "text";
                        curPts.style.width = "1.5rem";
                        curPts.value = exArray[eqNum].PointsGiven;
                        curPts.id = "newPoints" + eqNum;
                        examView.appendChild(curPts);

                        var possPts = document.createElement("P");
                        possPts.style.display = "inline-block";
                        var possPtsText = document.createTextNode(" / " + exArray[eqNum].Points);
                        possPts.appendChild(possPtsText);
                        examView.appendChild(possPts);

                        var autoComms = document.createElement("DIV");
                        var autoCommsText = document.createTextNode(exArray[eqNum].AutoComments);
                        autoComms.appendChild(autoCommsText);
                        examView.appendChild(autoComms);

                        var teachFB = document.createElement("TEXTAREA");
                        teachFB.placeholder = "Any additional feedback";
                        teachFB.cols = "30";
                        teachFB.rows = "5";
                        teachFB.id = "tComments" + eqNum;
                        examView.appendChild(teachFB);
                    }
                }
            }
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send("query=GetExam");
}

// Changing tabs
function changePage(e, pageName) {
    var tabContent, tabLinks;
    tabContent = document.getElementsByClassName("tabcontent");
    for(let curTab of tabContent) {
        curTab.style.display = "none";
    }
    tabLinks = document.getElementsByClassName("tablinks");
    for(let curLink of tabLinks) {
        curLink.className = curLink.className.replace(" active", "");
    }
    if(pageName == "exEdit") {
        teachExamView("editing");
    }
    else if(pageName == "gradeExam") {
        teachExamView("grading");
    }
    document.getElementById(pageName).style.display = "block";
    e.currentTarget.className += " active";
}

// Adding a question to the question bank
function addQuestion() {
    
    // Resets the form!
    if(document.getElementById("add").innerHTML == "Add Another Question") {
        document.getElementById("add").innerHTML = "Add Question";
        document.getElementById("sysMsg").innerHTML = "";
        document.getElementById("functionName").value = "";
        document.getElementById("q").value = "";
        document.getElementById("ans1").value = "";
        document.getElementById("ans2").value = "";
        document.getElementById("tc1").value = "";
        document.getElementById("tc2").value = "";
        document.getElementById("top").value = "";
        var constraintChecks = document.getElementsByClassName("check");
        for(let box of constraintChecks) {
            box.checked = false;
        }
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
        var tcIn = [];
        var tcOut = [];
        var checks = document.getElementsByClassName("check");

        for(i = 1; i < numCases+1; i++) {
            tcIn.push(document.getElementById("tc" + i).value);
            tcOut.push(document.getElementById("ans" + i).value);    
        }
        tcIn.toString();
        tcOut.toString();
        var testCases = new Object();
        testCases.Input = "[" + tcIn + "]";
        testCases.Output = "[" + tcOut + "]";
        
        var constraints = new Object();
        constraints.while = checks[0].checked;
        constraints.for = checks[1].checked;
        constraints.print = checks[2].checked;

        // If any fields are empty
        if(question == null || question == "" || difficulty == null || difficulty == "" || topic == null || topic == "" || testCases == null || testCases == [] || fName == null || fName == "") {
            document.getElementById("sysMsg").innerHTML = "All Fields are Required."
        }
        else {
            var xhttp = new XMLHttpRequest();
            var addRequest = "query=InsertQuestion&Question=" + question + "&FunctionName=" + fName + "&Difficulty=" + difficulty + "&Topic=" + topic + "&TestCases=" + JSON.stringify(testCases) + "&Constraints=" + JSON.stringify(constraints);
            var req = encodeURI(addRequest);
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
            xhttp.send(req);
        }
    }
}

// Deleting a question
function delQuestion(e) {
    var xhttp = new XMLHttpRequest();

    e = e || window.event;
    var curTarget = e.srcElement || e.target;
    while(curTarget && curTarget.tagName !== "TR") {
        curTarget = curTarget.parentNode;
    }

    var xhttp = new XMLHttpRequest();
    var delRequest = "query=DeleteQuestion&ID=" + curTarget.id;
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var check = this.responseText;
            document.getElementById("testWork").innerHTML = check;
            var checkJSON = JSON.parse(check);
            if(checkJSON.dbSuccess) {
                document.getElementById("testWork").innerHTML = "Question deleted.";
            }
            query();
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(delRequest);

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
function examToggle() {
    var table = document.getElementById("qResults");
    var queryList = table.children;
    if (!examEditingOn) {
        examEditingOn = true;
        document.getElementById("examEdit").innerHTML = "Cancel Exam Creating";
        document.getElementById("examEdit").setAttribute("onClick","examToggle()");
        document.getElementById("makeExam").style.display = "inline-block";
        document.getElementById("testWork").innerHTML = "";
        for(let curRow of queryList) {
            curRow.children[4].style.display = "";
            curRow.children[0].addEventListener("click", select);
            curRow.children[1].children[1].addEventListener("click", select);
            curRow.children[2].addEventListener("click", select);
            curRow.children[3].addEventListener("click", select);
        }
    }
    else {
        examEditingOn = false;
        document.getElementById("examEdit").innerHTML = "Make Exam";
        document.getElementById("examEdit").setAttribute("onClick","examToggle()");
        document.getElementById("makeExam").style.display = "none";
        for(let nowRow of queryList) {
            nowRow.removeEventListener("click", select);
            nowRow.children[1].children[1].removeEventListener("click", select);
            nowRow.children[2].removeEventListener("click", select);
            nowRow.children[3].removeEventListener("click", select);
            nowRow.children[4].children[0].value = "";
            nowRow.children[4].style.display = "none";
        }
        var selRows = document.getElementsByClassName("selected");
        while(selRows.length > 0) {
            for(let element of selRows) {
                element.className = element.className.replace("selected","");
            }
        }
    }
}

// Adding Questions to the Exam and Posting It
function addToExam() {
    var examQuestions = document.getElementsByClassName("selected");
    var fieldsFilled = false;
    for(let verify of examQuestions) {
        var points = verify.children[4].firstElementChild.value;
        if(points == "" || points == "0") {
            fieldsFilled = false;
            break;
        }
        else {
            fieldsFilled = true;
        }
    }
    if(fieldsFilled) {
        for(let chosen of examQuestions) {
                var xhttp = new XMLHttpRequest();
                var addRequest = "query=InsertExamQuestion&QuestionID=" + chosen.id + "&Points=" + chosen.children[4].firstElementChild.value;
                var addReq = encodeURI(addRequest);
                xhttp.onreadystatechange = function() {
                    if(this.readyState == 4 && this.status == 200) {
                        var echoed = this.responseText;
                        var response = JSON.parse(echoed);
                        if(response.dbSuccess) {
                            document.getElementById("testWork").innerHTML = "Exam Posted.";
                            teachExamView('editing');
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
                xhttp.send(addReq);
        }
        examToggle();
    }
    else {
        document.getElementById("testWork").innerHTML = "All questions going to the Exam must have a valid point value. (At least 1)";
    }
}

// Removes a question from Exam
function rmFromExam(recvID) {
    var xhttp = new XMLHttpRequest();
    var delExamQReq = "query=DeleteExamQuestion&QuestionID=" + recvID;

    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var echoBack = this.responseText;
            var JSONEcho = JSON.parse(echoBack);
            if(JSONEcho.dbSuccess) {
                document.getElementById("sysMsg1").innerHTML = "Question removed from Exam";
                teachExamView('editing');
            }
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(delExamQReq);
}

// Loading the exam for students
function loadExam() {
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var examJSON = this.responseText;
            var examResults;
            try {
                examResults = JSON.parse(examJSON);
            } catch(e) {
                console.log("JSON errror: " + examJSON);
                return;
            }
            var examArray = examResults.Exam;
            var questionArray = examResults.Questions;
            document.getElementById("fullExam").innerHTML = "EXAM 1";
            var finalGrade = 0;

            //Check if the exam has been graded
            if(examArray[0].AutoComments != "") {
                document.getElementById("qTable").style.visibility = "visible";
                var questionTable = document.getElementById("qArray");
                for(n = 0; n < examArray.length-1; n++)
                {
                    var questionID = examArray[n].QuestionID;
                    var questionText = questionArray[n].Question;
                    var questionPoints = examArray[n].Points;
                    var questionPointsGiven = examArray[n].PointsGiven;
                    var questionAutoComments = examArray[n].AutoComments;
                    var questionTeacherComments = examArray[n].TeacherComments;

                    var row = document.createElement("TR");
                    row.id = questionID;

                    var rQuestion = document.createElement("TD");
                    var rQuestionContent = document.createTextNode((n+1) + ") " + questionText);
                    rQuestion.appendChild(rQuestionContent);
                    row.appendChild(rQuestion);

                    var rPoints = document.createElement("TD");
                    var rPointsContent = document.createTextNode(questionPoints);
                    rPoints.appendChild(rPointsContent);
                    row.appendChild(rPoints);

                    var rPointsGiven = document.createElement("TD");
                    var rPointsGivenContent = document.createTextNode(questionPointsGiven);
                    finalGrade += questionPointsGiven;
                    rPointsGiven.appendChild(rPointsGivenContent);
                    row.appendChild(rPointsGiven);

                    var rAutoComments = document.createElement("TD");
                    var rAutoCommentsContent = document.createTextNode(questionAutoComments);
                    rAutoComments.appendChild(rAutoCommentsContent);
                    row.appendChild(rAutoComments);

                    var rTeacherComments = document.createElement("TD");
                    var rTeacherCommentsContent = document.createTextNode(questionTeacherComments);
                    rTeacherComments.appendChild(rTeacherCommentsContent);
                    row.appendChild(rTeacherComments);

                    questionTable.appendChild(row);

                }
                var total = document.createElement("H4");
                var totalValue = document.createTextNode("Total: " + finalGrade);
                total.appendChild(totalValue);
                questionTable.appendChild(total);
            } else if(examArray.length > 0) {
                document.getElementById("studentSubmit").style.visibility = "visible";
                document.getElementById("subMsg").style.visibility = "visible";
                var page = document.getElementById("fullExam");
                for(n = 0; n < examArray.length-1; n++) {
                    var curNode = document.createElement("P");
                    var curText = document.createTextNode((n+1) + ") " + questionArray[n].Question + " (" + examArray[n].Points + " points)");
                    curNode.appendChild(curText);
                    page.appendChild(curNode);
        
                    var ansNode = document.createElement("TEXTAREA");
                    ansNode.rows = "20";
                    ansNode.cols = "100";
                    if(examArray[n].Answer) {
                        ansNode.value = examArray[n].Answer;
                    }
                    ansNode.id = examArray[n].QuestionID;
                    ansNode.setAttribute("onKeyDown","insertTab(this,event)");
                    page.appendChild(ansNode);
                }
            }
        }
    }
    xhttp.open("POST","betaFrontCurl.php", true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send("query=GetExam");
}

// For submitting the exam
function submitExam() {
    var ansList = document.getElementsByTagName("textarea");

    for(let answer of ansList) {
        var xhttp = new XMLHttpRequest();
        var submitRequest = "query=UpdateExamQuestion&QuestionID=" + answer.id + "&Answer=" + answer.value;
        console.log(answer.id + " " + answer.value);
        var subReq = encodeURI(submitRequest);
        xhttp.onreadystatechange = function() {
            console.log(this.readyState + " " + this.status);
            if(this.readyState == 4 && this.status == 200) {
                var sEchoJSON = this.responseText;
                console.log(sEchoJSON);
                var sEcho;
                try{
                    sEcho = JSON.parse(sEchoJSON);
                } catch(e) {
                    console.log("Error: " + sEchoJSON);
                    return;
                }
                if(sEcho.dbSuccess) {
                    document.getElementById("system").innerHTML = "Answers Submitted! Grade will be posted Later.";
                }
            }
        }
        xhttp.open("POST","betaFrontCurl.php", true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(subReq);
    }
}

function editExam() {
    var massOfData = document.getElementById("examQuestions");
    var elements = massOfData.children;
    for(let eQuestion of elements) {
        var xhttp = new XMLHttpRequest();
        var updateReq = "query=UpdateExamQuestion&QuestionID=" + eQuestion.id + + "&Points=" + eQuestion.children[2].firstElementChild.value;
        console.log(eQuestion.id + " " + eQuestion.children[2].firstElementChild.value);
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                var strJSON = this.responseText;
                console.log(strJSON);
                var JSONObj = JSON.parse(strJSON);
                if(JSONObj.dbSuccess) {
                    document.getElementById("sysMsg").innerHTML = "Changes were successful.";
                    teachExamView('editing');
                }
            }
        }
        xhttp.open("POST","betaFrontCurl.php",true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(updateReq)
    }
}

function resetExam() {
    var examTable = document.getElementById("examQuestions");
    var examElements = examTable.children;
    for(let gradedQ of examElements) {
        var xhttp = new XMLHttpRequest();
        var req = "query=UpdateExamQuestion&QuestionID=" + gradedQ.id + "&Answer=&PointsGiven=0&AutoComments=&TeacherComments=";
        console.log(req);
        var codedReq = encodeURI(req);
        xhttp.onreadystatechange = function() {
            console.log(this.readyState + " " + this.status);
            if(this.readyState == 4 && this.status == 200) {
                var resp = this.responseText;
                console.log(resp);
                var respObj = JSON.parse(resp);
                if(respObj.dbSuccess) {
                    document.getElementById("sysMsg").innerHTML = "Exam reset.";
                }
            }
        }
        xhttp.open("POST","betaFrontCurl.php",true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(codedReq);
    }
}

// Sending Post Request Method
/*function sendPost(request) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            var responseJSON = JSON.parse(response);
            return responseJSON;
        }
    }
    xhttp.open("POST","betaFrontCurl.php",true);
    xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    xhttp.send(request);
}*/


// To enable user-friendly selection
function select() {
    var element = this;
    while(element.tagName != "TR") {
        element = element.parentNode;
    }
    element.classList.toggle("selected");
}

// For allowing tab in textarea
function insertTab(o, e) {		
    var kC = e.keyCode ? e.keyCode : e.charCode ? e.charCode : e.which;
    if (kC == 9 && !e.shiftKey && !e.ctrlKey && !e.altKey) {
        var oS = o.scrollTop;
        if (o.setSelectionRange) {
            var sS = o.selectionStart;	
            var sE = o.selectionEnd;
            o.value = o.value.substring(0, sS) + "\t" + o.value.substr(sE);
            o.setSelectionRange(sS + 1, sS + 1);
            o.focus();
        }
        else if (o.createTextRange){
            document.selection.createRange().text = "\t";
            e.returnValue = false;
        }
        o.scrollTop = oS;
        if (e.preventDefault){
            e.preventDefault();
        }
        return false;
    }
    return true;
}
