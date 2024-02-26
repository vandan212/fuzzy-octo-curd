<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP & Ajax CRUD</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <table id="main" border="0" cellspacing="0">
    <tr>
      <td id="header">
        <h1>PHP & Ajax CRUD</h1>

        <div id="search-bar">
          <label>Search :</label>
          <input type="text" id="search" autocomplete="off">
        </div>
      </td>
    </tr>
    <div id="sub-header">
    <button id="delete-all-button">Delete</button>
    </div>
    <tr>
      <td id="table-form">
        <form id="addForm">
          First Name : <input type="text" id="fname">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          Last Name : <input type="text" id="lname">
          <input type="submit" id="save-button" value="Save">
      </td>
    </tr>
    <tr>
      <td id="table-data">
      </td>
    </tr>
  </table>
  <div id="error-message"></div>
  <div id="success-message"></div>
  <div id="modal">
    <div id="modal-form">
      <h2>Edit Form</h2>
      <table cellpadding="10px" width="100%">
      </table>
      <div id="close-btn">X</div>
    </div>
  </div>

  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      // Load Table Records
      function loadTable() {
        $.ajax({
          url: "load.php",
          type: "POST",
          success: function(data) {
            $("#table-data").html(data);
          }
        });
      }
      loadTable();

      // Validates Name
      function validateName(name) {
        var regex = new RegExp("^[a-zA-Z]*$");
        if (regex.test(name)) {
          return true
        }
        return false
      }

      // Success Toast
      function successToast(message) {
        $("#success-message").html(message).slideDown();
        setTimeout(function() {
          $("#success-message").remove()
        }, 3000);
      }

      // Error Toast
      function errorToast(message) {
        $("#error-message").html(message).slideDown();
        setTimeout(function() {
          $("#error-message").remove()
        }, 3000);
      }

      // Insert New Records
      $("#save-button").on("click", function(e) {
        e.preventDefault();
        var fname = $("#fname").val();
        var lname = $("#lname").val();

        if (fname == "" || lname == "") {
          errorToast("All fields are required.")
        } else if (!validateName(fname)) {
          errorToast("Please provide valid first name")
        } else if (!validateName(lname)) {
          errorToast("Please provide valid last name")
        } else {
          $.ajax({
            url: "insert.php",
            type: "POST",
            data: {
              first_name: fname,
              last_name: lname
            },
            success: function(data) {
              if (data == 1) {
                loadTable();
                $("#addForm").trigger("reset");
                successToast("Data Inserted Successfully.")
              } else {
                errorToast("Can't Save Record.")
              }

            }
          });
        }

      });

      //Delete Records
      $(document).on("click", ".delete-btn", function() {
        if (confirm("Do you really want to delete this record ?")) {
          var studentId = $(this).data("id");
          var element = this;

          $.ajax({
            url: "delete.php",
            type: "POST",
            data: {
              id: studentId
            },
            success: function(data) {
              if (data == 1) {
                $(element).closest("tr").fadeOut();
              } else {
                errorToast("Can't Delete Record.")
              }
            }
          });
        }
      });

      //Show Modal Box
      $(document).on("click", ".edit-btn", function() {
        $("#modal").show();
        var studentId = $(this).data("eid");

        $.ajax({
          url: "loadupdate.php",
          type: "POST",
          data: {
            id: studentId
          },
          success: function(data) {
            $("#modal-form table").html(data);
          }
        })
      });

      //Hide Modal Box
      $("#close-btn").on("click", function() {
        $("#modal").hide();
      });

      //Save Update Form
      $(document).on("click", "#edit-submit", function() {
        var stuId = $("#edit-id").val();
        var fname = $("#edit-fname").val();
        var lname = $("#edit-lname").val();

        if (fname == "" || lname == "") {
          errorToast("All fields are required.")
        } else if (!validateName(fname)) {
          errorToast("Please provide valid first name")
        } else if (!validateName(lname)) {
          errorToast("Please provide valid last name")
        } else {
          $.ajax({
            url: "update.php",
            type: "POST",
            data: {
              id: stuId,
              first_name: fname,
              last_name: lname
            },
            success: function(data) {
              if (data == 1) {
                $("#modal").hide();
                loadTable();
              }
            }
          })
        }


      });

      // Live Search
      $("#search").on("keyup", function() {
        var search_term = $(this).val();

        $.ajax({
          url: "search.php",
          type: "POST",
          data: {
            search: search_term
          },
          success: function(data) {
            $("#table-data").html(data);
          }
        });
      });

      // Checkbox Select All
      $(document).on("click", "#select-all", function(e) {
        $('input:checkbox').not(this).prop('checked', this.checked);
      });

      // Delete All
      $('#delete-all-button').on("click", function() {
        if($("#select-all").prop('checked') == true){
        
          if (confirm("Do you really want to delete all record ?")) {
  
            var element = this;
  
            $.ajax({
              url: "delete-all.php",
              type: "POST",
              success: function(data) {
                if (data == 1) {
                  $("#table-data").empty();
                } else {
                  errorToast("Can't Delete Records.")
                }
              }
            });

            loadTable();

          }    
        }
      
        })
      


    });

     
  
  </script>
</body>





</html>