<?php
include 'DBConn.php';

$result = $conn->query("SELECT * FROM adoption_application");
?>


<?php include 'includes/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Application Management</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  font-family: Arial, sans-serif;
}

th, td {
  border: 1px solid #ccc;
  padding: 10px;
  text-align: center;
}

th {
  background-color: lightseagreen;
  color: white;
  font-weight: bold;
}

tr:nth-child(even) {
  background-color: #fafafa;
}

button {
  padding: 6px 12px;
  margin: 2px;
  border: none;
  cursor: pointer;
  border-radius: 4px;
}

button.approve {
  background-color: green;
  color: white;
}

button.reject {
  background-color: red;
  color: white;
}
</style>

</head>
<body>

<main>

    <h2>Adoption Application</h2>

    <table border="1">

        <tr>
            <th>Applicant</th>
            <th>Pet</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
  <tr>
    <td>John Smith</td>
    <td>Luna (Dog)</td>
    <td>Pending</td>
    <td>
      <button style="background-color:green;color:white;">Approve</button>
      <button style="background-color:red;color:white;">Reject</button>
    </td>
  </tr>
  <tr>
    <td>Sarah Mkhize</td>
    <td>Whiskers (Cat)</td>
    <td>Submitted</td>
    <td>
      <button style="background-color:green;color:white;">Approve</button>
      <button style="background-color:red;color:white;">Reject</button>
    </td>
  </tr>
  <tr>
    <td>Michael Smith</td>
    <td>Goldie (Fish)</td>
    <td>Approved</td>
    <td>
      <button style="background-color:green;color:white;">Approved</button>
    </td>
  </tr>
  <tr>
    <td>Aisha Patel</td>
    <td>Snowy (Rabbit)</td>
    <td>Rejected</td>
    <td>
      <button style="background-color:red;color:white;">Rejected</button>
    </td>
  </tr>
  <tr>
    <td>Nomsa Dlamini</td>
    <td>Buddy (German Shepherd)</td>
    <td>Pending Review</td>
    <td>
      <button style="background-color:green;color:white;">Approve</button>
      <button style="background-color:red;color:white;">Reject</button>
    </td>
  </tr>
</table>

<a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
</main>

</body>
</html>