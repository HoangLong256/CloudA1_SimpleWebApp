<?php 
	session_start();
	require_once 'php/google-api-php-client/vendor/autoload.php';
	$title = 'Frequency';
	include('./common/boostrap.php');
	include('./common/navbar.php');
	include('./function.php');
	include('./common/constant.php');
?>

<body>
	<div class="d-flex justify-content-center my-2">
        <h1>Big Query Result</h1>
    </div>
	<?php
	// Set up config
		$client = new Google_Client();
		$client->useApplicationDefaultCredentials();
		$client->addScope(Google_Service_Bigquery::BIGQUERY);
		$bigquery = new Google_Service_Bigquery($client);
		$projectId = cloudID;
		$request = new Google_Service_Bigquery_QueryRequest();
		$str = '';
		//Generate array to store data
		$listOfEmployeeID = array();
		$listOfEmployeeLastName = array();
		$listOfEmployeeFirstName = array();
		$listOfEmployeeLastNameF = array();
		$listOfEmployeeFirstNameF = array();
		$dataArray = getEmployee();
		if($dataArray != -1){
			// Load data into serveral arrays
			foreach($dataArray as $data){
				// Getting data from bucket file
				$eData = explode(",", $data);
				array_push($listOfEmployeeID, $eData[0]);
				array_push($listOfEmployeeFirstName, $eData[1]);
				array_push($listOfEmployeeLastName, $eData[2]);
				// Getting first name frequency from BigQuery
				$request->setQuery("SELECT SUM(Frequency) AS total FROM [baby.baby_names] WHERE LOWER(name) = LOWER('$eData[1]')");
				$response = $bigquery->jobs->query($projectId, $request);
				$rows = $response->getRows();
				foreach ($rows as $row){
					foreach ($row['f'] as $field){
						array_push($listOfEmployeeFirstNameF, $field['v'] ? $field['v'] : 0);
					}
				}
				// Getting last name frequency from BigQuery
				$request->setQuery("SELECT SUM(Frequency) AS total FROM [baby.baby_names] WHERE LOWER(name) = LOWER('$eData[2]')");
				$response = $bigquery->jobs->query($projectId, $request);
				$rows = $response->getRows();
				foreach ($rows as $row){
					foreach ($row['f'] as $field){
						array_push($listOfEmployeeLastNameF, $field['v'] ? $field['v'] : 0);
					}
				}
			}
		}
	?>
	<!-- Display data ino table -->
	<div class="row m-0">
		<div class="d-flex justify-content-center m-auto">
			<table class="table table-hover m-auto">
				<thead class="thead-dark">
					<tr>
						<th scope="col">ID</th>
						<th scope="col">Last Name</th>
						<th scope="col">Frequency</th>
						<th scope="col">First Name</th>
						<th scope="col">Frequency</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i = 0; $i < count($listOfEmployeeID); $i++){
						echo "<tr>";
						echo    "<td>".$listOfEmployeeID[$i]."</td>";
						echo    "<td>".$listOfEmployeeLastName[$i]."</td>";
						echo    "<td>".$listOfEmployeeLastNameF[$i]."</td>";
						echo    "<td>".$listOfEmployeeFirstName[$i]."</td>";
						echo    "<td>".$listOfEmployeeFirstNameF[$i]."</td>";
						echo "</tr>";
					}
				?>
				</tbody>
			</table>
		</div>

	</div>

	<?php
		include('./common/footer.php');
	?>
</body>