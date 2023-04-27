<?php 
include_once 'connect.php'; 

$arrayTime = array("10:00-11:00"=>"AVAILABLE","11:00-12:00"=>"AVAILABLE","12:00-13:00"=>"AVAILABLE","13:00-14:00"=>"AVAILABLE","14:00-15:00"=>"AVAILABLE","15:00-16:00"=>"AVAILABLE","16:00-17:00"=>"AVAILABLE");
$arrlength = count($arrayTime);

if(!empty($_POST["pickDate"])){ 
    $query = "SELECT * FROM schedule WHERE date = '".$_POST["pickDate"]."' AND application_type = 'BN'"; 
    $result = $conn->query($query); 

     echo '<table class="table">
            <thead class="thead-light text-center">
                <tr>
                <th scope="col">TIME</th>
                <th scope="col">SLOT</th>
                </tr>
            </thead>
            <tbody class="text-center">';
    if($result->num_rows > 0){ 
        $bookedTime = array();
        while($row = $result->fetch_assoc()){
            $booked = $row["time"];
            $bookedTime += [$booked =>"BOOKED"];
        }
        $updatedArray = array_merge($arrayTime,$bookedTime);
        $count = 1;
       foreach($updatedArray as $x => $x_value){
        echo '<tr>
                <th scope="row">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="timepicked" id="tp'.$count.'" value="'.$x.'" onclick="getTime(this.value)">
                        <label class="form-check-label" for="tp'.$count.'">'.$x.'</label>
                    </div>
                </th>
                <td>'.$x_value.'</td>
              </tr>';
              $count++;
        }

    }else{ $count = 1;
       foreach($arrayTime as $x => $x_value){
        echo '<tr>
                <th scope="row">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="timepicked" id="tp'.$count.'" value="'.$x.'" onclick="getTime(this.value)">
                        <label class="form-check-label" for="tp'.$count.'">'.$x.'</label>
                    </div>
                </th>
                <td>'.$x_value.'</td>
              </tr>';
              $count++;
        }
    } echo "</tbody>
            </table>";
}
?>