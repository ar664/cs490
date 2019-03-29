// Global variables (starting positions)
var examEditingOn = false;
var numCases = 2;

// For searching through the question bank
function query() {
    document.getElementById("version").innerHTML = "V 1.2.265";

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

            // Clears the table to receive the other questions
            while(queryTable.children.length > 0) {
                queryTable.removeChild(queryTable.firstChild);
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

                var rName = document.createElement("TD");
                var rNameContent = document.createTextNode(questionName);
                rName.appendChild(rNameContent);
                rName.className = "question";
                row.appendChild(rName);

                var rTopic = document.createElement("TD");
                var rTopicContent = document.createTextNode(questionTopic);
                rTopic.appendChild(rTopicContent);
                row.appendChild(rTopic);

                var rDifficulty = document.createElement("TD");
                var rDifficultyContent = document.createTextNode(questionDifficulty);
                rDifficulty.appendChild(rDifficultyContent);
                row.appendChild(rDifficulty);

                var rPoints = document.createElement("TD");
                var rPointsBox = document.createElement("INPUT");
                rPointsBox.type = "text";
                rPointsBox.style.width = "1rem";
                rPoints.appendChild(rPointsBox);
                row.appendChild(rPoints);

                var rCheck = document.createElement("TD");
                var rCheckBox = document.createElement("INPUT");
                rCheckBox.type = "checkbox";
                rCheckBox.value = questionID;
                rCheckBox.style.width = "1rem";
                rCheck.appendChild(rCheckBox);
                row.appendChild(rCheck);

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
        }
        /*else {
            document.getElementById("testWork").innerHTML = this.readyState + " " + this.status;
        }*/
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
                    var questText = document.createTextNode(questArray[qNum].Question);
                    curQuest.appendChild(questText);
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
                if(examJS.AutoComments !== "") {
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
                        if(exArray[eqNum]) {
                            curAnsText = document.createTextNode("No answer given.");
                        }
                        else {
                            curAnsText = document.createTextNode(exArray[eqNum].Answer);
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
    var curTab, tabContent, tabLinks;
    tabContent = document.getElementsByClassName("tabcontent");
    for(curTab = 0; curTab < tabContent.length; curTab++) {
        tabContent[curTab].style.display = "none";
    }
    tabLinks = document.getElementsByClassName("tablinks");
    for(curTab = 0; curTab < tabLinks.length; curTab++) {
        tabLinks[curTab].className = tabLinks[curTab].className.replace(" active", "");
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
        for(i = 1; i < numCases+1; i++) {
            tcIn.push(document.getElementById("tc" + i).value);
            tcOut.push(document.getElementById("ans" + i).value);    
        }
        tcIn.toString();
        tcOut.toString();
        var testCases = new Object();
        testCases.Input = "[" + tcIn + "]";
        testCases.Output = "[" + tcOut + "]";

        // If any fields are empty
        if(question == null || question == "" || difficulty == null || difficulty == "" || topic == null || topic == "" || testCases == null || testCases == [] || fName == null || fName == "") {
            document.getElementById("sysMsg").innerHTML = "All Fields are Required."
        }
        else {
            var xhttp = new XMLHttpRequest();
            var addRequest = "query=InsertQuestion&Question=" + question + "&FunctionName=" + fName + "&Difficulty=" + difficulty + "&Topic=" + topic + "&TestCases=" + JSON.stringify(testCases);
            var req = encodeURI(addRequest);
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    var echoed = this.responseText;
                    var response = JSON.parse(echoed);
                    console.log(response);
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
    if(examEditingOn) {
        rmFromExam(curTarget.id);
        return;
    }

    else {
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
            /*else {
                document.getElementById("status").innerHTML = this.readyState + " " + this.status;
            }*/
        }
        xhttp.open("POST","betaFrontCurl.php", true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(delRequest);
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
    examEditingOn = true;
    var table = document.getElementById("queryResults");
    document.getElementById("examEdit").innerHTML = "Cancel Exam Creating";
    document.getElementById("examEdit").setAttribute("onClick","examOff()");
    //document.getElementById("makeExam").style.visibility = "visible";
    document.getElementById("testWork").innerHTML = "";
    /*var queryList = table.children;
    for(k = 0; k < queryList.length; k++) {
        queryList[k].children[3].style.visibility = "visible";
        queryList[k].addEventListener("click", select(e));
    } */
}
function examOff() {
    examEditingOn = false;
    //var table = document.getElementById("queryResults");
    document.getElementById("examEdit").innerHTML = "Make Exam";
    document.getElementById("examEdit").setAttribute("onClick","examOn()");
    //document.getElementById("makeExam").style.visibility = "hidden";
    /*var queryList = table.children;
    for(m = 0; m < queryList.length; m++) {
        queryList[m].removeEventListener("click", select(e));
        queryList[m].children[3].value = "";
        queryList[m].children[3].style.visibility = "hidden";
    }
    for(p = 0; p < table.getElementByClassName("selected").length; p++) {
        table.getElementByClassName("selected")[p].classList.toggle("selected");
    }*/
}

// Adding Questions to the Exam and Posting It
function addToExam() {
    var table = document.getElementById("qResults");
    var examQuestions = table.children;

    var xhttp = new XMLHttpRequest();
    for(s = 0; s < examQuestions.length; s++) {
        if(examQuestions[s].children[4].firstChild.checked) {
            var addRequest = "query=InsertExamQuestion&QuestionID=" + examQuestions[s].id + "&Points=" + examQuestions[s].children[3].firstElementChild.value;
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

// Loading the exam for students (Testing)
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
                    var rQuestionContent = document.createTextNode(questionText);
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
                    var curText = document.createTextNode(questionArray[n].Question + "(" + examArray[n].Points + " points)");
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

// For submitting the exam (testing)
function submitExam() {
    var ansList = document.getElementsByTagName("textarea");

    // Testing variables (delete later)
    var successes = 0;
    var failures = 0;
    var errors = 0;
    var reqString = "";

    for(se = 0; se < ansList.length; se++) {
        var xhttp = new XMLHttpRequest();
        var submitRequest = "query=UpdateExamQuestion&QuestionID=" + ansList[se].id + "&Answer=" + ansList[se].value;
        var subReq = encodeURI(submitRequest);
        reqString+= se + " " + submitRequest + "|";
        xhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                var sEchoJSON = this.responseText;
                var sEcho;
                try{
                    sEcho = JSON.parse(sEchoJSON);
                } catch(e) {
                    console.log("Error: " + sEchoJSON);
                    return;
                }
                if(sEcho.dbSuccess) {
                    document.getElementById("system").innerHTML = "Answers Submitted! Grade will be posted Later.";
                    successes++;
                }
                else {
                    document.getElementById("system").innerHTML = "dbSuccess is false?";
                    failures++;
                }
            }
            else {
                document.getElementById("erroring").innerHTML = this.readyState + " " + this.status;
                errors++;
            }
        }
        xhttp.open("POST","betaFrontCurl.php", true);
        xhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
        xhttp.send(subReq);
    }
    document.getElementById("system").innerHTML = successes + " yes & " + failures + " no & " + errors + " errors";
    document.getElementById("erroring").innerHTML = reqString;
}

function editExam() {
    var massOfData = document.getElementById("examQuestions");
    var elements = massOfData.children;
    for(ee = 0; ee < elements.length; ee++) {
        var xhttp = new XMLHttpRequest();
        var updateReq = "query=UpdateExamQuestion&QuestionID=" + elements[ee].id + + "&Points=" + elements[ee].children[2].firstElementChild.value;
        console.log(elements[ee].id + " " + elements[ee].children[2].firstElementChild.value);
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
    for(rX = 0; rX < examElements.length; rX++) {
        var xhttp = new XMLHttpRequest();
        var req = "query=UpdateExamQuestion&QuestionID=" + examElements[rX].id + "&Answer=''&PointsGiven=''&AutoComments=''&TeacherComments=''";
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
function select(e) {
    e = e || window.event;
    var target = e.srcElement || e.target;
    while(target && target.nodeName !== "TR") {
        target = target.parentNode;
    }
    target.classList.toggle("selected");
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
