<?php

use yii\helpers\Url;

if (!empty($external) && $external == true) {
    $swaggerConfigPaths = \Yii::$app->assetManager->publish("@frontend/runtime/swagger-external.yaml");
} else {
    $swaggerConfigPaths = \Yii::$app->assetManager->publish("@frontend/runtime/swagger.yaml");
}
$fullConfigUrl = !empty($swaggerConfigPaths) ? end($swaggerConfigPaths) : null;
$fullConfigUrl =  Url::to($fullConfigUrl, true);

\Yii::info("Paths: $fullConfigUrl");

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <title>Base Backend API Doc</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@3.34.0/swagger-ui.css">
    <script src="https://unpkg.com/swagger-ui-dist@3.34.0/swagger-ui-standalone-preset.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@3.34.0/swagger-ui-bundle.js"></script>
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }
    </style>

</head>

<body>

<div id="swagger-ui"></div>
<script>
    var path = "<?= $fullConfigUrl ?>";
    window.onload = function () {
        // Build a system
        const ui = SwaggerUIBundle({
            dom_id: '#swagger-ui',
            deepLinking: true,
            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],
            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],
            layout: "StandaloneLayout",
            url: path,

            defaultModelsExpandDepth: 4,
            defaultModelExpandDepth: 3,
            displayRequestDuration: true,
            docExpansion: "list",
            filter: true,
            tagsSorter: "alpha",
            operationsSorter: "alpha",
            persistAuthorization: true,
        })
        window.ui = ui
    }
</script>
</body>

</html>

</html>