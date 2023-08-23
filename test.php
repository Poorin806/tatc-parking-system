<?php

    //Import connect.php file
    include_once('import/connect.php');

    //Import SQL Query file
    include_once('import/SQLQuery.php');

    $sql = "SELECT
              registration.*, prefix.prefix_name, student.std_id,
              student.name, student.lastname, student.img, department.dept_name,
              class_level.level_name, province.province_name, vehicle_type.vehiType_name
            FROM 
              registration, prefix, student,
              department, class_level, vehicle, province,
              vehicle_type
            WHERE
              (registration.std_id = student.std_id) AND
              (student.prefix_id = prefix.prefix_id) AND
              (student.dept_id = department.dept_id) AND
              (student.level_id = class_level.level_id) AND
              (registration.vehicle_id = vehicle.vehicle_id) AND
              (vehicle.vehiType_id = vehicle_type.vehiType_id) AND
              (vehicle.province_id = province.province_id) AND
              (registration.regis_id = 6)
    ";

    $data = [];
    $row = SQLSelectAssoc($con, $sql);

    foreach ($row as $key => $value) {
      switch($key) {
        case "copyOf_VehicleRegis" :
        case "copyOf_IdCard" :
        case "copyOf_StdID" :
        case "img_front" :
        case "img_side" :
        case "img_back" :
        case "pdfSignature" :
        case "img":
          $data[$key] = base64_encode($value);
          break;
        default :
          $data[$key] = $value;
      }
    }
?>
<img src="data:image/jpeg;base64,<?php echo $data['img'] ?>">