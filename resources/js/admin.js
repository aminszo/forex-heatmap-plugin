jQuery(document).ready(function ($) {
    const epStatus = $("#endpoint-status");
    $("#check-endpoint-btn").on("click", function () {
        $.ajax({
            url: fhData.adminUrl,
            method: "POST",
            data: {
                action: "fhm_test_endpoint"
            },
            beforeSend: function () {
                epStatus.text("...");
                epStatus.attr("class", "");
            },
            success: function (response) {
                console.log(response);
                if (response.success) {
                    epStatus.text("OK");
                    epStatus.attr("class", "success");

                } else {
                    epStatus.text("Fail");
                    epStatus.attr("class", "fail");
                }
            },
            error: function () {
                epStatus.text("Fail");
                epStatus.attr("class", "fail");
            }
        });
    });

    const apiStatus = $("#api-status");
    const apiResponseDiv = $("#api-status-response");

    $("#check-api-btn").on("click", function () {
        $.ajax({
            url: fhData.adminUrl,
            method: "POST",
            data: {
                action: "fhm_test_api"
            },
            beforeSend: function () {
                apiStatus.text("...");
                apiStatus.attr("class", "");
            },
            success: function (response) {
                console.log(response);
                apiResponseDiv
                    .text(JSON.stringify(response.data, null, 2))
                    .show();
                if (response.success) {
                    apiStatus.text("OK");
                    apiStatus.attr("class", "success");

                } else {
                    apiStatus.text("Fail");
                    apiStatus.attr("class", "fail");
                }
            },
            error: function () {
                apiStatus.text("Fail");
                apiStatus.attr("class", "fail");
            }
        });
    });
});