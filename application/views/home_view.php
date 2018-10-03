<!DOCTYPE html>
<html lang="en">
<head prefix="dcterms: http://purl.org/dc/terms/">
    <title>EADitor Institutional Repository View</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!--link rel="stylesheet" href="styles/bootstrap.css"-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js" integrity="sha384-CchuzHs077vGtfhGYl9Qtc7Vx64rXBXdIAZIPbItbNyWIRTdG0oYAqki3Ry13Yzu" crossorigin="anonymous"></script>


    <!-- Optional theme -->

    <!-- Latest compiled and minified JavaScript -->
    <style>
        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
            margin-bottom: 0;
            border-radius: 0;
        }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
            padding-top: 20px;
            /*background-color: #f1f1f1;*/
            height: 100%;
        }
        .panel-heading .accordion-toggle:after {
            /* symbol for "opening" panels */
            font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
            content: "\e114";    /* adjust as needed, taken from bootstrap.css */
            float: right;        /* adjust as needed */
            color: grey;         /* adjust as needed */
        }
        .panel-heading .accordion-toggle.collapsed:after {
            /* symbol for "collapsed" panels */
            content: "\e080";    /* adjust as needed, taken from bootstrap.css */
        }

        	.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )
                url('./icons/spinner.gif')
                50% 50%
                no-repeat;
		}

/* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
body.loading .modal {
    overflow: hidden;
}

/* Anytime the body has the loading class, our
   modal element will be visible */
body.loading .modal {
    display: block;
}

tr#red{
    background: #b31b1b;
}


    </style>
  
    <?php
?>
</head>
<body>

<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!--a class="navbar-brand" href="/"><img src='https://www.empireadc.org/sites/www.empireadc.org/files/ead_logo.gif' /></a-->
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <!--li class="active"><a href="#">Home</a></li-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <!--li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li-->
                <!--li><a href="#">About</a></li>
                <li><a href="#">Contact</a></li-->
                <li><a href='https://drive.google.com/open?id=1hsFy_xJ9uIP_wkRZjityXVdWVHSQF3X9eVALv2sMEo4' target='_blank'>Feedback/Issue</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class = "container">
        <div class="col-sm-2 sidenav">
            <a href='<?php echo base_url( ); ?>'><img src='https://www.empireadc.org/sites/www.empireadc.org/files/ead_logo.gif' style='width:220px; margin-top: -75px'/></a>
            <!--p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p>
            <p><a href="#">Link</a></p-->
        </div>
    <br>
<div class="col-sm-10" id="content">
    <h2 align="center">EAD Harvester</h2>
        <hr>
<br>

    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#repoSection">
                        Select a Git Repository
                    </a>
                </h4>
            </div>
            <!--h4 align="center">Institution's Git Repo</h4>
            <h4 data-toggle="collapse" data-target="#instInfo" class='infoAccordion accordion active'>Institution's Git Repo  <span class="glyphicon glyphicon-menu-right" style="float:right;"></span></h4-->

                <div id="repoSection" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <!--div class="form-group">
                            <label>Institution</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                                <input type="text" class="form-control" name="institution" id="inst" placeholder="Institution's Name">
                            </div>
                        </div-->
                        <div class="form-group">
                            <label>Git User Name</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Git Repo User Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Select Repository</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-th-list"></span></span>
                                <select class="form-control" name="repositorySelect" id="reposel">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Select Branch</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-th-list"></span></span>
                                <select class="form-control" name="branchSelect" id="brsel">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Repository Name</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
                                <input type="text" class="form-control" name="repository" id="repository" placeholder="Add Repository Name">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label>Agency Code</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                                <input type="text" class="form-control" name="agencyCode" id="agencyCode" placeholder="Agency Code for the collection">
                            </div>
                        </div>    
                        <!--div class="form-group">
                            <label>Select Directory</label>
                            <div class="input-group"> <span class="input-group-addon"><span class="glyphicon glyphicon-indent-left"></span></span>
                                <select class="form-control" name="directorySelect" id="dirsel">
                                </select>
                            </div>
                        </div-->
                        <!--li><input type="checkbox" id="select_all"/> Selecct All</li-->

            </div>
                </div>
                  </div>
                  </br><a href='https://docs.google.com/document/d/1DnOmxlH6jwH_3dNswq_Vcc3W9pdHMjZeID_DfJxHS9Y/edit?usp=sharing' target="_blank" ><button class="btn btn-primary" style="margin-bottom: 20px">VIEW VALIDATION RULES</button></a></br>
  

        <!--h4 data-toggle="collapse" data-target="#eadList" class='infoAccordion accordion'>List of Files<span class="glyphicon glyphicon-menu-right" style="float:right;"></span></h4>

        <div id="eadList" class="collapse"-->
        <div id="filePanel" class="panel panel-default" style="visibility: hidden; margin-bottom: 20px">
            <div class="panel-heading">
                <h4 class="panel-title">

                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#fileSection">
                        List of Files
                    </a>
                </h4>
               
            </div>
            <button class="btn btn-primary center-block" type="button" id="validate">Validate</button>
            <div id="fileSection" class="panel-collapse collapse in">
                <div class="panel-body">
                <table class="table table-striped" name="fileTable" id="fileTable" style="visibility: hidden">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>File Name</th>
                        <th>Selected files <br/> to validate &nbsp;(&nbsp;<input type="checkbox" checked name="eadFileSelect" id="select_all" checked>&nbsp;All</input>&nbsp;)</th>

                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
              
            </div>


        </div>
        </div>
        <div id="resultPanel" class="panel panel-default" style="visibility: hidden">
            <div class="panel-heading">
                <h4 class="panel-title">

                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#resultSection">
                        Results
                    </a>
                </h4>
                <button class="btn btn-primary" onclick="javascript:createPdf();" type="button" id="print">Print Pdf</button>
               
            </div>
            <div id="resultSection" class="panel-collapse collapse in">
                <div id="resultBody" class="panel-body">
                    <table class="table table-striped" name="resultTable" id="resultTable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>File Name</th>
                            <th>Rules Passed</th>
                            <th>Rules Failed</th>

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                   
                    <h6></h6>
                </div>


            </div>
        </div>
        </div>

        </div>
</div>
<div class="modal"><!-- Place at bottom of page --></div>
<script>

    $body = $("body");
    $(document).ajaxStop(function() {
	    $body.removeClass("loading");
    });

    $(document).ready(function() {
        $("input#username").change(function () {
                // $('#brsel').empty();
                // $('#reposel').empty();
                // $("#dirsel").empty();
                document.getElementById("filePanel").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";

                var gitUser = document.getElementById("username").value;

                // var gitRepositoryName = document.getElementById("repo").value;
                if (gitUser != null) {
                    $.ajax({
                        url: "https://api.github.com/users/" + gitUser + "/repos",
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#reposel').empty();
                            $('#reposel').append($('<option>', {
                                value: "select repo",
                                text: "Select your Repository"
                            }));

                            $.each(res, function () {
                                $('#reposel').append($('<option>', {
                                    value: this['name'],
                                    text: this['name']
                                }));


                            });
                        }
                    });
                }
            });

            $("#reposel").change(function () {
                $('#brsel').empty();
                $('#dirsel').empty();
                $("#fileTable tbody").empty();
                document.getElementById("filePanel").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";
                var gitUser = document.getElementById("username").value;
                var repoSel = this.value;
                if (repoSel != "select repo") {
                    $.ajax({
                        // https://api.github.com/repos/:username/:repositoryname/branches
                        url: "https://api.github.com/repos/" + gitUser + "/" + repoSel + "/branches",
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#brsel').append($('<option>', {
                                value: "select branch",
                                text: "Select your Branch"
                            }));
                            $.each(res, function () {
                                $('#brsel').append($('<option>', {
                                    value: this['name'],
                                    text: this['name']
                                }));

                            });
                        }
                    });
                }

            });

            $("#brsel").change(function () {
                var gitUser = document.getElementById("username").value;
                var repoSel = $("#reposel").val();
                var brSel = this.value;
               // var dirSel = this.value;
                $("#fileTable tbody").empty();
                document.getElementById("filePanel").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";
               // if (dirSel != "select directory") {
                    $.ajax({
                        //https://api.github.com/repos/dkarnati174/EADs/git/trees/master
                        url: "https://api.github.com/repos/" + gitUser + "/" + repoSel + "/git/trees/" + brSel,
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                           /* $('#dirsel').append($('<option>', {
                                value: "select directory",
                                text: "Select a Directory"
                            }));*/
                            var i = 1;
                            document.getElementById("filePanel").style.visibility = "visible";
                            document.getElementById("fileTable").style.visibility = "visible";
                            $('#filePanel').show();

                            $.each(res, function (key, value) {
                                if (key == "tree") {
                                    $.each(value, function () {
                                        var link = "https://raw.githubusercontent.com/" + gitUser + "/" + repoSel + "/" + brSel + "/" + "/" + this['path']
                                        $('#fileTable').append('<tr><td>' + i + '</td>' +
                                            '<td><a href="' + link + '" target="_blank">' + this['path'] + '</a></td>' +
                                            '<td><input type="checkbox" align="center" checked class="form-check-input" name="eadFileSelect" value="' + this['path'] + '"></td></tr>');
                                        i++;

                                    });

                                }
                            });
                        }
                    });
              //  }
            });
         /*   $("#dirsel").change(function () {
                var gitUser = document.getElementById("username").value;
                var repoSel = $("#reposel").val();
                var brSel = $("#brsel").val();
                var dirSel = this.value;
                $("#fileTable tbody").empty();
                document.getElementById("filePanel").style.visibility = "hidden";
                document.getElementById("fileTable").style.visibility = "hidden";
                if (dirSel != "select directory") {
                    $.ajax({
                        //https://api.github.com/repos/dkarnati174/EADs/git/trees/master
                        url: "https://api.github.com/repos/" + gitUser + "/" + repoSel + "/git/trees/" + brSel + ":" + dirSel,
                        jsonp: true,
                        method: "GET",
                        dataType: "json",
                        success: function (res) {
                            $('#dirsel').append($('<option>', {
                                value: "select directory",
                                text: "Select a Directory"
                            }));
                            var i = 1;
                            document.getElementById("filePanel").style.visibility = "visible";
                            document.getElementById("fileTable").style.visibility = "visible";

                            $.each(res, function (key, value) {
                                if (key == "tree") {
                                    $.each(value, function () {
                                        var link = "https://raw.githubusercontent.com/" + gitUser + "/" + repoSel + "/" + brSel + "/" + dirSel + "/" + this['path']
                                        $('#fileTable').append('<tr><td>' + i + '</td>' +

                                            '<td><a href="' + link + '">' + this['path'] + '</a></td>' +
                                            '<td><input type="checkbox" align="center" checked class="form-check-input" name="eadFileSelect" value="' + this['path'] + '"></td></tr>');
                                        i++;

                                    });

                                }
                            });
                        }
                    });
                }
            });
            */

            $('button#validate').click(function () {
                if ($('#repository').val() != ''){
                  
                    if($('#agencyCode').val() != ''){
    
                        var gitUser = document.getElementById("username").value;
                        var repoSel = $("#reposel").val();
                        var brSel = $("#brsel").val();
                        var acode = $("#agencyCode").val();
                        var repoName = $("#repository").val();
                        //var dirSel = $("#dirsel").val();
                        $("#resultTable tbody").empty();
                        var fileList = [];
                        $.each($("input:checked[name='eadFileSelect']"), function () {
                                var filename = this.value;
                            if (filename.indexOf(".xml") != -1) {
                                fileList.push(this.value);
                            }
                        });

                        if (fileList.length == 0) {

                            alert("Please select atleast one EAD file from the selected directory/Make sure that the selected directory has EAD files");
                
                        } else {
                            
                            //loading spinner gif
                            $("body").addClass("loading");
                            var result = "";
                            
                            $.post("<?php echo base_url("?c=eadharvester&m=validate");?>", {
                                //  institute: instName,
                                gituserId: gitUser,
                                repository: repoSel,
                                branch: brSel,
                                agencyCode: acode,
                                repoName: repoName,
                                //directory: dirSel,
                                fileList: JSON.stringify(fileList)

                            }).done(function (response) {

                                document.getElementById("resultPanel").style.visibility = "visible";
                                document.getElementById("resultTable").style.visibility = "visible";
                                
                                if(response !=""){
                                    var result = JSON.parse(response);
                                    $('#filePanel').hide();
                                    
                                    for(var i=0; i< result.length;i++){
                                        // Change row background to red if rules failed.                                         
                                        var color = 'white';
                                        if(result[i].rules_failed != ' '){
                                            color = 'red';
                                        }

                                        var link = "https://raw.githubusercontent.com/" + gitUser + "/" + repoSel + "/" + brSel + "/" + "/" + result[i].file_name;
                                                              
                                        $('#resultTable').append('<tr id="'+ color +'"><td>' + (i+1) + '</td>' +
                                            '<td><a href="' + link + '" target="_blank">' + result[i].file_name + '</a></td>' + 
                                            '<td>'+result[i].rules_valid+'</td>'+
                                            '<td>'+ result[i].rules_failed+'</td></tr>');
                                    }
                                }else {
                                      alert('Technical issue with the results. Please refresh and try again or contact Administrator!')  
                                 }
                            });
                        }

                    }else{
                        alert('Missing Agency Code');
                    }
              
                }else{
                    alert('Missing Repository Name');
                }
              
            });
        });
           
        function createPdf() {
            var pdf = new jsPDF('p', 'pt', 'letter');

            source = $('#resultBody')[0];


            specialElementHandlers = {
                '#bypassme': function (element, renderer) {
                    return true
                }
            };
            margins = {
                top: 80,
                bottom: 60,
                left: 60,
                width: 522
            };

            pdf.fromHTML(
                source,
                margins.left,
                margins.top, {
                    'width': margins.width,
                    'elementHandlers': specialElementHandlers
                },

                function (dispose) {

                    pdf.save('EAD_Validation_Results.pdf');
                }, margins);
        }

       $("button#download").click(function(){
            $.post("<?php echo base_url("?c=eadharvester&m=downloadFiles");?>", {
                   
            }).done(function (response) {
                alert('Done');
            });
       }); 

      $("#select_all").change(function() {
            if(this.checked) {
                $(".form-check-input").prop('checked', true);
            }else{
                $(".form-check-input").prop('checked', false);
                }
                //alert('Monish');
        });
</script>

</body>

</html>








