<body style="background-color: antiquewhite;">
    <?php $uri = isset($_GET['from']) ? "../../pages/".$_GET['from']  : '../../Login.php'?>
    <div style="display:flex;justify-content: center">
        <p>
            HTTP Response 500: There was a problem with the server! Please check your Internet connection or contact the school to confirm
        </p><br>
        <a href="<?php echo $uri?>"> Try to Reconnect?</a>
    </div>
</body>