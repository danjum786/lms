<?php

function showAlert($color, $msg, $timeout = 3000)
{
    echo <<<ALERT
    <div id="autoDismissAlert" style="min-width: 200px; border-radius: 5px; text-align: center; padding: 20px; position: absolute; top: 50px; z-index:1000; right: 20px; color: white; background-color: $color;">
        $msg
    </div>
    <script>
        setTimeout(function() {
            var alertBox = document.getElementById('autoDismissAlert');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, $timeout);
    </script>
    ALERT;
}


function checkUser()
{
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}
