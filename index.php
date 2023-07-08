<?php
require('vendor/autoload.php');

# You can generate a Token from the "Tokens Tab" in the UI
$token = 'vs0euRWubbpPOzd7E7pVLNE2Glkit0VJuYIOprbJMhtEklY1-MA4ESe8YjUoanu3bepsf_4zaZCJ5LDF8XXjwA==';
$org = 'umm';
$bucket = 'iot';

# Next, we will instantiate the client and establish a connection
$client = new InfluxDB2\Client([
    "url" => "http://192.168.43.195:8086", // url and port of your instance
    "token" => $token,
    "bucket" => $bucket,
    "org" => $org,
    // "precision" => InfluxDB2\Model\WritePrecision::NS,
]);

if ($client->ping() != null) {
    // printf("conn influxdb success");
    $queryApi = $client->createQueryApi();

    $query_state_fan = "from(bucket: \"$bucket\")
        |> range(start: 0)
        |> filter(fn: (r) => r._field == \"state_fan\")
        |> last()";

    $query_auto_fan = "from(bucket: \"$bucket\")
        |> range(start: 0)
        |> filter(fn: (r) => r._field == \"auto_fan\")
        |> last()";

    $records_state_fan = $queryApi->query($query_state_fan, $org);
    $data_state_fan = json_encode($records_state_fan, JSON_PRETTY_PRINT);

    $records_auto_fan = $queryApi->query($query_auto_fan, $org);
    $data_auto_fan = json_encode($records_auto_fan, JSON_PRETTY_PRINT);

    $val_state_fan = json_decode($data_state_fan, true)[0]["records"][0]["values"]["_value"];
    $val_auto_fan = json_decode($data_auto_fan, true)[0]["records"][0]["values"]["_value"];
} else {
    printf("conn influxdb failed");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Smart Fan</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid mt-3 text-center">
        <h1>Smart Fan Control Panel</h1>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <div class="card mt-3" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Smart Fan</h5>
                        <form action="form.php" method="post">
                            <?php if ($val_auto_fan == 1) { ?>
                                <input type="hidden" name="state_auto" value="0">
                                <input type="hidden" name="fan_state_auto" <?php echo "value=" . $val_state_fan ?>>
                                <p class="card-text">On</p>
                                <button type="submit" class="btn btn-primary">Turn Off</button>
                            <?php } else { ?>
                                <input type="hidden" name="state_auto" value="1">
                                <input type="hidden" name="fan_state_auto" <?php echo "value=" . $val_state_fan ?>>
                                <p class="card-text">Off</p>
                                <button type="submit" class="btn btn-primary">Turn On</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col d-flex justify-content-center">
                <div class="card mt-3" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Fan Switch</h5>
                        <form action="form.php" method="post">
                            <?php if ($val_state_fan == 1) { ?>
                                <input type="hidden" name="state_fan" value="0">
                                <input type="hidden" name="auto_state_fan" <?php echo "value=" . $val_auto_fan ?>>
                                <p class="card-text">On</p>
                                <button type="submit" class="btn btn-primary" <?php echo ($val_auto_fan == 1 ? "disabled" : "") ?>>Turn Off</button>
                            <?php } else { ?>
                                <input type="hidden" name="state_fan" value="1">
                                <input type="hidden" name="auto_state_fan" <?php echo "value=" . $val_auto_fan ?>>
                                <p class="card-text">Off</p>
                                <button type="submit" class="btn btn-primary" <?php echo ($val_auto_fan == 1 ? "disabled" : "") ?>>Turn On</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>